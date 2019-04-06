<?php

class Ideal_Replacesku_Block_Adminhtml_Replacesku_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('replacesku_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('replacesku')->__('Update Product SKU Manager'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('replacesku')->__('ProductSKU'),
          'title'     => Mage::helper('replacesku')->__('ProductSKU'),
          'content'   => $this->getLayout()->createBlock('replacesku/adminhtml_replacesku_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}