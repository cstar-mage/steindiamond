<?php
/*
NOTICE OF LICENSE

This source file is subject to the NekloEULA that is bundled with this package in the file ICENSE.txt.

It is also available through the world-wide-web at this URL: http://store.neklo.com/LICENSE.txt

Copyright (c)  Neklo (http://store.neklo.com/)
*/


class Neklo_ProductPosition_Adminhtml_Neklo_Productposition_AjaxController extends Mage_Adminhtml_Controller_Action
{
    public function pageAction()
    {
        // TODO: move default to const
        $page = $this->getRequest()->getParam('page', 1);
        $count = $this->getRequest()->getParam('count', 20);
        $collectionJson = Mage::helper('neklo_productposition/product')->getCollectionJson($page, $count);
        $this->getResponse()->setBody($collectionJson);
        $this->getResponse()->clearHeaders()->setHeader('Content-type', 'application/json', true);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/categories');
    }
}