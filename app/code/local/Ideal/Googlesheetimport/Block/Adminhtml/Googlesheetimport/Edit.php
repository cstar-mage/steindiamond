<?php

class Ideal_Googlesheetimport_Block_Adminhtml_Googlesheetimport_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'googlesheetimport';
        $this->_controller = 'adminhtml_googlesheetimport';
        
        //$this->_updateButton('save', 'label', Mage::helper('googlesheetimport')->__('Save Item'));
        //$this->_updateButton('delete', 'label', Mage::helper('googlesheetimport')->__('Delete Item'));
		
        $this->removeButton('back');
        $this->removeButton('save');
        $this->removeButton('delete');
        $this->removeButton('reset');
        
        /* $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('googlesheetimport_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'googlesheetimport_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'googlesheetimport_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        "; */
    }

    public function getHeaderText()
    {
    	return Mage::helper('googlesheetimport')->__('Google Sheet Import');
        
    }
}