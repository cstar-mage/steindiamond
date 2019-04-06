<?php
class Ideal_Evolved_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getThemeConfig()
	{
		$model = Mage::getModel('evolved/evolved');
		$collection = $model->getCollection()->addFieldToFilter('value',array('neq' => null));
       // echo "<pre>"; echo "count => ".count($collection->getData())."<br />"; print_r($collection->getData()); exit;
		$theme = array();
		foreach($collection as $row)
		{
			$theme[$row->getData('field')] = $row->getData('value');
		}
		return $theme;
	}	
	public function getThemeBlockConfig($type)
	{
		$model = Mage::getModel('evolved/evolved');
		$collection = $model->getCollection()
				      ->addFieldToFilter('type',array('like' => $type))
					  ->addFieldToFilter('value',array('neq' => null));
		// echo "<pre>"; echo "count => ".count($collection->getData())."<br />"; print_r($collection->getData()); exit;
		$theme = array();
		foreach($collection as $row)
		{
			$theme[$row->getData('field')] = $row->getData('value');
		}
		return $theme;
	}
	public function getContactsTopLinks()
	{
		return $this->_getUrl('contacts/');
	}
	public function getContactsMetaKeywords()
	{
		return Mage::getStoreConfig('evolved/contacts/metakeywords');
	}
	public function getContactsMetaDescription()
	{
		return Mage::getStoreConfig('evolved/contacts/metadescription');
	}
}