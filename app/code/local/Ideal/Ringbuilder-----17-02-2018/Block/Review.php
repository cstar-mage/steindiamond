<?php 
class Ideal_Ringbuilder_Block_Review extends Mage_Core_Block_Template {
	
	public function _toHtml()
	{
		$this->setTemplate('ringbuilder/review.phtml');
	
		return parent::_toHtml();
	}
	
	public function _prepareLayout()  {
		return parent::_prepareLayout();
    }
    
}