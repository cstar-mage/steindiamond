<?php

class Ideal_Replacesku_Block_Adminhtml_Replacesku_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('replacesku_form', array('legend'=>Mage::helper('replacesku')->__('Replace Product SKU')));
     
     /* $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('replacesku')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));*/

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('replacesku')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
     /* $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('replacesku')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('replacesku')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('replacesku')->__('Disabled'),
              ),
          ),
      ));*/
     
     /* $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('replacesku')->__('Content'),
          'title'     => Mage::helper('replacesku')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));*/
     
      if ( Mage::getSingleton('adminhtml/session')->getReplaceskuData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getReplaceskuData());
          Mage::getSingleton('adminhtml/session')->setReplaceskuData(null);
      } elseif ( Mage::registry('replacesku_data') ) {
          $form->setValues(Mage::registry('replacesku_data')->getData());
      }
      return parent::_prepareForm();
  }
}