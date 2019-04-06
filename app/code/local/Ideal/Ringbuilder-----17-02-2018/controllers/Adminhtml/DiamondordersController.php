<?php

class Ideal_Ringbuilder_Adminhtml_DiamondordersController extends Mage_Adminhtml_Controller_action
{
	
	protected function _initAction() {
		$this->loadLayout()
		->_setActiveMenu('sales/diamondorders')
		->_addBreadcrumb(Mage::helper('adminhtml')->__('Diamond Orders'), Mage::helper('adminhtml')->__('Diamond Orders'));
	
		return $this;
	}
	
	public function indexAction() {
		$this->_initAction()
		->renderLayout();
	}
}