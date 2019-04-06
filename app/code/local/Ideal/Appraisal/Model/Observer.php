<?php

class Ideal_Appraisal_Model_Observer
{
    public function beforeAdminhtmlSalesOrderViewAction($observer)
    {
        Mage::app()->getLayout()->getBlock('head')->addJs('appraisal/appraisal.js');
        Mage::app()->getLayout()->getBlock('head')->addCss('lib/prototype/windows/themes/magento.css');
        Mage::app()->getLayout()->getBlock('head')->addCss('../../../../js/prototype/windows/themes/default.css');

        $block = Mage::app()->getLayout()->createBlock('IdealAppraisal/Adminhtml_PageContent');
        Mage::app()->getLayout()->getBlock('content')->append($block);
    }
}