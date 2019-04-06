<?php
class Ideal_Googlesheetimport_Block_Adminhtml_Googlesheetimport extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_googlesheetimport';
    $this->_blockGroup = 'googlesheetimport';
    $this->_headerText = Mage::helper('googlesheetimport')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('googlesheetimport')->__('Add Item');
    parent::__construct();
  }
}