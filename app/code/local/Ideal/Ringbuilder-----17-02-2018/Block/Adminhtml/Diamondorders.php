<?php
class Ideal_Ringbuilder_Block_Adminhtml_Diamondorders extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_diamondorders';
    $this->_blockGroup = 'ringbuilder';
    $this->_headerText = Mage::helper('ringbuilder')->__('Diamond Orders');
    //$this->_addButtonLabel = Mage::helper('ringbuilder')->__('Add Vendor');
    
    $this->_removeButton('add');
    
    parent::__construct();
  }
}