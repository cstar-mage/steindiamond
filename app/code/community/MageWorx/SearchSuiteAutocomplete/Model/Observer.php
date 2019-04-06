<?php

/**
 * MageWorx
 * Search Suite
 *
 * @category   MageWorx
 * @package    MageWorx_SearchSuiteAutocomplete
 * @copyright  Copyright (c) 2015 MageWorx (http://www.mageworx.com/)
 */
class MageWorx_SearchSuiteAutocomplete_Model_Observer {

    public function controllerActionLayoutRenderBefore($observer) {
        $helper = Mage::helper('mageworx_searchsuiteautocomplete');
        if ($helper->showPopup()) {
            $block = Mage::app()->getLayout()->getBlock('head');
            $animation = $helper->getAnimation();
            if ($block) {
                $block->addItem('skin_js', 'js/mageworx/searchsuiteautocomplete/searchsuiteautocomplete.js');
            }
            if ($animation == 'nprogress') {
                if ($block) {
                    $block->addItem('skin_js', 'js/mageworx/searchsuiteautocomplete/NProgress/nprogress.js');
                    $block->addCss('css/mageworx/searchsuiteautocomplete/NProgress/nprogress.css');
                }
            }
        }
    }
}
