<?php

class Unirgy_Storelocationreport_Block_Adminhtml_Storelocationreport_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('storelocationreport_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('storelocationreport')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('storelocationreport')->__('Item Information'),
          'title'     => Mage::helper('storelocationreport')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('storelocationreport/adminhtml_storelocationreport_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}