<?php

/**
 * MageWorx
 * Search Suite
 *
 * @category   MageWorx
 * @package    MageWorx_SearchSuite
 * @copyright  Copyright (c) 2015 MageWorx (http://www.mageworx.com/)
 */
include_once ('Mage/CatalogSearch/controllers/ResultController.php');

class MageWorx_SearchSuite_ResultController extends Mage_CatalogSearch_ResultController {

    public function indexAction() {
        $helper = Mage::helper('mageworx_searchsuite');
        $attr = null;
        $cat = null;
        if ($helper->isSearchByAttributes() && $helper->getSearchParameter()) {
            $attr = $helper->getSearchParameter();
        }
        if ($helper->isSearchByCategories() && $helper->getSearchCategory()) {
            $cat = $helper->getSearchCategory();
        }
        if (!$attr && !$cat) {
            parent::indexAction();
        } else {
            $query = Mage::helper('catalogsearch')->getQuery();
            $query->setStoreId(Mage::app()->getStore()->getId());

            if ($query->getRedirect()){
                $query->save();
                $this->getResponse()->setRedirect($query->getRedirect());
                return;
            }

            if ($query->getQueryText() != '') {
                if (Mage::helper('catalogsearch')->isMinQueryLength()) {

                    $query->setId(0)
                        ->setIsActive(1)
                        ->setIsProcessed(1);

                } else {
                    $key = '_singleton/catalogsearch/layer';
                    Mage::register($key, Mage::getModel('mageworx_searchsuite/layer'), true);

                    if ($query->getId()) {
                        $query->setPopularity($query->getPopularity()+1);
                    }
                    else {
                        $query->setPopularity(1);
                    }

                    if ($query->getRedirect()){
                        $query->save();
                        $this->getResponse()->setRedirect($query->getRedirect());
                        return;
                    }
                    else {
                        $query->prepare();
                    }
                }
                $this->loadLayout();
                $this->_initLayoutMessages('catalog/session');
                $this->_initLayoutMessages('checkout/session');
                $this->renderLayout();
            } else {
                $this->_redirectReferer();
            }
        }
    }

}
