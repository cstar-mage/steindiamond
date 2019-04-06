<?php
class Mage_Stud_Block_Stud extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getStud()     
     { 
        if (!$this->hasData('stud')) {
            $this->setData('stud', Mage::registry('stud'));
        }
        return $this->getData('stud');
        
    }
}