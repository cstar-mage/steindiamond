<?php

class Ideal_Replacesku_Block_Adminhtml_Replacesku_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'replacesku';
        $this->_controller = 'adminhtml_replacesku';
        
        $this->_updateButton('save', 'label', Mage::helper('replacesku')->__('Upload CSV'));
		$this->_removeButton('reset');
		
        $this->_addButton('run_in_popup', array(
           'label'     => Mage::helper('adminhtml')->__('Update SKU'),
            //'onclick'   => 'window.open(\''.$this->getUrl('adminhtml/replacesku/insertinpopup', array('_current'=>true)).'\')',
			'onclick'   => 'setLocation(\''.$this->getUrl('adminhtml/replacesku/insertinpopup').'\')',
            'class'     => 'save', 
        ), -100);
        $this->_removeButton('back');
        //$data = array(
        //		'label' =>  'Back',
        //		'onclick'   => 'setLocation(\'' . $this->getUrl('adminhtml/replacesku/new') . '\')',
        //		'class'     =>  'back'
        //);
        //$this->addButton ('my_back', $data, 0, 100,  'header');
    }

    public function getHeaderText()
    {
        if( Mage::registry('replacesku_data') && Mage::registry('replacesku_data')->getId() ) {
            return Mage::helper('replacesku')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('replacesku_data')->getTitle()));
        } else {
            return Mage::helper('replacesku')->__('Update Product SKU');
        }
    }
}