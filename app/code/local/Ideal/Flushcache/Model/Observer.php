<?php

class Ideal_Flushcache_Model_Observer
{
    public function cmsPageSaveAfter()
    {
       $this->flushAllCache();
       return $this;
    }
    
    public function cmsBlockSaveAfter()
    {
    	 $this->flushAllCache();
      	 return $this;
    }
    
    public function flushAllCache() {
    	
    	$types = Mage::app()->getCacheInstance()->getTypes();
    	foreach ($types as $type => $data) {
    		Mage::app()->getCacheInstance()->clean($data["tags"]);
    	}
    	Mage::app()->getCacheInstance()->clean();
    	//Mage::getModel('core/design_package')->cleanMergedJsCss();
    	//Mage::dispatchEvent('clean_media_cache_after');
    	//Mage::getModel('catalog/product_image')->clearCache();
    	
    	if(Mage::helper('core')->isModuleOutputEnabled('Litespeed_Litemage'))
    	{
    		Mage::getModel( 'litemage/observer_purge' )->adminPurgeCache(null);
    	}
    	
    	return;
    }
    
    public function removeBadGallery() {
    	
    	$resource = Mage::getSingleton('core/resource');
    	$readConnection = $resource->getConnection('core_read');
    	$writeConnection = $resource->getConnection('core_write');
    	
    	$resource->getTableName('catalog/product');
    	//DELETE FROM catalog_product_entity_media_gallery where `entity_id` NOT IN (SELECT entity_id FROM (catalog_product_entity))
    	$query = "DELETE FROM " . $resource->getTableName('catalog_product_entity_media_gallery') . "  where `entity_id` NOT IN (SELECT entity_id FROM (".$resource->getTableName('catalog_product_entity')."))";
    	$writeConnection->query($query);
    	return $this;
    }
    
    public function purgeLitemageCache() {
		//this event is redundant now, menu issue has been fixed in stain
		return $this;
    	if(!Mage::app()->getStore()->isAdmin()) {
    		//if(Mage::helper('core')->isModuleOutputEnabled('Litespeed_Litemage') && (!Mage::getModel('core/session')->getPurgedLitemageFlag())) {
    		if(Mage::helper('core')->isModuleOutputEnabled('WP_CustomMenu') && Mage::helper('core')->isModuleOutputEnabled('Litespeed_Litemage')) {
    			Mage::getModel( 'litemage/observer_purge' )->adminPurgeCache(null);
    			//Mage::getModel('core/session')->setPurgedLitemageFlag(1);
    		}
    	}
    	return $this;
    }
}