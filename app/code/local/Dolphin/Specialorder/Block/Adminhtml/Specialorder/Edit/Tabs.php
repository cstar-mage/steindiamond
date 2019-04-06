<?php

class Dolphin_Specialorder_Block_Adminhtml_Specialorder_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('specialorder_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('specialorder')->__('Special Order Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('specialorder')->__('Special Order Information'),
          'title'     => Mage::helper('specialorder')->__('Special Order Information'),
          'content'   => $this->getLayout()->createBlock('specialorder/adminhtml_specialorder_edit_tab_specialorder')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}
