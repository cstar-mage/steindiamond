<?php

class Ideal_Ringbuilder_Block_Adminhtml_Diamondvendors_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('diamondvendors_form', array('legend'=>Mage::helper('ringbuilder')->__('Vendor information')));
     
      $fieldset->addField('name', 'text', array(
          'label'     => Mage::helper('ringbuilder')->__('Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'name',
      ));

      $fieldset->addField('contact_person', 'text', array(
      		'label'     => Mage::helper('ringbuilder')->__('Contact Person'),
      		'class'     => 'required-entry',
      		'required'  => true,
      		'name'      => 'contact_person',
      ));
      
      $fieldset->addField('email', 'text', array(
      		'label'     => Mage::helper('ringbuilder')->__('Email'),
      		'class'     => 'required-entry',
      		'required'  => true,
      		'name'      => 'email',
      ));
      
      if ( Mage::getSingleton('adminhtml/session')->getDiamondvendorsData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getDiamondvendorsData());
          Mage::getSingleton('adminhtml/session')->setDiamondvendorsData(null);
      } elseif ( Mage::registry('diamondvendors_data') ) {
          $form->setValues(Mage::registry('diamondvendors_data')->getData());
      }
      return parent::_prepareForm();
  }
}