<?php

/**
 * Sales Order Invoice PDF model
 *
 * @category   Studio4
 * @package    Studio4_InvoiceConfig
 * @author     Studio4 <info@studio4.lt>
 */
 
class Studio4_InvoiceConfig_Model_Order_Pdf_Creditmemo extends Studio4_InvoiceConfig_Model_Order_Pdf_Abstract {
	 /**
     * Draw table header for product items
     *
     * @param  Zend_Pdf_Page $page
     * @return void
     */
    protected function _drawHeader(Zend_Pdf_Page $page)
    {
       /* Add table head */
		//draw heading background
		$this -> _setFontLight($page, 10);
		$page -> setFillColor(new Zend_Pdf_Color_Html('#' . $this -> params['mainColor']));
		$page -> drawRectangle(18, $this -> y, $page->getWidth() - 18, $this -> y - 30, $fillType = Zend_Pdf_Page::SHAPE_DRAW_FILL);
		$page -> setFillColor(new Zend_Pdf_Color_Html('#' . $this -> params['inverseColor']));
		
		$this -> y -= 18;
			
		$font = $this->_setFontLight($page, 10);
		//ouput column labels
		
		$page->drawText(Mage::helper('sales') -> __('Products'), 28, $this->y, 'UTF-8');
		$page->drawText(Mage::helper('sales') -> __('SKU'), 194, $this->y, 'UTF-8');
		$page->drawText(Mage::helper('sales') -> __('Total (ex)'), 304, $this->y, 'UTF-8');
		$page->drawText(Mage::helper('sales') -> __('Discount'), 360, $this->y, 'UTF-8');
		$page->drawText(Mage::helper('sales') -> __('Qty'), 416, $this->y, 'UTF-8');
		$page->drawText(Mage::helper('sales') -> __('Tax'), 469, $this->y, 'UTF-8');
		$page->drawText(Mage::helper('sales') -> __('Total (inc)'), $this->getAlignRight(Mage::helper('sales') -> __('Total (inc)'), $page->getWidth() - 60 - 28, 60, $font, 10, 0), $this->y, 'UTF-8');

        //$lineBlock = array('lines' => $lines, 'height' => 5);

       	//$this -> drawLineBlocks($page, array($lineBlock), array('table_header' => true));
		$page -> setFillColor(new Zend_Pdf_Color_GrayScale(0));
		$this -> y -= 12;
    }

    /**
     * Return PDF document
     *
     * @param  array $creditmemos
     * @return Zend_Pdf
     */
    public function getPdf($creditmemos = array())
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('creditmemo');
		
        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);
		
		$commonHelper = Mage::helper('studio4_invoiceconfig/common');

        foreach ($creditmemos as $creditmemo) {
        	
			//collect params (settings)
			
			$storeId = $creditmemo -> getStoreId();
			$params = array(
				'mainColor' => $commonHelper->getStoreConfig('invoiceconfig/s4colors/s4_main_color',  $storeId, '000000'), 
				'textColor' => $commonHelper->getStoreConfig('invoiceconfig/s4colors/s4_text_color',  $storeId, '000000'), 
				'lightTextColor' => $commonHelper->getStoreConfig('invoiceconfig/s4colors/s4_lighttext_color',  $storeId, '444444'), 
				'inverseColor' => $commonHelper->getStoreConfig('invoiceconfig/s4colors/s4_inverted_color',  $storeId, 'FFFFFF'), 
				'lightBg' => $commonHelper->getStoreConfig('invoiceconfig/s4colors/s4_lightbg_color',  $storeId, 'CCCCCC'), 
				'logo' => Mage::getStoreConfig('invoiceconfig/s4images/s4_pdf_logo',  $storeId), 
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
			
         	if ($creditmemo->getStoreId()) {
                Mage::app()->getLocale()->emulate($creditmemo->getStoreId());
                Mage::app()->setCurrentStore($creditmemo->getStoreId());
            }
			
			//add pdf page
           	$page  = $this->newPage();
            $order = $creditmemo->getOrder();
			
		    /* Add image */
            $this->insertLogo($page, $creditmemo->getStore());
			
			/* Add document text and number  -- creditmemo #*/
			$this -> insertDocNumber(
					$page, 
					mb_strtoupper(Mage::helper('sales') -> __('Credit Memo # ') . $creditmemo->getIncrementId(), 'UTF-8'), 
					$order, 
					true
				);
			
			/* insert date */
			$font = $this->_setFontLight($page, 8);
			
			if ($this -> params['invoiceDate'] == 1) {
				$creditmemoDate =  Mage::helper('core')->formatDate($creditmemo->getCreatedAtStoreDate(), 'medium', false);
			} else {
				$creditmemoDate =  Mage::helper('core')->formatDate($order->getCreatedAtStoreDate(), 'medium', false);
			}
			
			$page->setFillColor(new Zend_Pdf_Color_Html('#'.$this->params['lightTextColor']));
			$page->drawText(
				$creditmemoDate, 
				$this->getAlignRight($creditmemoDate, 298, 267, $font, 8, 0), 
				$page->getHeight() - 8 - 60, 
				'UTF-8');
			
			/* Sepperator */
			$page -> setLineColor(new Zend_Pdf_Color_Html('#' . $this -> params['lightTextColor']));
			$page -> setLineWidth(1);
			$page -> setLineDashingPattern(array(2, 2));
			$page -> drawLine(18, $page->getHeight() - 92, $page->getWidth() - 18, $page->getHeight() - 92);
			$page -> setLineDashingPattern(Zend_Pdf_Page::LINE_DASHING_SOLID);
			
			/* Add head -- shipping billing address */
			$this -> insertOrder($page, $order, Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, $order -> getStoreId()));
			
            /* Add table head */
            $this->_drawHeader($page);
			
            /* Add body */
            
            //let's count the lines
			$lineCount = 0;
			foreach ($creditmemo->getAllItems() as $item) {
				if ($item -> getOrderItem() -> getParentItem()) {
					continue;
				}
				$lineCount++;
			}
			
			//set the background on/off for the first item
			$this->currentItemWithBg = ($lineCount % 2) == 0;
			
            foreach ($creditmemo->getAllItems() as $item){
                if ($item->getOrderItem()->getParentItem()) {
                    continue;
                }
				/* Draw item */
				$this -> _drawItem($item, $page, $order);
				$page = end($this->_getPdf() -> pages);
				$this->currentItemWithBg = !$this->currentItemWithBg;
            }
			
			//draw a line after last item
			$page -> setLineColor(new Zend_Pdf_Color_Html('#' . $this -> params['lightBg']));
			$page -> setLineWidth(0.5);
			$page -> drawLine(18, $this -> y, $page->getWidth() - 18, $this -> y);
			
            /* Add totals */
            $this -> y = $this -> y + 2;
            $this->insertTotals($page, $creditmemo);
			if ($creditmemo -> getStoreId()) {
				Mage::app() -> getLocale() -> revert();
			}
			$page = end($this->_getPdf() -> pages);
        }

        $this->_afterGetPdf();
		
        if ($creditmemo->getStoreId()) {
            Mage::app()->getLocale()->revert();
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
