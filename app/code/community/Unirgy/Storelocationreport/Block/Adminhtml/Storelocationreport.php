<?php
class Unirgy_Storelocationreport_Block_Adminhtml_Storelocationreport extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_storelocationreport';
    $this->_blockGroup = 'storelocationreport';
    $this->_headerText = Mage::helper('storelocationreport')->__('Store Locator Reports');
    //$this->_addButtonLabel = Mage::helper('storelocationreport')->__('Add Item');
    parent::__construct();
    $this->removeButton('add');
  }
}