<?php

/**
 * Sales Order Invoice PDF model
 *
 * @category   Studio4
 * @package    Studio4_InvoiceConfig
 * @author     Studio4 <info@studio4.lt>
 */
 
abstract class Studio4_InvoiceConfig_Model_Order_Pdf_Abstract extends Mage_Sales_Model_Order_Pdf_Abstract {

	public $currentItemWithBg = true;
	/**
	 * Insert logo to pdf page
	 *
	 * @param Zend_Pdf_Page $page
	 * @param null $store
	 */
	protected function insertLogo(&$page, $store = null) {
		$image = Mage::helper('studio4_invoiceconfig/common')->getPathToMedia($this->params['logo']);
		if ($image) {
			if (is_file($image)) {
				$image = Zend_Pdf_Image::imageWithPath($image);
				
				//get zone and image sizes
				$maxWidth = 270;
				$maxHeight = 68;
				
				$leftX = 28;
				$centerY = $page->getHeight() - 46;
				
				$imageWidth = $image->getPixelWidth() * 0.75;
				$imageHeight = $image->getPixelHeight() * 0.75;
				
				//if image is larger than allowed zone - fit it keeping aspect ratio
				if ($imageWidth > $maxWidth || $imageHeight > $maxHeight)
				{
					$wRatio = $imageWidth / $maxWidth;
					$hRatio = $imageHeight / $maxHeight;
					
					$scaleRatio = $wRatio > $hRatio?$wRatio:$hRatio;
					$finalImageWidth = floor($imageWidth / $scaleRatio);
					$finalImageHeight = floor($imageHeight / $scaleRatio);
				}
				else
				{
					//insert image
					$finalImageWidth = floor($imageWidth);
					$finalImageHeight = floor($imageHeight);
				}
				
				
				//calculate image coordinates
				$x1 = $leftX;
				$y1 = $centerY - ($finalImageHeight / 2);
				$x2 = $leftX + $finalImageWidth;
				$y2 = $centerY + ($finalImageHeight / 2);

				//coordinates after transformation are rounded by Zend
				$page -> drawImage($image, $x1, $y1, $x2, $y2);
			}
		}
	}

	/**
	 * Insert address to pdf page
	 *
	 * @param Zend_Pdf_Page $page
	 * @param null $store
	 */
	protected function insertAddress(&$page, $store = null) {
		$page -> setFillColor(new Zend_Pdf_Color_Html('#'.$this -> params['textColor']));
		$font = $this -> _setFontRegular($page, 10);
		$page -> setLineWidth(0);
		$this -> y = $this -> y ? $this -> y : 815;
		$top = 815;
		foreach (explode("\n", Mage::getStoreConfig('sales/identity/address', $store)) as $value) {
			if ($value !== '') {
				$value = preg_replace('/<br[^>]*>/i', "\n", $value);
				foreach (Mage::helper('core/string')->str_split($value, 45, true, true) as $_value) {
					$page -> drawText(trim(strip_tags($_value)), $this -> getAlignRight($_value, 130, 440, $font, 10), $top, 'UTF-8');
					$top -= 12;
				}
			}
		}
		$this -> y = ($this -> y > $top) ? $top : $this -> y;
	}

