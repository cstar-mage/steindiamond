<?php
class Ideal_Replacesku_Block_Replacesku extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getReplacesku()     
     { 
        if (!$this->hasData('replacesku')) {
            $this->setData('replacesku', Mage::registry('replacesku'));
        }
        return $this->getData('replacesku');
        
    }
}