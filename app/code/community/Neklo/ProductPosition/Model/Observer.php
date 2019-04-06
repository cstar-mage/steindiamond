<?php
/*
NOTICE OF LICENSE

This source file is subject to the NekloEULA that is bundled with this package in the file ICENSE.txt.

It is also available through the world-wide-web at this URL: http://store.neklo.com/LICENSE.txt

Copyright (c)  Neklo (http://store.neklo.com/)
*/


class Neklo_ProductPosition_Model_Observer
{
    public function categoryPrepareSave(Varien_Event_Observer $observer)
    {
        $category = $observer->getCategory();
        $postedProducts = Mage::helper('neklo_productposition/string')->parseQueryStr($observer->getRequest()->getParam('category_products', null));
        $attachedProducts = Mage::helper('neklo_productposition/string')->parseQueryStr($observer->getRequest()->getParam('attached_category_products', null));
        try {
            Mage::getResourceSingleton('neklo_productposition/product_status')->updateProductPositions($category->getId(), $attachedProducts, $postedProducts);
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    public function addTab(Varien_Event_Observer $observer)
    {
        $tabContainer = $observer->getTabs();
        $tabContainer->addTab(
            'neklo_productposition',
            array(
                'label'   => Mage::helper('neklo_productposition')->__('Product Position'),
                'content' => $this->getTabContent(),
            )
        );
    }

    /**
     * @return string
     */
    public function getTabContent()
    {
        $tab = Mage::app()->getLayout()->createBlock('neklo_productposition_adminhtml/category_tab_product_position', 'neklo_productposition.tab');
        $tab->setTemplate('neklo/productposition/category/tab/product/position.phtml');
        return $tab->toHtml();
    }
}