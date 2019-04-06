<?php
/**
 * Sales Order Invoice PDF model
 *
 * @category   Studio4
 * @package    Studio4_InvoiceConfig
 * @author     Studio4 <info@studio4.lt>
 */
 
class Studio4_InvoiceConfig_Model_Order_Pdf_Items_Invoice_Default extends Studio4_InvoiceConfig_Model_Order_Pdf_Items_Abstract
{
	
    /**
     * Draw item line
	 * if $returnHeight is true then only the block height is calculated and returned
     */
    public function draw($returnHeight = false)
    {
    	$commonHelper = Mage::helper('studio4_invoiceconfig/common');
		
    	$mostOffset = 0;
		
        $order  = $this->getOrder();
        $item   = $this->getItem();
        $pdf    = $this->getPdf();
        $page   = $this->getPage();
        //$lines  = array();
        
        ///* Display Image Start */
			$id = Mage::getModel('catalog/product')->getIdBySku($this->getSku($item));
			$product=  Mage::getModel('catalog/product')->load($id);
			$imageUrl = $product->getSmallImageUrl();
			$imageFile=  str_replace(Mage::getBaseUrl('media'),"media/",$imageUrl);
			$imageWidth = 100; $imageHeight = 80;
			$image = Zend_Pdf_Image::imageWithPath($imageFile,$imageWidth,$imageHeight);
			$y= $pdf->y - $imageHeight /1.8;
			$page->drawImage($image, 25,$y, 25+ $imageWidth / 2, $y + $imageHeight/2);       
			
        
        //if not only checking the height - let's check and set up page and background if needed
        if (!$returnHeight)
		{
			$blockHeight = $this->draw(true);
			
			if ($pdf->y - $blockHeight < 12)
            {
				$page = $pdf->newPage(array('table_header' => true));
                $this->setPage($page);
            }
			
			if ($pdf->currentItemWithBg)
			{
				//$page -> setFillColor(new Zend_Pdf_Color_Html('#' . $pdf -> params['lightBg']));
				//$page -> drawRectangle(18, $pdf -> y, $page->getWidth() - 18, $pdf -> y - $blockHeight, $fillType = Zend_Pdf_Page::SHAPE_DRAW_FILL);
			}
		}

		//get price and subtotal values
		$prices = $this->getItemPricesForDisplay();
		
		$topOffset = 10;
		
		//if there are labels leave more space at the top
		if (count($prices) > 0 && !empty($prices[0]['label']))
			$topOffset = 16;
		
		//slice product name into chunks
		$font = $pdf->_setFontLight($page, 9);
		//$nameParts = Mage::helper('core/string')->str_split($item->getName(), 35, true, true);
		//$nameParts = $commonHelper->sliceStringByPoints($item->getName(), 191, $font, 9); //My Comnt
		$nameParts = $commonHelper->sliceStringByPoints($item->getName(), 160, $font, 9);
		
		if (!$returnHeight)
		{
			$page -> setFillColor(new Zend_Pdf_Color_Html('#' . $pdf -> params['textColor']));
		}
		
		$titleOffset = 0;
		
		//output product+ name
		
		foreach ($nameParts as $key => $nPart)
		{
			$titleOffset = $topOffset + 9 + (13 * $key);
			$y = $pdf->y - $titleOffset;
			
			if (!$returnHeight)
				$page->drawText($nPart, 90, $y, 'UTF-8');
		}
		
		if ($titleOffset > $mostOffset)
			$mostOffset = $titleOffset;
		
		//check if any options are available and prepare them for output
		
		/*-----------custom code for customoption ------------------*/ 
		
		
		/* $proid = Mage::getModel('catalog/product')->getIdBySku($this->getSku($item));
		
		$product = Mage::getModel('catalog/product')->load($proid);
		$orderItems = Mage::getModel('sales/order_item')->getCollection()
							->addFieldToFilter('order_id', $order->getId())
							->addFieldToFilter('product_id', $proid); 
		$order = Mage::getModel('sales/order')->load($order->getId());*/ 
		
		$orderItems = $order->getItemsCollection();
			
			//echo '<pre>';
		
			foreach($orderItems as $orderItem){
				$option = $orderItem->getProductOptions();	
				
				foreach(array_slice($option,1) as $options){
				  if($this->getSku($item) == $orderItem->getSku()){		
					if ($options) {
			
							$font = $pdf->_setFontLight($page, 7);
							
							foreach ($options as $option) {
							   $_printValue = '';
							   if ($option['value']) {
									if (isset($option['print_value'])) {
										$_printValue = $option['print_value'];
									} else {
										$_printValue = strip_tags($option['value']);
									}
								}
								
									
							
								
								
							   
								end($optionLines);
																								
								$lastLine = key($optionLines);
								if (is_null($lastLine))
									$lastLineVal = '';
								else
									$lastLineVal = $optionLines[$lastLine];
								
								$toOutput = strip_tags($option['label']).': '.$_printValue;
								
								$joinedLine = $lastLineVal . (!empty($lastLineVal)?', ':'') . $toOutput;
							
								$w = $pdf->widthForStringUsingFontSize($joinedLine, $font, 7);
								
								
							
								if ($w <= 135 && !is_null($lastLine))
									$optionLines[$lastLine] = $joinedLine;
								else
								{
														
									if (strlen($toOutput) > 100) 
									{
										$slices = Mage::helper('core/string')->str_split($toOutput, 100);
										foreach ($slices as $slice)
											$optionLines[] = $slice;
									}
									else
										$optionLines[] = $toOutput; 
								}
					
							}
						
						}
					}
        
				
				}
				
			}
				
								
/*-----------custom code for customoption ------------------*/ 
			
		/*	exit;
		
		echo "<pre>";
		print_r($options1->getData());
		exit; */
		
	
	/*	$options = $this->getItemOptions();  
	 
		$optionLines = array();		
		if ($options) {
			
			$font = $pdf->_setFontLight($page, 7);
			
            foreach ($options as $option) {
               $_printValue = '';
               if ($option['value']) {
                    if (isset($option['print_value'])) {
                        $_printValue = $option['print_value'];
                    } else {
                        $_printValue = strip_tags($option['value']);
                    }
                }
			   
               	end($optionLines);
				$lastLine = key($optionLines);
				if (is_null($lastLine))
					$lastLineVal = '';
				else
					$lastLineVal = $optionLines[$lastLine];
				
                $toOutput = strip_tags($option['label']).': '.$_printValue;
				
				$joinedLine = $lastLineVal . (!empty($lastLineVal)?', ':'') . $toOutput;
				
				$w = $pdf->widthForStringUsingFontSize($joinedLine, $font, 7);
				
				if ($w <= 135 && !is_null($lastLine))
					$optionLines[$lastLine] = $joinedLine;
				else
				{
				//	$optionLines = array_merge($optionLines, $commonHelper->sliceStringByPoints($toOutput, 135, $font, 7));
				
				 	if (strlen($toOutput) > 40)
					{
						$slices = Mage::helper('core/string')->str_split($toOutput, 40);
						foreach ($slices as $slice)
							$optionLines[] = $slice;
					}
					else
						$optionLines[] = $toOutput; 
				}
            }
        } */
		
		if (!$returnHeight)
			$font = $pdf->_setFontLight($page, 8);
		
		$optionsOffset = 0;
		
								
						
		//ouput options
		foreach ($optionLines as $key => $line)
		{
			$optionsOffset = $titleOffset + 20 + (10 * $key);
			$y = $pdf->y - $optionsOffset;
								
			if (!$returnHeight)
			$page->drawText($line, 90, $y, 'UTF-8');
		}
		
		
		
		if ($optionsOffset > $mostOffset)
			$mostOffset = $optionsOffset;
		
		$font = $pdf->_setFontLight($page, 9); 
		
		//slice SKU
		//$skuParts = Mage::helper('core/string')->str_split($this->getSku($item), 23);
		$skuParts = $commonHelper->sliceStringByPoints($this->getSku($item), 70, $font, 9);
		$skuOffset = 0; 
		
		//ouput SKU slices
		
		foreach ($skuParts as $key => $sPart)
		{
			$skuOffset = $topOffset + 9 + (13 * $key);
			$y = $pdf->y - $skuOffset;
			
			if (!$returnHeight)
				$page->drawText($sPart, 265, $y, 'UTF-8');
		}
		
		if ($skuOffset > $mostOffset)
			$mostOffset = $skuOffset;
		
		$priceOffset = 0;
		
		//ouput prices and subtotals
		foreach ($prices as $key => $price)
		{
			$priceOffset = $topOffset + 9 + (21 * $key);
			$y = $pdf->y - $priceOffset;
			
			if (!$returnHeight)
			{
				//if labels are set ouput them above each price
				if (isset($price['label']))
				{
					$font = $pdf->_setFontLight($page, 6);
					$page->drawText($price['label'], 345, $y + 10, 'UTF-8');
					$page->drawText($price['label'], $pdf->getAlignRight($price['label'], $page->getWidth() - 60 - 28, 60, $font, 6, 0), $y + 10, 'UTF-8');
				}
				$font = $pdf->_setFontBold($page, 9);
				$page->drawText($price['price'], 345, $y, 'UTF-8');
				$page->drawText($price['subtotal'], $pdf->getAlignRight($price['subtotal'], $page->getWidth() - 60 - 28, 60, $font, 9, 0), $y, 'UTF-8');
			}
		}
		
		if ($priceOffset > $mostOffset)
			$mostOffset = $priceOffset;
		
		//if only height is needed - return it
		if ($returnHeight)
			return $mostOffset + 12;
		
		$font = $pdf->_setFontRegular($page, 9);
		
		//ouput quantity and tax amount
		$page->drawText($item->getQty() * 1, 420, $pdf->y - $topOffset - 9, 'UTF-8');
		$font = $pdf->_setFontBold($page, 9);
		$page->drawText($order->formatPriceTxt($item->getTaxAmount()), 469, $pdf->y - $topOffset - 9, 'UTF-8');
		
		//keep some whitespace under the info
		$pdf->y -= ($mostOffset + 12);
			
        if (!$returnHeight)
        	$this->setPage($page);
    }
}
