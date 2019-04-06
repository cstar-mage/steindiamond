<?php
class Ideal_Ringbuilder_Block_Adminhtml_Diamondvendors extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_diamondvendors';
    $this->_blockGroup = 'ringbuilder';
    $this->_headerText = Mage::helper('ringbuilder')->__('Vendor Manager');
    $this->_addButtonLabel = Mage::helper('ringbuilder')->__('Add Vendor');
    parent::__construct();
  }
}