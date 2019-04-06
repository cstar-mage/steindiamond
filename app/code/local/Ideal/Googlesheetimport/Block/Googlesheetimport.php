<?php
class Ideal_Googlesheetimport_Block_Googlesheetimport extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getGooglesheetimport()     
     { 
        if (!$this->hasData('googlesheetimport')) {
            $this->setData('googlesheetimport', Mage::registry('googlesheetimport'));
        }
        return $this->getData('googlesheetimport');
        
    }
}