<?php
class Ideal_Ringbuilder_Block_Ringbuilder extends Mage_Core_Block_Template
{
	
	public function _toHtml()
	{
		$this->setTemplate('ringbuilder/ringbuilder.phtml');
	
		return parent::_toHtml();
	}
	
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getRingbuilder()     
     { 
        if (!$this->hasData('ringbuilder')) {
            $this->setData('ringbuilder', Mage::registry('ringbuilder'));
        }
        return $this->getData('ringbuilder');
        
    }
}