    /**
     * Insert order to pdf page
     *
     * @param Zend_Pdf_Page $page
     * @param Mage_Sales_Model_Order $obj
     * @param bool $putOrderId
     */
	protected function insertOrder(&$page, $obj, $putOrderId = true) {
		$commonHelper = Mage::helper('studio4_invoiceconfig/common');
		
		if ($obj instanceof Mage_Sales_Model_Order) {
			$shipment = null;
			$order = $obj;
		} elseif ($obj instanceof Mage_Sales_Model_Order_Shipment) {
			$shipment = $obj;
			$order = $shipment -> getOrder();
		}
		
		$this -> y = $page->getHeight() - 135;
		
		/* Billing Address */
		$billingAddress = $this -> _formatAddress($order -> getBillingAddress() -> format('pdf'));

		/* Payment */
		
		$paymentInfo = $order->getPayment()->getMethodInstance()->getTitle();  
		//$paymentInfo = Mage::helper('payment') -> getInfoBlock($order -> getPayment()) -> setIsSecureMode(true) -> toPdf();
		$paymentInfo = htmlspecialchars_decode($paymentInfo, ENT_QUOTES);
		
		//$paymentInfo = preg_replace("/\s+{{pdf_row_separator}}\s+{{pdf_row_separator}}/",".", $paymentInfo);  /* replace two line breaks with . */
		//$paymentInfo = preg_replace("/\s*{{pdf_row_separator}}\s*/",".", $paymentInfo);  /* remove line breakes . */
		$paymentInfo = str_replace(array('{{pdf_row_separator}}', "\r\n"), "\n", $paymentInfo);
		$paymentInfo = preg_replace('/<br[^>]*>/i', "\n", $paymentInfo);
		$payment = explode("\n", $paymentInfo);
		
        foreach ($payment as $key=>$value){
            if (strip_tags(trim($value)) == '') {
                unset($payment[$key]);
            }
			else
			{
				$payment[$key] = strip_tags(trim($value));
				
				if (ctype_alnum(substr($payment[$key], -1)))
					$payment[$key] .= '.';
			}
        }
        reset($payment);
		
		$paymentInfo = implode(' ', $payment);

		/* Shipping Address and Method */
		if (!$order -> getIsVirtual()) {
			/* Shipping Address */
			$shippingAddress = $this -> _formatAddress($order -> getShippingAddress() -> format('pdf'));
			$shippingMethod = $order -> getShippingDescription();
		}

		// if swap blocks config value is no, seller is on the left
		if ($this->params['swapBlocks'] == 0) {
			$sellerInfoEndY = $this -> drawSellerInfo ($page, $order, 28, $this -> y);
			$buyerInfoEndY = $this -> drawBuyerInfo ($page, $order, 323, $this -> y);
		} else {
			$sellerInfoEndY = $this -> drawSellerInfo ($page, $order, 323, $this -> y);
			$buyerInfoEndY = $this -> drawBuyerInfo ($page, $order, 28, $this -> y);
		}
		
		if ($sellerInfoEndY >= $buyerInfoEndY) {
			$this -> y = $buyerInfoEndY;
		} else { 
			$this -> y = $sellerInfoEndY;
		}
		
		$page -> setLineColor(new Zend_Pdf_Color_Html('#' . $this -> params['lightTextColor']));
		$page -> setLineWidth(1);
		$page -> setLineDashingPattern(array(2, 2));
		$page -> drawLine(298, $this->y, 298, $page->getHeight() - 120);
		$page -> setLineDashingPattern(Zend_Pdf_Page::LINE_DASHING_SOLID);
		
		/* payment info */
		$this -> y -= 15;
		$font = $this -> _setFontRegular($page, 9);
		$page -> setFillColor(new Zend_Pdf_Color_Html('#'.$this -> params['lightTextColor']));
		$page -> drawText(Mage::helper('sales') -> __('Payment Method:'), 28, $this -> y, 'UTF-8');
		$yPayments = $this -> y;
		$paymentLeft = 120;
		
		if ($paymentInfo != '')
		{
			//foreach (Mage::helper('core/string')->str_split($value, 87, true, true) as $_value) {
			foreach ($commonHelper->sliceStringByPoints($paymentInfo, 447, $font, 9) as $_value) {
				$page -> drawText(strip_tags(trim($_value)), $paymentLeft, $yPayments, 'UTF-8');
				$yPayments -= 11;
			}
		}
        else
            $yPayments -= 11;
				
		$this -> y = $yPayments;

		if (!$order -> getIsVirtual()) {
			$page -> drawText(Mage::helper('sales') -> __('Shipping Method:'), 28, $this -> y, 'UTF-8');
			//foreach (Mage::helper('core/string')->str_split($shippingMethod. ' ('.$order -> formatPriceTxt($order -> getShippingAmount()).')', 87, true, true) as $_value) {
			foreach ($commonHelper->sliceStringByPoints($shippingMethod. ' ('.$order -> formatPriceTxt($order -> getShippingAmount()).')', 447, $font, 9) as $_value) {
				$page -> drawText(strip_tags(trim($_value)), 120, $this -> y, 'UTF-8');
				$this -> y -= 11;
			}			
			
			$tracks = array();
			if ($shipment) {
				$tracks = $shipment -> getAllTracks();
			}
			if (count($tracks)) {

				$page -> drawText(Mage::helper('sales') -> __('Tracking').':', 28, $this -> y, 'UTF-8');
				
				foreach ($tracks as $track) {

					$CarrierCode = $track -> getCarrierCode();
					if ($CarrierCode != 'custom') {
						$carrier = Mage::getSingleton('shipping/config') -> getCarrierInstance($CarrierCode);
						$carrierTitle = $carrier -> getConfigData('title');
					} else {
						$carrierTitle = Mage::helper('sales') -> __('Custom Value');
					}

					$maxTitleLen = 190;
					$endOfTitle = strlen($track -> getTitle()) > $maxTitleLen ? '...' : '';
					$truncatedTitle = substr($track -> getTitle(), 0, $maxTitleLen) . $endOfTitle;
					$page -> drawText($truncatedTitle . ' : ' . $track -> getNumber(), 120, $this -> y, 'UTF-8');
					$this -> y -= 11;
				}
			} 
			
			$this -> y -= 10;
		}
		
		//$this->y -= 20;
	}

