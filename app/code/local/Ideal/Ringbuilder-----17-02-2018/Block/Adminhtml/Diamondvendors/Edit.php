<?php

class Ideal_Ringbuilder_Block_Adminhtml_Diamondvendors_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'ringbuilder';
        $this->_controller = 'adminhtml_diamondvendors';
        
        $this->_updateButton('save', 'label', Mage::helper('ringbuilder')->__('Save Vendor'));
        $this->_updateButton('delete', 'label', Mage::helper('ringbuilder')->__('Delete Vendor'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('diamondvendors_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'diamondvendors_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'diamondvendors_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('diamondvendors_data') && Mage::registry('diamondvendors_data')->getId() ) {
            return Mage::helper('ringbuilder')->__("Edit Vendor '%s'", $this->htmlEscape(Mage::registry('diamondvendors_data')->getName()));
        } else {
            return Mage::helper('ringbuilder')->__('Add Vendor');
        }
    }
}