<?php

/**
 * Sales Order Invoice PDF model
 *
 * @category   Studio4
 * @package    Studio4_InvoiceConfig
 * @author     Studio4 <info@studio4.lt>
 */
 
class Studio4_InvoiceConfig_Model_Order_Pdf_Invoice extends Studio4_InvoiceConfig_Model_Order_Pdf_Abstract {
/**
	 * Draw header for item table
	 *
	 * @param Zend_Pdf_Page $page
	 * @return void
	 */

	protected function _drawHeader(Zend_Pdf_Page $page) {
		/* Add table head */
		//draw heading background
		$this -> _setFontLight($page, 10);
		$page -> setFillColor(new Zend_Pdf_Color_Html('#' . $this -> params['mainColor']));
		$page -> drawRectangle(18, $this -> y, $page->getWidth() - 18, $this -> y - 30, $fillType = Zend_Pdf_Page::SHAPE_DRAW_FILL);
		$page -> setFillColor(new Zend_Pdf_Color_Html('#' . $this -> params['inverseColor']));
		
		$this -> y -= 18;
			
		$font = $this->_setFontLight($page, 10);
		//ouput column labels
		$page->drawText(Mage::helper('sales') -> __('Images'), 35, $this->y, 'UTF-8');
		$page->drawText(Mage::helper('sales') -> __('Products'), 145, $this->y, 'UTF-8');
		$page->drawText(Mage::helper('sales') -> __('SKU'), 285, $this->y, 'UTF-8');
		$page->drawText(Mage::helper('sales') -> __('Price'), 354, $this->y, 'UTF-8');
		$page->drawText(Mage::helper('sales') -> __('Qty'), 416, $this->y, 'UTF-8');
		$page->drawText(Mage::helper('sales') -> __('Tax'), 469, $this->y, 'UTF-8');
		$page->drawText(Mage::helper('sales') -> __('Subtotal'), $this->getAlignRight(Mage::helper('sales') -> __('Subtotal'), $page->getWidth() - 60 - 28, 60, $font, 10, 0), $this->y, 'UTF-8');

		$page -> setFillColor(new Zend_Pdf_Color_GrayScale(0));
		$this -> y -= 12;
	}

