<?php
class Ideal_Diamondsearch_Block_Diamondsearch extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getDiamondsearch()     
     { 
        if (!$this->hasData('diamondsearch')) {
            $this->setData('diamondsearch', Mage::registry('diamondsearch'));
        }
        return $this->getData('diamondsearch');
        
    }
}