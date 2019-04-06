<?php

/**
 * Sales Order Invoice PDF model
 *
 * @category   Studio4
 * @package    Studio4_InvoiceConfig
 * @author     Studio4 <info@studio4.lt>
 */
 
class Studio4_InvoiceConfig_Model_Order_Pdf_Shipment extends Studio4_InvoiceConfig_Model_Order_Pdf_Abstract {
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
		$page->drawText(Mage::helper('sales') -> __('Qty'), 28, $this->y, 'UTF-8');
		$page->drawText(Mage::helper('sales') -> __('Products'), 78, $this->y, 'UTF-8');
		$page->drawText(Mage::helper('sales') -> __('SKU'), 354, $this->y, 'UTF-8');

		$page -> setFillColor(new Zend_Pdf_Color_GrayScale(0));
		$this -> y -= 12;
    }

    /**
     * Return PDF document
     *
     * @param  array $shipments
     * @return Zend_Pdf
     */
    public function getPdf($shipments = array())
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('shipment');

        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);
		
		$commonHelper = Mage::helper('studio4_invoiceconfig/common');
		
        foreach ($shipments as $shipment) {
        	
        	//collect params (settings)
        	
			$storeId = $shipment -> getStoreId();
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
			
            if ($shipment->getStoreId()) {
                Mage::app()->getLocale()->emulate($shipment->getStoreId());
                Mage::app()->setCurrentStore($shipment->getStoreId());
            }
			
			//add pdf page
            $page  = $this->newPage();
            $order = $shipment->getOrder();
			
			/* Add logo */
			$this -> insertLogo($page, $shipment -> getStore());

			/* Add document text and number  -- shipment #*/
			$this -> insertDocNumber(
					$page, 
					mb_strtoupper(Mage::helper('sales')->__('Packingslip # ') . $shipment->getIncrementId(), 'UTF-8'), 
					$shipment, 
					false
				);
			
			/* insert date */
			$font = $this->_setFontLight($page, 8);
	
			if ($this -> params['invoiceDate'] == 1) {
				$shipmentDate =  Mage::helper('core')->formatDate($shipment->getCreatedAtStoreDate(), 'medium', false);
			} else {
				$shipmentDate =  Mage::helper('core')->formatDate($order->getCreatedAtStoreDate(), 'medium', false);
			}
	
			$page->setFillColor(new Zend_Pdf_Color_Html('#'.$this->params['lightTextColor']));
			$page->drawText(
				$shipmentDate, 
				$this->getAlignRight($shipmentDate, 298, 267, $font, 8, 0), 
				$page->getHeight() - 8 - 60 + 8, 
				'UTF-8');
            
            /* Sepperator */
			$page -> setLineColor(new Zend_Pdf_Color_Html('#' . $this -> params['lightTextColor']));
			$page -> setLineWidth(1);
			$page -> setLineDashingPattern(array(2, 2));
			$page -> drawLine(18, $page->getHeight() - 92, $page->getWidth() - 18, $page->getHeight() - 92);
			$page -> setLineDashingPattern(Zend_Pdf_Page::LINE_DASHING_SOLID);
			
			/* Add head -- shipping billing address */
			$this -> insertOrder($page, $shipment, Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, $order -> getStoreId()));

            /* Add table */
            $this->_drawHeader($page);
           
		    /* Add body */
		    
		    //let's count the lines
			$lineCount = 0;
			foreach ($shipment->getAllItems() as $item) {
				if ($item -> getOrderItem() -> getParentItem()) {
					continue;
				}
				$lineCount++;
			}
			
			//set the background on/off for the first item
			$this->currentItemWithBg = ($lineCount % 2) == 0;
		    
            foreach ($shipment->getAllItems() as $item) {
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
        }

        $this->_afterGetPdf();
        if ($shipment->getStoreId()) {
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