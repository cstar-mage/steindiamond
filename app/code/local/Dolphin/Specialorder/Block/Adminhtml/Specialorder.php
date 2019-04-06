<?php
class Dolphin_Specialorder_Block_Adminhtml_Specialorder extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_specialorder';
    $this->_blockGroup = 'specialorder';
    $this->_headerText = Mage::helper('specialorder')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('specialorder')->__('Add Item');
    parent::__construct();
    $this->_removeButton('add');
  }
}
