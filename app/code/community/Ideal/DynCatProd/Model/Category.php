<?php

/**
 * Catelog Category functions
 *
 * @category   Ideal
 * @package    DynCatProd
 * @author     Lucas van Staden (sales@proxiblue.com.au)
 */

class Ideal_DynCatProd_Model_Category extends Mage_Catalog_Model_Category {

    /**
     * Get category products collection
     *
     * @return Varien_Data_Collection_Db
     */
    public function getProductCollection() {
        $collection = Mage::getResourceModel('catalog/product_collection')->setStoreId($this->getStoreId());
        if (Mage::helper('dyncatprod')->addDynamicFilters($collection,$this->getEntityId(),true,true)){
            return parent::getProductCollection();
        }
        return parent::getProductCollection();
    }
    public function getProductCollectionDynamic() {
    	$collection = Mage::getResourceModel('catalog/product_collection')->setStoreId($this->getStoreId());
    	if (Mage::helper('dyncatprod')->addDynamicFilters($collection,$this->getEntityId(),true,true)){
    		return $collection;
    	}
    	return parent::getProductCollection();
    }
    /**
     * Set the category product count, taking dynamic products into consideration
     *
     * @param type $value
     */



    /**
     * Retrieve count products of category
     *
     * @return int
     */
    public function getProductCount()
    {
        $this->setProductCount($this->getData('product_count'));
        if (!$this->hasProductCount()) {
            $count = $this->_getResource()->getProductCount($this); // load product count
            $this->setData('product_count', $count);
        }
        return $this->getData('product_count');
    }
}
