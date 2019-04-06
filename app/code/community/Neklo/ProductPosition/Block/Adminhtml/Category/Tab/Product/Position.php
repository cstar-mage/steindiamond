<?php
/*
NOTICE OF LICENSE

This source file is subject to the NekloEULA that is bundled with this package in the file ICENSE.txt.

It is also available through the world-wide-web at this URL: http://store.neklo.com/LICENSE.txt

Copyright (c)  Neklo (http://store.neklo.com/)
*/


class Neklo_ProductPosition_Block_Adminhtml_Category_Tab_Product_Position extends Mage_Adminhtml_Block_Template
{
    /**
     * @return bool
     */
    public function canShow()
    {
        return $this->getConfig()->isEnabled();
    }

    /**
     * @return string
     */
    public function getCollectionJson()
    {
        return Mage::helper('neklo_productposition/product')->getCollectionJson();
    }

    /**
     * @return string
     */
    public function getSortedProductsPositionJson()
    {
        return Mage::helper('neklo_productposition/product')->getSortedProductsPositionJson();
    }

    /**
     * @return string
     */
    public function getAttachedProductsJson()
    {
        return Mage::helper('neklo_productposition/product')->getAttachedProductsJson();
    }

    /**
     * @return string
     */
    public function getCollectionSize()
    {
        return Mage::helper('neklo_productposition/product')->getCollection()->getSize();
    }

    /**
     * @return string
     */
    public function getNextPageUrl()
    {
        $params = array();
        if ($this->getCategory() && $this->getCategory()->getId()) {
            $params['id'] = $this->getCategory()->getId();
        }
        return $this->getUrl('adminhtml/neklo_productposition_ajax/page', $params);
    }

    /**
     * @return Mage_Catalog_Model_Category
     */
    public function getCategory()
    {
        return Mage::registry('category');
    }

    /**
     * @return Neklo_ProductPosition_Helper_Config
     */
    public function getConfig()
    {
        return Mage::helper('neklo_productposition/config');
    }
}