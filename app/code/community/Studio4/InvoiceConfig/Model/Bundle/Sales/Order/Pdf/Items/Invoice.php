<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Bundle
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Sales Order Invoice Pdf default items renderer
 *
 * @category   Mage
 * @package    Mage_Sales
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Studio4_InvoiceConfig_Model_Bundle_Sales_Order_Pdf_Items_Invoice extends Mage_Bundle_Model_Sales_Order_Pdf_Items_Abstract
{
    /**
     * Draw item line
     *
     */
    public function draw()
    {
    	$commonHelper = Mage::helper('studio4_invoiceconfig/common');
		
        $order  = $this->getOrder();
        $item   = $this->getItem();
        $pdf    = $this->getPdf();
        $page   = $this->getPage();

        $this->_setFontRegular();
        $items = $this->getChilds($item);
		
		$itemLines = array();
		$prevOptId = '';
		
		//loop through items
		foreach ($items as $_item) {
			$itemDetails = array();
			
			$attributes = $this->getSelectionAttributes($_item);
			
			//if the item has a parent let's see if it's the first one with this label
			if ($_item->getOrderItem()->getParentItem()) {
                if ($prevOptId != $attributes['option_id']) {
					$itemLines[] = array(
						'type' => 'optionLabel',
						'name' => $attributes['option_label']
					);
					
					//don't include same label again
                    $prevOptId = $attributes['option_id'];
                }
				
				$itemDetails['type'] = 'item';
				$itemDetails['name'] = $this->getValueHtml($_item);
            }
			else
			{
				$itemDetails['type'] = 'parent';
				$itemDetails['name'] = $_item->getName();
				$itemDetails['sku'] = $item->getSku();
			}
			
			//let's collect prices if needed
			if ($this->canShowPriceInfo($_item)) {
                $itemDetails['price'] = $order->formatPriceTxt($_item->getPrice());
				$itemDetails['qty'] = $_item->getQty()*1;
				$itemDetails['tax'] = $order->formatPriceTxt($_item->getTaxAmount());
                $itemDetails['rowTotal'] = $order->formatPriceTxt($_item->getRowTotal());
            }
			
			//add collected data to array
			$itemLines[] = $itemDetails;
		}

		// custom options
        $options = $item->getOrderItem()->getProductOptions();
		$optionLines = array();
        if ($options) {
        	
        	$font = $pdf->_setFontLight($page, 7);
			
            if (isset($options['options'])) {
                foreach ($options['options'] as $option) {
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
					
					if ($w <= 191 && !is_null($lastLine))
						$optionLines[$lastLine] = $joinedLine;
					else
					{
						$optionLines = array_merge($optionLines, $commonHelper->sliceStringByPoints($toOutput, 191, $font, 7));
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
        }

		//loop through every line and output it
		foreach ($itemLines as $key => $line)
		{
			$blockHeight = 0;
			switch ($line['type'])
			{
				case 'parent':
					$blockHeight = $this->drawParent($line, true);
					break;
				case 'optionLabel':
					$blockHeight = $this->drawOptionLabel($line, true);
					break;
				case 'item':
					$blockHeight = $this->drawItem($line, true);
					break;
			}
			
            $pageJustAdded = false;
            
			//if the row won't fit to page - add a new one
			if ($pdf->y - $blockHeight < (12 + 12))
            {
                $pageJustAdded = true;
				$page = $pdf->newPage(array('table_header' => true));
                $this->setPage($page);
            }
			
			//draw a background if needed
			if ($pdf->currentItemWithBg)
			{
				$page -> setFillColor(new Zend_Pdf_Color_Html('#' . $pdf -> params['lightBg']));
				$page -> drawRectangle(18, $pdf -> y, $page->getWidth() - 18, $pdf -> y - $blockHeight - ($key == 0 || $pageJustAdded?10:0) - ($key == count($itemLines) - 1 && count($optionLines) == 0?2:0), $fillType = Zend_Pdf_Page::SHAPE_DRAW_FILL);
			}
			
			if ($key == 0 || $pageJustAdded)
				$pdf->y -= 10;
			
			switch ($line['type'])
			{
				case 'parent':
					$this->drawParent($line);
					break;
				case 'optionLabel':
					$this->drawOptionLabel($line);
					break;
				case 'item':
					$this->drawItem($line);
					break;
			}
			
			$pdf->y -= $blockHeight;
			
			if ($key == count($itemLines) - 1 && count($optionLines) == 0)
				$pdf->y -= 2;
		}

		$font = $pdf->_setFontLight($page, 7);
		
		//ouput options
		if (count($optionLines) > 0)
		{
			if ($pdf->currentItemWithBg)
			{
				$page -> setFillColor(new Zend_Pdf_Color_Html('#' . $pdf -> params['lightBg']));
				$page -> drawRectangle(18, $pdf -> y, $page->getWidth() - 18, $pdf -> y - (10 + 10 * count($optionLines)), $fillType = Zend_Pdf_Page::SHAPE_DRAW_FILL);
				$page -> setFillColor(new Zend_Pdf_Color_Html('#' . $pdf -> params['textColor']));
			}
			
			foreach ($optionLines as $key => $line)
			{
				$optionsOffset = 10 + (10 * $key);
				$y = $pdf->y - $optionsOffset;
				$page->drawText($line, 28, $y, 'UTF-8');
			}

			$pdf->y = $y - 12;
		}

        $this->setPage($page);
    }

	public function drawParent($details, $returnHeight = false)
	{
		$commonHelper = Mage::helper('studio4_invoiceconfig/common');
		$mostOffset = 0;
		
		$page   = $this->getPage();
		$pdf    = $this->getPdf();
		
		$font = $pdf->_setFontRegular($page, 9);
		
		//slice product name into chunks
		//$nameParts = Mage::helper('core/string')->str_split($details['name'], 35, true, true);
		$nameParts = $commonHelper->sliceStringByPoints($details['name'], 191, $font, 9);
		
		if (!$returnHeight)
		{
			$page->setFillColor(new Zend_Pdf_Color_Html('#' . $pdf -> params['textColor']));
		}
		
		//output product name
		foreach ($nameParts as $key => $nPart)
		{
			$titleOffset = 9 + (13 * $key);
			$y = $pdf->y - $titleOffset;
			
			if (!$returnHeight)
				$page->drawText($nPart, 28, $y, 'UTF-8');
		}
		
		if ($titleOffset > $mostOffset)
			$mostOffset = $titleOffset;
		
		//slice SKU
		//$skuParts = Mage::helper('core/string')->str_split($details['sku'], 23);
		$skuParts = $commonHelper->sliceStringByPoints($details['sku'], 115, $font, 9);
		$skuOffset = 0;
		
		//ouput SKU slices
		foreach ($skuParts as $key => $sPart)
		{
			$skuOffset = 9 + (13 * $key);
			$y = $pdf->y - $skuOffset;
			
			if (!$returnHeight)
				$page->drawText($sPart, 229, $y, 'UTF-8');
		}
		
		if ($skuOffset > $mostOffset)
			$mostOffset = $skuOffset;
		
		if (!$returnHeight)
		{
			$page->setFillColor(new Zend_Pdf_Color_Html('#' . $pdf -> params['textColor']));
			$font = $pdf->_setFontRegular($page, 9);
		}
		
		$priceOffset = 9;
		$y = $pdf->y - $priceOffset;
		
		if (!$returnHeight && !empty($details['price']))
		{
			$font = $pdf->_setFontBold($page, 9);
			$page->drawText($details['price'], 354, $y, 'UTF-8');
		}
		
		if (!$returnHeight && !empty($details['qty']))
		{
			$font = $pdf->_setFontRegular($page, 9);
			$page->drawText($details['qty'], 416, $y, 'UTF-8');
		}
		
		if (!$returnHeight && !empty($details['tax']))
		{
			$font = $pdf->_setFontBold($page, 9);
			$page->drawText($details['tax'], 469, $y, 'UTF-8');
		}
		
		if (!$returnHeight && !empty($details['price']))
		{
			$font = $pdf->_setFontBold($page, 9);
			$page->drawText($details['rowTotal'], $pdf->getAlignRight($details['rowTotal'], $page->getWidth() - 60 - 28, 60, $font, 9, 0), $y, 'UTF-8');
		}
		
		$mostOffset += 10;
		
		if ($returnHeight)
			return $mostOffset;
	}
	
	public function drawOptionLabel($details, $returnHeight = false)
	{
		$commonHelper = Mage::helper('studio4_invoiceconfig/common');
		$mostOffset = 0;
		
		$page   = $this->getPage();
		$pdf    = $this->getPdf();
		
		$font = $pdf->_setFontRegular($page, 9);
		
		//slice product name into chunks
		//$nameParts = Mage::helper('core/string')->str_split('• ' . $details['name'], 34, true, true);
		$nameParts = $commonHelper->sliceStringByPoints('• ' . $details['name'], 189, $font, 9);
		
		if (!$returnHeight)
		{
			$page->setFillColor(new Zend_Pdf_Color_Html('#' . $pdf -> params['textColor']));
		}
		
		//output product name
		foreach ($nameParts as $key => $nPart)
		{
			$titleOffset = 9 + (13 * $key);
			$y = $pdf->y - $titleOffset;
			
			if (!$returnHeight)
				$page->drawText($nPart, 30, $y, 'UTF-8');
		}
		
		if ($titleOffset > $mostOffset)
			$mostOffset = $titleOffset;
		
		$mostOffset += 10;
		
		if ($returnHeight)
			return $mostOffset;
	}
	
	public function drawItem($details, $returnHeight = false)
	{
		$commonHelper = Mage::helper('studio4_invoiceconfig/common');
		$mostOffset = 0;
		
		$page   = $this->getPage();
		$pdf    = $this->getPdf();
		
		$font = $pdf->_setFontLight($page, 8);
		
		//slice product name into chunks
		//$nameParts = Mage::helper('core/string')->str_split($details['name'], 32, true, true);
		$nameParts = $commonHelper->sliceStringByPoints($details['name'], 176, $font, 8);
		
		if (!$returnHeight)
		{
			$page->setFillColor(new Zend_Pdf_Color_Html('#' . $pdf -> params['textColor']));
		}
		
		//output product name
		foreach ($nameParts as $key => $nPart)
		{
			$titleOffset = 8 + (12 * $key);
			$y = $pdf->y - $titleOffset;
			
			if (!$returnHeight)
				$page->drawText($nPart, 43, $y, 'UTF-8');
		}
		
		if ($titleOffset > $mostOffset)
			$mostOffset = $titleOffset;
		
		if (!$returnHeight)
		{
			$page->setFillColor(new Zend_Pdf_Color_Html('#' . $pdf -> params['textColor']));
			$font = $pdf->_setFontRegular($page, 9);
		}
		
		$priceOffset = 9;
		$y = $pdf->y - $priceOffset;
		
		if (!$returnHeight && !empty($details['price']))
		{
			$font = $pdf->_setFontBold($page, 9);
			$page->drawText($details['price'], 354, $y, 'UTF-8');
		}
		
		if (!$returnHeight && !empty($details['qty']))
		{
			$font = $pdf->_setFontRegular($page, 9);
			$page->drawText($details['qty'], 416, $y, 'UTF-8');
		}
		
		if (!$returnHeight && !empty($details['tax']))
		{
			$font = $pdf->_setFontBold($page, 9);
			$page->drawText($details['tax'], 469, $y, 'UTF-8');
		}
		
		if (!$returnHeight && !empty($details['price']))
		{
			$font = $pdf->_setFontBold($page, 9);
			$page->drawText($details['rowTotal'], $pdf->getAlignRight($details['rowTotal'], $page->getWidth() - 60 - 28, 60, $font, 9, 0), $y, 'UTF-8');
		}
		
		$mostOffset += 10;
		
		if ($returnHeight)
			return $mostOffset;
	}
}
