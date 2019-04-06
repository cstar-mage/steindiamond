<?php
/**
 * Sales Order Invoice PDF model
 *
 * @category   Studio4
 * @package    Studio4_InvoiceConfig
 * @author     Studio4 <info@studio4.lt>
 */
 
class Studio4_InvoiceConfig_Model_Order_Pdf_Items_Creditmemo_Default extends Studio4_InvoiceConfig_Model_Order_Pdf_Items_Abstract
{
    /**
     * Draw process
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
				$page -> setFillColor(new Zend_Pdf_Color_Html('#' . $pdf -> params['lightBg']));
				$page -> drawRectangle(18, $pdf -> y, $page->getWidth() - 18, $pdf -> y - $blockHeight, $fillType = Zend_Pdf_Page::SHAPE_DRAW_FILL);
			}
		}
		
		$topOffset = 10;
		
		//slice product name into chunks
		//$nameParts = Mage::helper('core/string')->str_split($item->getName(), 32, true, true);
		$font = $pdf->_setFontLight($page, 9);
		$nameParts = $commonHelper->sliceStringByPoints($item->getName(), 156, $font, 9);
		
		if (!$returnHeight)
		{
			$page -> setFillColor(new Zend_Pdf_Color_Html('#' . $pdf -> params['textColor']));
		}
		
		$titleOffset = 0;
		
		//output product name
		foreach ($nameParts as $key => $nPart)
		{
			$titleOffset = $topOffset + 9 + (13 * $key);
			$y = $pdf->y - $titleOffset;
			
			if (!$returnHeight)
				$page->drawText($nPart, 28, $y, 'UTF-8');
		}
		
		if ($titleOffset > $mostOffset)
			$mostOffset = $titleOffset;
		
		
		if (!$returnHeight)
        	$this->setPage($page);
		
		//check if any options are available and prepare them for output
		$options = $this->getItemOptions();
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
				
				if ($w <= 156 && !is_null($lastLine))
					$optionLines[$lastLine] = $joinedLine;
				else
				{
					$optionLines = array_merge($optionLines, $commonHelper->sliceStringByPoints($toOutput, 156, $font, 7));
					/*if (strlen($toOutput) > 40)
					{
						$slices = Mage::helper('core/string')->str_split($toOutput, 40);
						foreach ($slices as $slice)
							$optionLines[] = $slice;
					}
					else
						$optionLines[] = $toOutput;*/
				}
            }
        }
		
		if (!$returnHeight)
			$font = $pdf->_setFontLight($page, 7);
		
		$optionsOffset = 0;
		
		//ouput options
		foreach ($optionLines as $key => $line)
		{
			$optionsOffset = $titleOffset + 10 + (10 * $key);
			$y = $pdf->y - $optionsOffset;
			
			if (!$returnHeight)
				$page->drawText($line, 28, $y, 'UTF-8');
		}
		
		if ($optionsOffset > $mostOffset)
			$mostOffset = $optionsOffset;
		
		$font = $pdf->_setFontLight($page, 9);
		
		//slice SKU
		//$skuParts = Mage::helper('core/string')->str_split($this->getSku($item), 21);
		$skuParts = $commonHelper->sliceStringByPoints($this->getSku($item), 100, $font, 9);
		$skuOffset = 0;
		
		//ouput SKU slices
		foreach ($skuParts as $key => $sPart)
		{
			$skuOffset = $topOffset + 9 + (13 * $key);
			$y = $pdf->y - $skuOffset;
			
			if (!$returnHeight)
				$page->drawText($sPart, 194, $y, 'UTF-8');
		}
		
		if ($skuOffset > $mostOffset)
			$mostOffset = $skuOffset;
		
		//if only height is needed - return it
		if ($returnHeight)
			return $mostOffset + 12;
		
		$font = $pdf->_setFontBold($page, 9);
		$page->drawText($order->formatPriceTxt($item->getRowTotal()), 304, $pdf->y - $topOffset - 9, 'UTF-8');
		$page->drawText($order->formatPriceTxt($item->getDiscountAmount()), 360, $pdf->y - $topOffset - 9, 'UTF-8');
		
		$font = $pdf->_setFontRegular($page, 9);
		$page->drawText($item->getQty() * 1, 416, $pdf->y - $topOffset - 9, 'UTF-8');
		
		$font = $pdf->_setFontBold($page, 9);
		$page->drawText($order->formatPriceTxt($item->getTaxAmount()), 469, $pdf->y - $topOffset - 9, 'UTF-8');
		$subtotal = $item->getRowTotal() + $item->getTaxAmount() + $item->getHiddenTaxAmount() - $item->getDiscountAmount();
		$page->drawText($order->formatPriceTxt($subtotal), $pdf->getAlignRight($order->formatPriceTxt($subtotal), $page->getWidth() - 60 - 28, 60, $font, 9, 0), $pdf->y - $topOffset - 9, 'UTF-8');
		
		$pdf->y -= ($mostOffset + 12);
		
		if (!$returnHeight)
        	$this->setPage($page);
    }
}
