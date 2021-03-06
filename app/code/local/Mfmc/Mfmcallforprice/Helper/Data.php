<?php
/**
* @copyright  Copyright (c) 2009 Modules For Magento Inc. 
*/
class Mfmc_mfmcallforprice_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getCallForPriceStr($product) { 
        $callForPrice = Mage::getModel('mfmcallforprice/mfmcallforprice')->load($product->getId(), 'product_id');
        if($callForPrice->getHidePrice())  {
            
            $product->setIsSalable(0);
            $html = '<div class="out-of-stock">' . $callForPrice->getMessage() . '</div>';
            return $html;
            
        }
        else {
            if(!$product->getIsSalable()) {
                $html = '<div class="out-of-stock">' . Mage::helper('mfmcallforprice')->__('&nbsp;') . '</div>';
                return $html;
            }
        }
        return null;
    }
}