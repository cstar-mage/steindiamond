<?php
class Ideal_Production_CacheController extends Mage_Core_Controller_Front_Action
{

	//public function indexAction()
	//{
	//	$this->loadLayout()->renderLayout();
	//}
	
	public function flushAction()
	{
		$message = '';
		$this->loadLayout();
		$response = array();
		try{
			$types = Mage::app()->getCacheInstance()->getTypes();
			foreach ($types as $type => $data) {
				$message .=Mage::app()->getCacheInstance()->clean($data["tags"]);
			}
			$message .=Mage::app()->getCacheInstance()->clean();
			//Mage::getModel('core/design_package')->cleanMergedJsCss();
			//Mage::dispatchEvent('clean_media_cache_after');
			//Mage::getModel('catalog/product_image')->clearCache();
			
			if(Mage::helper('core')->isModuleOutputEnabled('Litespeed_Litemage'))
			{
				$message .= Mage::getModel( 'litemage/observer_purge' )->adminPurgeCache(null);
			}
			$response['success'] = true;
			$response['message'] = "Cache has been flushed successfully";
		}catch(Exception $e) {
			$response['success'] = false;
			$response['message'] = "There has been error while flushing cache";
		}
		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));

	}
	
	
	
}