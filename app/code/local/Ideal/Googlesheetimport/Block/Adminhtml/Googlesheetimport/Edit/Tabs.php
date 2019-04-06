<?php

class Ideal_Googlesheetimport_Block_Adminhtml_Googlesheetimport_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('googlesheetimport_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('googlesheetimport')->__('Google Sheet Import'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('googlesheetimport', array(
          'label'     => Mage::helper('googlesheetimport')->__('Import Products'),
          'title'     => Mage::helper('googlesheetimport')->__('Import Products'),
          'content'   => $this->getLayout()->createBlock('googlesheetimport/adminhtml_googlesheetimport_edit_tab_import')->toHtml(),
      ));
     
      $this->addTab('googlesheetupdate', array(
      		'label'     => Mage::helper('googlesheetimport')->__('Update Products'),
      		'title'     => Mage::helper('googlesheetimport')->__('Update Products'),
      		'content'   => $this->getLayout()->createBlock('googlesheetimport/adminhtml_googlesheetimport_edit_tab_update')->toHtml(),
      ));
      
      return parent::_beforeToHtml();
  }
}