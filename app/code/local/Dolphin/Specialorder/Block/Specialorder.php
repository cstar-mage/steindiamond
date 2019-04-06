<?php
class Dolphin_Specialorder_Block_Specialorder extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getCustomcontactnew()     
     { 
        if (!$this->hasData('specialorder')) {
            $this->setData('specialorder', Mage::registry('specialorder'));
        }
        return $this->getData('specialorder');
        
    }
    public function getFormActionUrl()
    {
    	$url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'specialorder/index/post/';
    	return $url;
    
    }
}