	/**
	 * Insert title and number for concrete document type
	 *
	 * @param  Zend_Pdf_Page $page
	 * @param  string $text
	 * @return void
	 */
	public function insertDocNumber(Zend_Pdf_Page $page, $text, $obj, $putOrderId = true) {
		/* invoice number */
		$page -> setFillColor(new Zend_Pdf_Color_Html('#'.$this -> params['mainColor']));
		$font = $this -> _setFontRegular($page, 16);
		
		$page -> drawText(
			$text, 
			$this -> getAlignRight($text, 298, 270, $font, 16, 0),
			$page->getHeight() - 16 - 20 - ($putOrderId?0:8),
			'UTF-8');
		
		if ($putOrderId) {
			/* order number */
			if ($obj instanceof Mage_Sales_Model_Order) {
				$shipment = null;
				$order = $obj;
			} elseif ($obj instanceof Mage_Sales_Model_Order_Shipment) {
				$shipment = $obj;
				$order = $shipment -> getOrder();
			}
			
			$font = $this -> _setFontLight($page, 10);
			$page -> setFillColor(new Zend_Pdf_Color_Html('#'.$this -> params['lightTextColor']));
			$text = Mage::helper('sales') -> __('Order # ') . $order -> getRealOrderId();
			$page -> drawText(
				$text, 
				$this -> getAlignRight($text, 298, 268, $font, 10, 0), 
				$page->getHeight() - 10 - 43, 
				'UTF-8');
		}
	}

	/**
	 * Insert totals to pdf page
	 *
	 * @param  Zend_Pdf_Page $page
	 * @param  Mage_Sales_Model_Abstract $source
	 * @return Zend_Pdf_Page
	 */
	protected function insertTotals($page, $source) {
		$order = $source -> getOrder();
		$totals = $this -> _getTotalsList($source);
		$lineBlock = array('lines' => array(), 'height' => 25);
		foreach ($totals as $total) {
			$total -> setOrder($order) -> setSource($source);

			if ($total -> canDisplay()) {
				foreach ($total->getTotalsForDisplay() as $totalData) {
					//if at the bottom - add new page
					if ($this->y < 45)
						$page = $this->newPage();
					
					//if outputting grand total - change style
					$isGrandTotal = $total->source_field == 'grand_total';
					
					$this->y -= $isGrandTotal?22:21;
					
					//ouput label
					if ($isGrandTotal)
						$font = $this->_setFontBold($page, 10);
					else
						$font = $this->_setFontLight($page, 9);
					
					$page->drawText($totalData['label'], $source instanceof Mage_Sales_Model_Order_Creditmemo?360:354, $this->y, 'UTF-8');
					
					//output value
					if ($isGrandTotal)
					{
						$this->y -= 2;
						$font = $this->_setFontBold($page, 15);
						$page -> setFillColor(new Zend_Pdf_Color_Html('#' . $this -> params['mainColor']));
						$page->drawText($totalData['amount'], $this->getAlignRight($totalData['amount'], $page->getWidth() - 60 - 28, 60, $font, 15, 0), $this->y, 'UTF-8');
						$page -> setFillColor(new Zend_Pdf_Color_Html('#' . $this -> params['textColor']));
					}
					else
					{
						$font = $this->_setFontBold($page, 9);
						$page->drawText($totalData['amount'], $this->getAlignRight($totalData['amount'], $page->getWidth() - 60 - 28, 60, $font, 9, 0), $this->y, 'UTF-8');
					}
				}
			}
		}

		$this->y -= 11;
		$page -> setLineColor(new Zend_Pdf_Color_Html('#' . $this -> params['lightBg']));
		$page -> setLineWidth(0.5);
		$page -> drawLine(354, $this -> y, $page->getWidth() - 28, $this -> y);

		return $page;
	}

	/**
	 * Set font as regular
	 *
	 * @param  Zend_Pdf_Page $object
	 * @param  int $size
	 * @return Zend_Pdf_Resource_Font
	 */
	public function _setFontRegular($object, $size = 7) {
		$font = Zend_Pdf_Font::fontWithPath(Mage::helper('studio4_invoiceconfig/common')->getFontPath('Roboto', 'Regular'));
		//$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
		$object -> setFont($font, $size);
		return $font;
	}

	/**
	 * Set font as bold
	 *
	 * @param  Zend_Pdf_Page $object
	 * @param  int $size
	 * @return Zend_Pdf_Resource_Font
	 */
	public function _setFontBold($object, $size = 7) {
		$font = Zend_Pdf_Font::fontWithPath(Mage::helper('studio4_invoiceconfig/common')->getFontPath('Roboto', 'Bold'));
		//$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
		$object -> setFont($font, $size);
		return $font;
	}

	/**
	 * Set font as italic
	 *
	 * @param  Zend_Pdf_Page $object
	 * @param  int $size
	 * @return Zend_Pdf_Resource_Font
	 */
	public function _setFontItalic($object, $size = 7) {
		$font = Zend_Pdf_Font::fontWithPath(Mage::helper('studio4_invoiceconfig/common')->getFontPath('Roboto', 'Italic'));
		//$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_ITALIC);
		$object -> setFont($font, $size);
		return $font;
	}

/**
	 * Set font as Light
	 *
	 * @param  Zend_Pdf_Page $object
	 * @param  int $size
	 * @return Zend_Pdf_Resource_Font
	 */
	public function _setFontLight($object, $size = 7) {
		$font = Zend_Pdf_Font::fontWithPath(Mage::helper('studio4_invoiceconfig/common')->getFontPath('Roboto', 'Light'));
		//$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
		$object -> setFont($font, $size);
		return $font;
	}

