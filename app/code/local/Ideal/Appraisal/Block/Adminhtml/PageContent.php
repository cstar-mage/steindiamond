<?php

class Ideal_Appraisal_Block_Adminhtml_PageContent extends Mage_Adminhtml_Block_Widget
{
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('appraisal/page_content.phtml');
    }

    public function getAppraisalButtonHtml()
    {
        return $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'id' => 'appraisal_open_popup_button',
                'label' => $this->__('Appraisal')
            ))->toHtml();
    }

    public function getActionUrl()
    {
        return Mage::getBaseUrl() . 'appraisal';
    }
}