	/**
	 * Return PDF document
	 *
	 * @param  array $invoices
	 * @return Zend_Pdf
	 */
	public function getPdf($invoices = array()) {
		$this -> _beforeGetPdf();
		$this -> _initRenderer('invoice');

		$pdf = new Zend_Pdf();
		$this -> _setPdf($pdf);
		$style = new Zend_Pdf_Style();
		$this -> _setFontBold($style, 10);
		
		$commonHelper = Mage::helper('studio4_invoiceconfig/common');

		foreach ($invoices as $invoice) {
			
			//collect params (settings)
				
			$storeId = $invoice -> getStoreId();
			$params = array(
				'mainColor' => $commonHelper->getStoreConfig('invoiceconfig/s4colors/s4_main_color', $storeId, '000000'), 
				'textColor' => $commonHelper->getStoreConfig('invoiceconfig/s4colors/s4_text_color', $storeId, '000000'), 
				//'textColor' => Mage::getStoreConfig('invoiceconfig/s4colors/s4_text_color', $storeId), 
				'lightTextColor' => $commonHelper->getStoreConfig('invoiceconfig/s4colors/s4_lighttext_color',  $storeId, '444444'), 
				'inverseColor' => $commonHelper->getStoreConfig('invoiceconfig/s4colors/s4_inverted_color',  $storeId, 'FFFFFF'), 
				'lightBg' => $commonHelper->getStoreConfig('invoiceconfig/s4colors/s4_lightbg_color',  $storeId, 'CCCCCC'), 
				'logo' => Mage::getStoreConfig('invoiceconfig/s4images/s4_pdf_logo', $storeId), 
				'background' => Mage::getStoreConfig('invoiceconfig/s4images/s4_pdf_bg',  $storeId), 
				'signature' => Mage::getStoreConfig('invoiceconfig/s4images/s4_pdf_signature',  $storeId), 
				'signitureName' => Mage::getStoreConfig('invoiceconfig/s4general/s4_text_signature',  $storeId), 
				'textSuffix' => Mage::getStoreConfig('invoiceconfig/s4general/s4_text_suffix',  $storeId),
				'companyName' => Mage::getStoreConfig('invoiceconfig/s4general/s4_company_name',  $storeId), 
				'companyAddress1' => Mage::getStoreConfig('invoiceconfig/s4general/s4_company_address',  $storeId), 
				'companyPhone' => Mage::getStoreConfig('invoiceconfig/s4general/s4_company_phone',  $storeId), 
				'companyEmail' => Mage::getStoreConfig('invoiceconfig/s4general/s4_company_email',  $storeId), 
				'companyAdditionalInfo' => Mage::getStoreConfig('invoiceconfig/s4general/s4_company_additionalinfo',  $storeId),
				'showComments' => Mage::getStoreConfig('invoiceconfig/s4general/s4_show_comments',  $storeId),
				'swapBlocks' => Mage::getStoreConfig('invoiceconfig/s4general/s4_swap_blocks',  $storeId),
				'showShippingAddress' => Mage::getStoreConfig('invoiceconfig/s4general/s4_show_customer_shipping_address',  $storeId),
				'customInvoicePrefix' => Mage::getStoreConfig('invoiceconfig/s4general/s4_custom_prefix',  $storeId),
				'dueDateShift' => Mage::getStoreConfig('invoiceconfig/s4payment/s4_due_date_shift',  $storeId),
				'paymentInformation' => Mage::getStoreConfig('invoiceconfig/s4payment/s4_payment_information',  $storeId),
				'invoiceDate' => Mage::getStoreConfig('invoiceconfig/s4general/s4_invoice_date',  $storeId)
			);
			
			$this -> params = $params;	
			
			if ($invoice -> getStoreId()) {
				Mage::app() -> getLocale() -> emulate($invoice -> getStoreId());
				Mage::app() -> setCurrentStore($invoice -> getStoreId());
			}
			
			//add pdf page
			$page = $this -> newPage();
			$order = $invoice -> getOrder();
			
			/* Add logo */
			$this -> insertLogo($page, $invoice -> getStore());

			/* Add document text and number  -- invoice #*/
			if ($this -> params['customInvoicePrefix'] != '') {
				$newInvoiceNumber = $this -> params['customInvoicePrefix'] . substr($invoice -> getIncrementId(), 3);
			} else {
				$newInvoiceNumber = $invoice -> getIncrementId();
			}
			
			$this -> insertDocNumber(
					$page, 
					mb_strtoupper(Mage::helper('sales') -> __('Invoice # ') . $newInvoiceNumber, 'UTF-8'), 
					$order, 
					Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, $order -> getStoreId())
				);

			/* insert date */
			$font = $this->_setFontLight($page, 8);
	
			if ($this -> params['invoiceDate'] == 1) {
				$invoiceDate = Mage::helper('core')->formatDate( $invoice->getCreatedAtStoreDate(), 'medium', false);
			} else {
				$invoiceDate =  Mage::helper('core')->formatDate($order->getCreatedAtStoreDate(), 'medium', false);
			}
	
			$page->setFillColor(new Zend_Pdf_Color_Html('#'.$this->params['lightTextColor']));
			$page->drawText(
				$invoiceDate, 
				$this->getAlignRight($invoiceDate, 298, 267, $font, 8, 0), 
				$page->getHeight() - 8 - 60 + (Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, $order -> getStoreId())?0:8), 
				'UTF-8');

			/* Sepperator */

			$page -> setLineColor(new Zend_Pdf_Color_Html('#' . $this -> params['lightTextColor']));
			$page -> setLineWidth(1);
			$page -> setLineDashingPattern(array(2, 2));
			$page -> drawLine(18, $page->getHeight() - 92, $page->getWidth() - 18, $page->getHeight() - 92);
			$page -> setLineDashingPattern(Zend_Pdf_Page::LINE_DASHING_SOLID);