	/**
	 * Create new page and assign to PDF object
	 *
	 * @param  array $settings
	 * @return Zend_Pdf_Page
	 */
	public function newPage(array $settings = array()) {
		$page = parent::newPage($settings);
		$this->insertBackgroundImage($page);
		return $page;
	}

    /**
     * Draw lines
     *
     * draw items array format:
     * lines        array;array of line blocks (required)
     * shift        int; full line height (optional)
     * height       int;line spacing (default 10)
     *
     * line block has line columns array
     *
     * column array format
     * text         string|array; draw text (required)
     * feed         int; x position (required)
     * font         string; font style, optional: bold, italic, regular
     * font_file    string; path to font file (optional for use your custom font)
     * font_size    int; font size (default 7)
     * align        string; text align (also see feed parametr), optional left, right
     * height       int;line spacing (default 10)
     *
     * @param  Zend_Pdf_Page $page
     * @param  array $draw
     * @param  array $pageSettings
     * @throws Mage_Core_Exception
     * @return Zend_Pdf_Page
     */
	public function drawLineBlocks(Zend_Pdf_Page $page, array $draw, array $pageSettings = array(), $isTotal = 0) {
		foreach ($draw as $itemsProp) {
			if (!isset($itemsProp['lines']) || !is_array($itemsProp['lines'])) {
				Mage::throwException(Mage::helper('sales') -> __('Invalid draw line data. Please define "lines" array.'));
			}
			$lines = $itemsProp['lines'];
			$height = isset($itemsProp['height']) ? $itemsProp['height'] : 10;

			if (empty($itemsProp['shift'])) {
				$shift = 0;
				foreach ($lines as $line) {
					$maxHeight = 0;
					foreach ($line as $column) {
						$lineSpacing = !empty($column['height']) ? $column['height'] : $height;
						if (!is_array($column['text'])) {
							$column['text'] = array($column['text']);
						}
						$top = 0;
						foreach ($column['text'] as $part) {
							$top += $lineSpacing;
						}

						$maxHeight = $top > $maxHeight ? $top : $maxHeight;
					}
					$shift += $maxHeight;
				}
				$itemsProp['shift'] = $shift;
			}

			if ($this -> y - $itemsProp['shift'] < 15) {
				$page = $this -> newPage($pageSettings);
			}

			$i = 0;
			$lineIsGrandTotal = 0;
			foreach ($lines as $line) {
				$i++;
				if ($isTotal == 1 && $i == count($lines))
				{
					 $lineIsGrandTotal = 1;
				};

				$maxHeight = 0;
				foreach ($line as $column) {
					$fontSize = empty($column['font_size']) ? 10 : $column['font_size'];
					if ($fontSize <= 8) {$this->y = $this->y+9; $column['height'] = 17;} /* if text is small, reduce padding*/
					if (!empty($column['font_file'])) {
						$font = Zend_Pdf_Font::fontWithPath($column['font_file']);
						$page -> setFont($font, $fontSize);
					} else {
						$fontStyle = empty($column['font']) ? 'regular' : $column['font'];
						switch ($fontStyle) {
							case 'bold' :
								$font = $this -> _setFontBold($page, $fontSize);
								break;
							case 'italic' :
								$font = $this -> _setFontItalic($page, $fontSize);
								break;
							default :
								$font = $this -> _setFontRegular($page, $fontSize);
								break;
						}
					}

					if (!is_array($column['text'])) {
						$column['text'] = array($column['text']);
					}

					$lineSpacing = !empty($column['height']) ? $column['height'] : $height;
					$top = 0;
					foreach ($column['text'] as $part) {
						if ($this -> y - $lineSpacing < 15) {
							$page = $this -> newPage($pageSettings);
						}
						$feed = $column['feed'];
						$textAlign = empty($column['align']) ? 'left' : $column['align'];
						$width = empty($column['width']) ? 0 : $column['width'];
						switch ($textAlign) {
							case 'right' :
								if ($width) {
									$feed = $this -> getAlignRight($part, $feed, $width, $font, $fontSize);
								} else {
									$feed = $feed - $this -> widthForStringUsingFontSize($part, $font, $fontSize);
								}
								break;
							case 'center' :
								if ($width) {
									$feed = $this -> getAlignCenter($part, $feed, $width, $font, $fontSize);
								}
								break;
						}

						/* background of totals */
						if ($isTotal == 1) {
							$page -> setFillColor(new Zend_Pdf_Color_Html('#'.$this -> params['textColor']));

							if ($column['feed'] == 565) {
								if ($lineIsGrandTotal == 1) {
									$page -> setFillColor(new Zend_Pdf_Color_Html('#'.$this -> params['mainColor']));
									$page -> drawRectangle($column['feed'] + 5, $this -> y + 15, $column['feed'] - 85, $this -> y - 8, $fillType = Zend_Pdf_Page::SHAPE_DRAW_FILL);
									$page -> setFillColor(new Zend_Pdf_Color_Html('#'.$this -> params['inverseColor']));
								} else {
									$page -> setFillColor(new Zend_Pdf_Color_Html('#'.$this -> params['lightBg']));
									$page -> drawRectangle($column['feed'] + 5, $this -> y + 15, $column['feed'] - 85, $this -> y - 8, $fillType = Zend_Pdf_Page::SHAPE_DRAW_FILL);
									$page -> setFillColor(new Zend_Pdf_Color_Html('#'.$this -> params['textColor']));
								}
							}
						}
						/* End of background of totals */
						
						$page -> drawText($part, $feed, $this -> y - $top, 'UTF-8');
						$top += $lineSpacing;
					}

					$maxHeight = $top > $maxHeight ? $top : $maxHeight;
				}
				$this -> y -= $maxHeight;
			}
		}

		return $page;
	}

