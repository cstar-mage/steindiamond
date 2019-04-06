<?php
class Ideal_Replacesku_Block_Adminhtml_Replacesku extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_replacesku';
    $this->_blockGroup = 'replacesku';
    $this->_headerText = Mage::helper('replacesku')->__('Upload Product Manager');
    $this->_addButtonLabel = Mage::helper('replacesku')->__('Upload Product');
    parent::__construct();
  }
}