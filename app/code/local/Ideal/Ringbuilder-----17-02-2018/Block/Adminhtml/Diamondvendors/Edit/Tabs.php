<?php

class Ideal_Ringbuilder_Block_Adminhtml_Diamondvendors_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('restconnect_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('ringbuilder')->__('Vendor Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('ringbuilder')->__('Vendor Information'),
          'title'     => Mage::helper('ringbuilder')->__('Vendor Information'),
          'content'   => $this->getLayout()->createBlock('ringbuilder/adminhtml_diamondvendors_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}