	//insert siganture image
	protected function insertSigniture(&$page, $store, $signatureHeight) {
		$image = Mage::helper('studio4_invoiceconfig/common')->getPathToMedia($this->params['signature']);
		if ($image) {
			if (is_file($image)) {
				$image = Zend_Pdf_Image::imageWithPath($image);
				
				//get zone and image sizes
				$maxWidth = 224;
				$maxHeight = 60;
				
				$imageWidth = $image->getPixelWidth() * 0.75;
				$imageHeight = $image->getPixelHeight() * 0.75;
				
				//if image is larger than allowed zone - fit it keeping aspect ratio
				if ($imageWidth > $maxWidth || $imageHeight > $maxHeight)
				{
					$wRatio = $imageWidth / $maxWidth;
					$hRatio = $imageHeight / $maxHeight;
					
					$scaleRatio = $wRatio > $hRatio?$wRatio:$hRatio;
					$finalImageWidth = floor($imageWidth / $scaleRatio);
					$finalImageHeight = floor($imageHeight / $scaleRatio);
				}
				else
				{
					//insert image
					$finalImageWidth = floor($imageWidth);
					$finalImageHeight = floor($imageHeight);
				}
				
				$centerY = $this->y - $signatureHeight / 2;
				$rightX = $page->getWidth() - 28;
				
				$x1 = $rightX - $finalImageWidth;
				$y1 = $centerY - ($finalImageHeight / 2);
				$x2 = $rightX;
				$y2 = $centerY + ($finalImageHeight / 2);

				//coordinates after transformation are rounded by Zend
				$page -> drawImage($image, $x1, $y1, $x2, $y2);
			}
		}
	}

	protected function insertBackgroundImage(&$page) {
		$image = Mage::helper('studio4_invoiceconfig/common')->getPathToMedia($this->params['background']);
		if ($image) {
			if (is_file($image)) {
				$image = Zend_Pdf_Image::imageWithPath($image);
				
				//get page and image sizes
				
				$pageWidth = $page->getWidth();
				$pageHeight = $page->getHeight();
				
				$centerX = $pageWidth / 2;
				$centerY = $pageHeight / 2;
				
				$imageWidth = $image->getPixelWidth() * 0.75;
				$imageHeight = $image->getPixelHeight() * 0.75;
				
				//if image is larger than the page - fit it keeping aspect ratio
				if ($imageWidth > $pageWidth || $imageHeight > $pageHeight)
				{
					$wRatio = $imageWidth / $pageWidth;
					$hRatio = $imageHeight / $pageHeight;
					
					$scaleRatio = $wRatio > $hRatio?$wRatio:$hRatio;
					$finalImageWidth = floor($imageWidth / $scaleRatio);
					$finalImageHeight = floor($imageHeight / $scaleRatio);
				}
				else
				{
					//insert centered image
					$finalImageWidth = floor($imageWidth);
					$finalImageHeight = floor($imageHeight);
				}
				
				
				//calculate image coordinates
				$x1 = $centerX - ($finalImageWidth / 2);
				$y1 = $centerY - ($finalImageHeight / 2);
				$x2 = $centerX + ($finalImageWidth / 2);
				$y2 = $centerY + ($finalImageHeight / 2);
				
				//coordinates after transformation are rounded by Zend
				$page -> drawImage($image, $x1, $y1, $x2, $y2);
			}
		}
	}

