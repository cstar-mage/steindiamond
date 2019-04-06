<?php

class Dolphin_Specialorder_Block_Adminhtml_Specialorder_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('specialorder_form', array('legend'=>Mage::helper('specialorder')->__('Special Order Request information')));
     
      $fieldset->addField('name', 'text', array(
          'label'     => Mage::helper('specialorder')->__('Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'name',
      ));
      
      /* $fieldset->addField('lname', 'text', array(
      		'label'     => Mage::helper('specialorder')->__('Last Name'),
      		'class'     => 'required-entry',
      		'required'  => true,
      		'name'      => 'lname',
      )); */

      $fieldset->addField('email', 'text', array(
          'label'     => Mage::helper('specialorder')->__('Email'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'email',
	  ));
      
      /* $fieldset->addField('phone', 'text', array(
      		'label'     => Mage::helper('specialorder')->__('Phone'),
      		'name'      => 'phone',
      )); */
	  $fieldset->addField('producturl', 'text', array(
      		'label'     => Mage::helper('specialorder')->__('Product Url '),
      		'name'      => 'producturl',
      ));
	  $fieldset->addField('producttype', 'text', array(
      		'label'     => Mage::helper('specialorder')->__('Looking for Product'),
      		'name'      => 'producttype',
      ));
	  
	  /* $fieldset->addField('moreimportant', 'text', array(
      		'label'     => Mage::helper('specialorder')->__('More Important'),
      		'name'      => 'moreimportant',
      )); */
	 /*  $fieldset->addField('rings', 'text', array(
	  		'label'     => Mage::helper('specialorder')->__('Iterested Rings'),
	  		'name'      => 'rings',
	  ));
	  
	   $fieldset->addField('stonetype', 'text', array(
      		'label'     => Mage::helper('specialorder')->__('Stone Type'),
      		'name'      => 'stonetype',
      ));
	   
	   $fieldset->addField('metalcolors', 'text', array(
	   		'label'     => Mage::helper('specialorder')->__('Metal Colors'),
	   		'name'      => 'metalcolors',
	   ));
	   */
	  /*  $fieldset->addField('pricerange', 'text', array(
      		'label'     => Mage::helper('specialorder')->__('Price Range'),
      		'name'      => 'pricerange',
      )); 
	   
	  
	   $fieldset->addField('month', 'text', array(
      		'label'     => Mage::helper('specialorder')->__('Finished Month'),
      		'name'      => 'month',
      ));
	  
	   $fieldset->addField('day', 'text', array(
      		'label'     => Mage::helper('specialorder')->__('Finished Date'),
      		'name'      => 'day',
      ));
	  
	  $fieldset->addField('year', 'text', array(
      		'label'     => Mage::helper('specialorder')->__('Finished year'),
      		'name'      => 'year',
      ));
     
	  
	  $fieldset->addField('comment', 'textarea', array(
      		'label'     => Mage::helper('specialorder')->__('Comment'),
      		'name'      => 'comment',
      ));
       */
	  /* $fieldset->addField('image1', 'text',array(
			'label'     => Mage::helper('specialorder')->__('Image1'),
			'name'      => 'image1',
	  ));
	   $fieldset->addField('image2', 'text',array(
			'label'     => Mage::helper('specialorder')->__('Image2'),
			'name'      => 'image2',
	  ));
	   $fieldset->addField('image3', 'text',array(
			'label'     => Mage::helper('specialorder')->__('Image3'),
			'name'      => 'image3',
	  )); */
	   
		
    /*  $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('specialorder')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('specialorder')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('specialorder')->__('Disabled'),
              ),
          ),
      ));*/
     
      if ( Mage::getSingleton('adminhtml/session')->getSpecialorderData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getSpecialorderData());
          Mage::getSingleton('adminhtml/session')->setSpecialorderData(null);
      } elseif ( Mage::registry('specialorder_data') ) {
          $form->setValues(Mage::registry('specialorder_data')->getData());
      }
      return parent::_prepareForm();
  }
}