			/* Add head -- shipping billing address */
			$this -> insertOrder($page, $order, Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, $order -> getStoreId()));

			/* Add table header */
			$this -> _drawHeader($page);
			
			/* Add body */
			
			//let's count the lines
			$lineCount = 0;
			foreach ($invoice->getAllItems() as $item) {
				if ($item -> getOrderItem() -> getParentItem()) {
					continue;
				}
				$lineCount++;
			}
			
			//set the background on/off for the first item
			$this->currentItemWithBg = ($lineCount % 2) == 0;
			
			foreach ($invoice->getAllItems() as $item) {
				if ($item -> getOrderItem() -> getParentItem()) {
					continue;
				}
				/* Draw item */
				$this -> _drawItem($item, $page, $order);
				$page = end($this->_getPdf() -> pages);
				$this->currentItemWithBg = !$this->currentItemWithBg;
				
				$page -> setLineColor(new Zend_Pdf_Color_Html('#' . $this -> params['lightBg']));
				$page -> setLineWidth(0.5);
				$page -> drawLine(18, $this -> y, $page->getWidth() - 18, $this -> y);
			}
			
			//draw a line after last item
			$page -> setLineColor(new Zend_Pdf_Color_Html('#' . $this -> params['lightBg']));
			$page -> setLineWidth(0.5);
			$page -> drawLine(18, $this -> y, $page->getWidth() - 18, $this -> y);
			
			/* Add totals */
			$this -> y = $this -> y + 2;
			$this -> insertTotals($page, $invoice);
            
			$page = end($this->_getPdf() -> pages);
			
			//if due date needs ouputting
			if ($this->params['dueDateShift'] > 0)
			{
				//if not enough space - add new page
				if ($this -> y < 46)
					$page = $this -> newPage();
				
				$page = end($this->_getPdf() -> pages);
				
				$this -> y -= 21;
				
				Mage::helper('studio4_invoiceconfig/common')->drawIconOnPage($page, 354, $this->y, 'calendar');
				
				$page->setFillColor(new Zend_Pdf_Color_Html('#'.$this->params['lightTextColor']));
				$this->_setFontLight($page, 9);
				$dueDate = Mage::helper('core')->formatDate($invoice->getCreatedAtStoreDate()->add($this -> params['dueDateShift'], Zend_Date::DAY), 'medium', false);
				$page->drawText(Mage::helper('sales') -> __('Due date:') . ' '. $dueDate, 370, $this -> y, 'UTF-8');
				$this -> y -= 12;
			}
			
			//output comments
			if ($this->params['showComments'] == 1)
			{
				$comments = $invoice -> getCommentsCollection() -> getItems();
				if (!empty($comments)) {
					
					$commentLines = array();
					
					foreach ($comments as $comment) {
						if ($comment -> getData('is_visible_on_front') == 1) {
							
							$font = $this->_setFontLight($page, 9);
							$commentLines = array_merge($commentLines, $commonHelper->sliceStringByPoints(trim($comment->getData('comment')), $page->getWidth() - 28*2, $font, 9));
						}
					}
					
					//if we have something to ouput
					if (count($commentLines) > 0)
					{
						
						if ($this->y < 36)
							$page = $this -> newPage();
						
						$this->y -= 12;
						
						foreach ($commentLines as $line)
						{
							if ($this->y < 24)
							{
								$page = $this -> newPage();
								$this->y -= 12;
							}
						
							$this->y -= 12;
							$page->setFillColor(new Zend_Pdf_Color_Html('#'.$this->params['lightTextColor']));
							$this->_setFontLight($page, 9);
							$page -> drawText(trim(strip_tags($line)), 28, $this -> y, 'UTF-8');	
						}
						
						$this -> y -= 12;
					}
				}
			}

			$font = $this->_setFontLight($page, 9);

			//prepare and ouput footer
			$textSuffixLines = array();
			if ($this->params['textSuffix'] != '')
				$textSuffixLines = $commonHelper->sliceStringByPoints(trim($this->params['textSuffix']), $this->params['signature'] != ''?315:539, $font, 9);
				//$textSuffixLines = Mage::helper('core/string')->str_split($this->params['textSuffix'], $this->params['signature'] != ''?80:130);
			
			$signatureNameLines = array();
			if ($this->params['signitureName'] != '')
				$signatureNameLines = $commonHelper->sliceStringByPoints(trim($this->params['signitureName']), $this->params['signature'] != ''?315:539, $font, 9);
				//$signatureNameLines = Mage::helper('core/string')->str_split($this->params['signitureName'], $this->params['signature'] != ''?80:130);
			
			$paymentInfoLines = array();
			if ($this->params['paymentInformation'] != '')
			{
				foreach (explode("\n", $this -> params['paymentInformation']) as $value) {
					if ($value !== '') {
						$value = preg_replace('/<br[^>]*>/i', " ", $value);
						$paymentInfoLines = array_merge($paymentInfoLines, $commonHelper->sliceStringByPoints(trim($value), $page->getWidth() - 28*2, $font, 9));
						/*foreach (Mage::helper('core/string')->str_split($value, 130, true, true) as $_value) {
							$paymentInfoLines[] = trim(strip_tags($_value));
						}*/
					}
				}
			}
			
			$signTextHeight = (count($textSuffixLines) + count($signatureNameLines)) * 12;
			
			$signatureHeight = $signTextHeight;
			
			if ($this->params['signature'] != '' && $signatureHeight < 60)
				$signatureHeight = 60;
			
			$footerHeight = 13 + $signatureHeight + (count($paymentInfoLines) > 0?5:0) +  12 * count($paymentInfoLines);
			
			//if the footer won't fit - add new page
			if ($this->y < $footerHeight + 12)
			{
				$page = $this -> newPage();
				$this->y -= 12;
			}
			else
			{
				//if same page - ouput footer at the bottom
				$this->y = $footerHeight + 12;
			}
			
			/* Separator */

			$page -> setLineColor(new Zend_Pdf_Color_Html('#' . $this -> params['lightTextColor']));
			$page -> setLineWidth(1);
			$page -> setLineDashingPattern(array(2, 2));
			$page -> drawLine(18, $this->y, $page->getWidth() - 18, $this->y);
			$page -> setLineDashingPattern(Zend_Pdf_Page::LINE_DASHING_SOLID);
			
			$this->y -= 7;
			
			//text suffix and name
			$signTextOffset = floor(($signatureHeight - $signTextHeight) / 2);
			
			$page->setFillColor(new Zend_Pdf_Color_Html('#'.$this->params['textColor']));
			$this->_setFontLight($page, 9);
			Mage::helper('studio4_invoiceconfig/common')->drawIconOnPage($page, 16, $this -> y -12 , 'address');
			foreach ($textSuffixLines as $key => $line)
				$page -> drawText(trim(strip_tags($line)), 28, $this -> y - $signTextOffset - 12 * ($key + 1), 'UTF-8');
			
			Mage::helper('studio4_invoiceconfig/common')->drawIconOnPage($page, 300, $this -> y -12 , 'phone');
			
			$companyPhone = $commonHelper->sliceStringByPoints(trim($this->params['companyPhone']),315, $font, 9);
			
			foreach ($companyPhone as $key => $line)
				$page -> drawText(trim(strip_tags($line)), 318, $this -> y - 12, 'UTF-8');
				
			
			Mage::helper('studio4_invoiceconfig/common')->drawIconOnPage($page, 450, $this -> y -12 , 'email');
			
			$companyEmail = $commonHelper->sliceStringByPoints(trim($this->params['companyEmail']),465, $font, 9);
			
			foreach ($companyEmail as $key => $line)
				$page -> drawText(trim(strip_tags($line)), 468, $this -> y - 12, 'UTF-8');
			
			
			$page->setFillColor(new Zend_Pdf_Color_Html('#'.$this->params['textColor']));
			foreach ($signatureNameLines as $key => $line)
				$page -> drawText(trim(strip_tags($line)), 28, $this -> y - $signTextOffset - 12 * (count($textSuffixLines) + $key + 1), 'UTF-8');
			
			//signature image
			$this->insertSigniture($page, $invoice -> getStore(), $signatureHeight);
			
			$this->y -= $signatureHeight;
			
			$page->setFillColor(new Zend_Pdf_Color_Html('#'.$this->params['lightTextColor']));
			$this->_setFontLight($page, 9);
			foreach ($paymentInfoLines as $key => $line)
				$page -> drawText(trim(strip_tags($line)), 28, $this -> y - 5 - 12 * ($key + 1), 'UTF-8');
			
		}
		$this -> _afterGetPdf();
        
        if ($invoice -> getStoreId()) {
            Mage::app() -> getLocale() -> revert();
        }
        
		return $pdf;
	}

	/**
	 * Create new page and assign to PDF object
	 *
	 * @param  array $settings
	 * @return Zend_Pdf_Page
	 */
	public function newPage(array $settings = array()) {
		$page = $this -> _getPdf() -> newPage(Zend_Pdf_Page::SIZE_A4);
		$this -> _getPdf() -> pages[] = $page;
		
		//"move cursor" to top of the page
		$this -> y = $page->getHeight();

		//insert background image
		$this -> insertBackgroundImage($page);
		
		//output table header if needed
		if (!empty($settings['table_header']))
		{
			$this->y -= 12;
			$this -> _drawHeader($page);
		}

		return $page;
	}
}

?>