	public function drawBuyerInfo($page, $order, $xoffset, $startY) {
		$commonHelper = Mage::helper('studio4_invoiceconfig/common');
		$appendShippingInfo = 0;	
		$InvoiceBillingAddress = $order->getBillingAddress()->format('pdf');		
		if (!$order->getIsVirtual()) {
			$InvoiceShippingAddress = $order->getShippingAddress()->format('pdf');	
		}
		
		if (!$order->getIsVirtual() && $InvoiceBillingAddress != $InvoiceShippingAddress) {
			$appendShippingInfo = 1;
		}
		
		$infoY = $startY;
		$page -> setFillColor(new Zend_Pdf_Color_Html('#'.$this -> params['lightTextColor']));
		$this -> _setFontLight($page, 10);
		$page -> drawText(Mage::helper('sales') -> __('Customer') . ':', $xoffset, $infoY, 'UTF-8');
		$infoY -= 22;
			
		$page -> setFillColor(new Zend_Pdf_Color_Html('#'.$this -> params['mainColor']));
		$font = $this -> _setFontRegular($page, 16);
		
		$fullName = strip_tags(ltrim($order -> getBillingAddress() -> getFirstname() . ' ' . $order -> getBillingAddress() -> getLastname()));
		
		if ($order -> getBillingAddress() -> getCompany() != '') {
			//foreach (Mage::helper('core/string')->str_split($order -> getBillingAddress() -> getCompany(), 25) as $_value) {
			foreach ($commonHelper->sliceStringByPoints(trim(strip_tags(mb_strtoupper($order -> getBillingAddress() -> getCompany(), 'UTF-8'))), 244, $font, 16) as $_value) {
				$page -> drawText(trim($_value), $xoffset, $infoY, 'UTF-8');
				$infoY -= 16;
			}
			
			if ($fullName != '')
			{
				$page -> setFillColor(new Zend_Pdf_Color_Html('#'.$this -> params['textColor']));
				$font = $this -> _setFontRegular($page, 9);
				$infoY -= 18;
				Mage::helper('studio4_invoiceconfig/common')->drawIconOnPage($page, $xoffset, $infoY, 'buyer');
				
				//foreach (Mage::helper('core/string')->str_split($fullName, 46) as $_value) {
				foreach ($commonHelper->sliceStringByPoints(trim($fullName), 224, $font, 9) as $_value) {
					$page -> drawText(trim($_value), $xoffset + 20, $infoY, 'UTF-8');
					$infoY -= 11;
				}

				$infoY -= 12;
			}
		} else {
			//foreach (Mage::helper('core/string')->str_split($fullName, 25) as $_value) {
			foreach ($commonHelper->sliceStringByPoints(mb_strtoupper($fullName, 'UTF-8'), 244, $font, 16) as $_value) {
				$page -> drawText(trim($_value), $xoffset, $infoY, 'UTF-8');
				$infoY -= 16;
			}
			$infoY -= 18;
		}
		
		$page -> setFillColor(new Zend_Pdf_Color_Html('#'.$this -> params['textColor']));
		$font = $this -> _setFontRegular($page, 9);

		Mage::helper('studio4_invoiceconfig/common')->drawIconOnPage($page, $xoffset, $infoY, 'address');
		
		$address = array();
		$address[] = $order -> getBillingAddress() -> getStreet1();
		$address[] = $order -> getBillingAddress() -> getStreet2();
		$address[] = $order -> getBillingAddress() -> getCity() . ($order -> getBillingAddress() -> getRegion() ? ', ' . $order -> getBillingAddress() -> getRegion() : '') . ($order -> getBillingAddress() -> getPostcode() ? ', ' . $order -> getBillingAddress() -> getPostcode() : '');
		$address[] = $order -> getBillingAddress() -> getCountryModel() -> getName();
		
		foreach ($address as $addLine)
		{
			$addLine = strip_tags(ltrim($addLine));
			
			if ($addLine != '')
			{
				//foreach (Mage::helper('core/string')->str_split($addLine, 46) as $_value) {
				foreach ($commonHelper->sliceStringByPoints($addLine, 224, $font, 9) as $_value) {
					$page -> drawText(trim(strip_tags($_value)), $xoffset + 20, $infoY, 'UTF-8');
					$infoY -= 11;
				}
			}
		}
		
		$infoY -= 12;

		if ($order -> getBillingAddress() -> getTelephone() != '') {
			Mage::helper('studio4_invoiceconfig/common')->drawIconOnPage($page, $xoffset, $infoY, 'phone');
			
			$phone = strip_tags(ltrim($order -> getBillingAddress() -> getTelephone()));
			//foreach (Mage::helper('core/string')->str_split($phone, 46) as $_value) {
			foreach ($commonHelper->sliceStringByPoints($phone, 224, $font, 9) as $_value) {
				$page -> drawText(trim(strip_tags($_value)), $xoffset + 20, $infoY, 'UTF-8');
				$infoY -= 11;
			}
			
			$infoY -= 12;
		}

		if ($order -> getCustomerEmail() != '') {
			Mage::helper('studio4_invoiceconfig/common')->drawIconOnPage($page, $xoffset, $infoY, 'email');
			$email = strip_tags(ltrim($order -> getCustomerEmail()));
			//foreach (Mage::helper('core/string')->str_split($email, 46) as $_value) {
			foreach ($commonHelper->sliceStringByPoints($email, 224, $font, 9) as $_value) {
				$page -> drawText(trim(strip_tags($_value)), $xoffset + 20, $infoY, 'UTF-8');
				$infoY -= 11;
			}
			$infoY -= 12;
		}
		
		if ($order -> getBillingAddress() -> getVatId() != '') {
			$page -> drawText(Mage::helper('sales') -> __('VAT:').' '.strip_tags(ltrim($order -> getBillingAddress() -> getVatId())), $xoffset+20, $infoY, 'UTF-8');
			$infoY -= 11;
		} elseif ($order->getData('customer_taxvat') != '') {
			$page -> drawText(Mage::helper('sales') -> __('VAT:').' '.strip_tags(ltrim($order->getData('customer_taxvat'))), $xoffset+20, $infoY, 'UTF-8');
			$infoY -= 11;
		}
		
		/* If shipping is different from billing info, add info that differs */
		if ($appendShippingInfo == 1 && $this -> params['showShippingAddress'] == 1) {
			$nameDiffers = 
				$order -> getBillingAddress() -> getFirstname() != $order -> getShippingAddress() -> getFirstname() || 
				$order -> getBillingAddress() -> getLastname() != $order -> getShippingAddress() -> getLastname();
			
			$addressDiffers =
				$order -> getBillingAddress() -> getStreet1() != $order -> getShippingAddress() -> getStreet1() ||
				$order -> getBillingAddress() -> getStreet2() != $order -> getShippingAddress() -> getStreet2() ||
				$order -> getBillingAddress() -> getCity() != $order -> getShippingAddress() -> getCity() ||
				$order -> getBillingAddress() -> getRegion() != $order -> getShippingAddress() -> getRegion() ||
				$order -> getBillingAddress() -> getPostcode() != $order -> getShippingAddress() -> getPostcode() ||
				$order -> getBillingAddress() -> getCountryModel() -> getName() != $order -> getShippingAddress() -> getCountryModel() -> getName();
				
			$phoneDiffers = $order -> getBillingAddress() -> getTelephone() != $order -> getShippingAddress() -> getTelephone() && $order -> getShippingAddress() -> getTelephone() != '';
			
			if ($nameDiffers || $addressDiffers || $phoneDiffers)
			{
				$page -> setFillColor(new Zend_Pdf_Color_Html('#'.$this -> params['lightTextColor']));
				$this -> _setFontRegular($page, 10);	
				$infoY -= 15;
				$page -> drawText(Mage::helper('sales') -> __('Shipping info:'), $xoffset, $infoY, 'UTF-8');
				$infoY -= 22;
				$page -> setFillColor(new Zend_Pdf_Color_Html('#'.$this -> params['textColor']));
				$font = $this -> _setFontRegular($page, 9);
				
				if ($nameDiffers || true) {
					Mage::helper('studio4_invoiceconfig/common')->drawIconOnPage($page, $xoffset, $infoY, 'buyer');
					
					$nameStr = strip_tags(ltrim(Mage::helper('sales') -> __('Buyer:') . ' ' . $order -> getShippingAddress() -> getFirstname() . ' ' . $order -> getShippingAddress() -> getLastname()));
					
					//foreach (Mage::helper('core/string')->str_split($nameStr, 46) as $_value) {
					foreach ($commonHelper->sliceStringByPoints($nameStr, 224, $font, 9) as $_value) {
						$page -> drawText(trim(strip_tags($_value)), $xoffset + 20, $infoY, 'UTF-8');
						$infoY -= 11;
					}
					
					$infoY -= 11;
				}
				
				if ($addressDiffers) {
					Mage::helper('studio4_invoiceconfig/common')->drawIconOnPage($page, $xoffset, $infoY, 'address');
					
					$address = array();
					$address[] = $order -> getShippingAddress() -> getStreet1();
					$address[] = $order -> getShippingAddress() -> getStreet2();
					$address[] = $order -> getShippingAddress() -> getCity() . ($order -> getShippingAddress() -> getRegion() ? ', ' . $order -> getShippingAddress() -> getRegion() : '') . ($order -> getShippingAddress() -> getPostcode() ? ', ' . $order -> getShippingAddress() -> getPostcode() : '');
					$address[] = $order -> getShippingAddress() -> getCountryModel() -> getName();
					
					foreach ($address as $addLine)
					{
						$addLine = strip_tags(ltrim($addLine));
						
						if ($addLine != '')
						{
							//foreach (Mage::helper('core/string')->str_split($addLine, 46) as $_value) {
							foreach ($commonHelper->sliceStringByPoints($addLine, 224, $font, 9) as $_value) {
								$page -> drawText(trim(strip_tags($_value)), $xoffset + 20, $infoY, 'UTF-8');
								$infoY -= 11;
							}
						}
					}
					
					$infoY -= 11;
				}
				
				if ($phoneDiffers) {
					Mage::helper('studio4_invoiceconfig/common')->drawIconOnPage($page, $xoffset, $infoY, 'phone');
					
					$phone = strip_tags(ltrim($order -> getShippingAddress() -> getTelephone()));
					//foreach (Mage::helper('core/string')->str_split($phone, 46) as $_value) {
					foreach ($commonHelper->sliceStringByPoints($phone, 224, $font, 9) as $_value) {
						$page -> drawText(trim(strip_tags($_value)), $xoffset + 20, $infoY, 'UTF-8');
						$infoY -= 11;
					}
					
					$infoY -= 12;
				}
			}
		}
		/* end of shipping info output */
		
		return $infoY;
	}

