<?php

class Unirgy_Storelocationreport_Block_Adminhtml_Storelocationreport_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('storelocationreport_form', array('legend'=>Mage::helper('storelocationreport')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('storelocationreport')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('storelocationreport')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('storelocationreport')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('storelocationreport')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('storelocationreport')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('storelocationreport')->__('Content'),
          'title'     => Mage::helper('storelocationreport')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getStorelocationreportData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getStorelocationreportData());
          Mage::getSingleton('adminhtml/session')->setStorelocationreportData(null);
      } elseif ( Mage::registry('storelocationreport_data') ) {
          $form->setValues(Mage::registry('storelocationreport_data')->getData());
      }
      return parent::_prepareForm();
  }
}