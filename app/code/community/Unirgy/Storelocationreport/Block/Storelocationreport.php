<?php
class Unirgy_Storelocationreport_Block_Storelocationreport extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getStorelocationreport()     
     { 
        if (!$this->hasData('storelocationreport')) {
            $this->setData('storelocationreport', Mage::registry('storelocationreport'));
        }
        return $this->getData('storelocationreport');
        
    }
}