	public function drawSellerInfo($page, $order, $xoffset, $startY) {
		$commonHelper = Mage::helper('studio4_invoiceconfig/common');
		$infoY = $startY;	
		$page -> setFillColor(new Zend_Pdf_Color_Html('#'.$this -> params['lightTextColor']));
		$this -> _setFontLight($page, 10);
		$page -> drawText(Mage::helper('sales') -> __('Seller:'), $xoffset, $infoY, 'UTF-8');
		$infoY -= 22;
		
		if ($this -> params['companyName'] != '') {
			$page -> setFillColor(new Zend_Pdf_Color_Html('#'.$this -> params['mainColor']));
			$font = $this -> _setFontRegular($page, 16);
			
			//foreach (Mage::helper('core/string')->str_split($this -> params['companyName'], 25) as $_value) {
			foreach ($commonHelper->sliceStringByPoints(trim(strip_tags(mb_strtoupper($this -> params['companyName'], 'UTF-8'))), 244, $font, 16) as $_value) {
				$page -> drawText(trim($_value), $xoffset, $infoY, 'UTF-8');
				$infoY -= 16;
			}
		}
		$infoY -= 18;
		
		$page -> setFillColor(new Zend_Pdf_Color_Html('#'.$this -> params['textColor']));
		$font = $this -> _setFontRegular($page, 9);
		
		if (!empty($this -> params['companyAddress1'])) {
			Mage::helper('studio4_invoiceconfig/common')->drawIconOnPage($page, $xoffset, $infoY, 'address');
			
			foreach (explode("\n", $this -> params['companyAddress1']) as $value) {
				if ($value !== '') {
					$value = preg_replace('/<br[^>]*>/i', " ", $value);
					//foreach (Mage::helper('core/string')->str_split($value, 46, true, true) as $_value) {
					foreach ($commonHelper->sliceStringByPoints(trim(strip_tags($value)), 224, $font, 9) as $_value) {
						$page -> drawText(trim($_value), $xoffset+20, $infoY, 'UTF-8');
						$infoY -= 11;
					}
				}
			}
		}

		$infoY -= 12;

		if ($this -> params['companyPhone'] != '') {
			Mage::helper('studio4_invoiceconfig/common')->drawIconOnPage($page, $xoffset, $infoY, 'phone');
			
			$phone = strip_tags(ltrim($this -> params['companyPhone']));
			//foreach (Mage::helper('core/string')->str_split($phone, 46, true, true) as $_value) {
			foreach ($commonHelper->sliceStringByPoints(trim(strip_tags($phone)), 224, $font, 9) as $_value) {
				$page -> drawText(trim($_value), $xoffset+20, $infoY, 'UTF-8');
				$infoY -= 11;
			}
			
			$infoY -= 12;
		}
		
		if ($this -> params['companyEmail'] != '') {
			Mage::helper('studio4_invoiceconfig/common')->drawIconOnPage($page, $xoffset, $infoY, 'email');
			
			$email = strip_tags(ltrim($this -> params['companyEmail']));
			//foreach (Mage::helper('core/string')->str_split($email, 46, true, true) as $_value) {
			foreach ($commonHelper->sliceStringByPoints(trim(strip_tags($email)), 224, $font, 9) as $_value) {
				$page -> drawText(trim($_value), $xoffset+20, $infoY, 'UTF-8');
				$infoY -= 11;
			}

			$infoY -= 12;
		}

		if (!empty($this -> params['companyAdditionalInfo'])) {
			foreach (explode("\n", $this -> params['companyAdditionalInfo']) as $value) {
				if ($value !== '') {
					$value = preg_replace('/<br[^>]*>/i', " ", $value);
					//foreach (Mage::helper('core/string')->str_split($value, 46, true, true) as $_value) {
					foreach ($commonHelper->sliceStringByPoints(trim(strip_tags($value)), 224, $font, 9) as $_value) {
						$page -> drawText(trim($_value), $xoffset+20, $infoY, 'UTF-8');
						$infoY -= 11;
					}
				}
			}
		}
		
		return $infoY;
	} 

}

?>
