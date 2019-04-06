<?php

/**
 * Tab in admin category
 *
 * @category   Ideal
 * @package    DynCatProd
 * @author     Lucas van Staden (sales@proxiblue.com.au)
 */

class Ideal_DynCatProd_Block_Adminhtml_Catalog_Category_Tab_DynCatProd_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    /**
     * Constructor
     * */
    public function __construct() {
        parent::__construct();
        $this->setUseAjax(true);
        $this->setId('dynamic_products_grid');
        $this->setTemplate('dyncatprod/grid.phtml');
    }

    protected function _prepareColumns() {
    	
        $this->addColumn('entity_id', array(
            'header' => Mage::helper('catalog')->__('ID'),
            'sortable' => true,
            'width' => '60',
            'index' => 'entity_id'
        ));
        $this->addColumn('name', array(
            'header' => Mage::helper('catalog')->__('Name'),
            'index' => 'name'
        ));
        
        $this->addColumn('sku', array(
            'header' => Mage::helper('catalog')->__('SKU'),
            'width' => '80',
            'index' => 'sku'
        ));
        $this->addColumn('price', array(
            'header' => Mage::helper('catalog')->__('Price'),
            'type' => 'currency',
            'width' => '1',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index' => 'price'
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl() {
        return $this->getUrl('dyncatprod/adminhtml_catalog_category/grid', array('category_id' => $this->getCategory()->getId(), '_current' => true));
    }

    public function getCategory() {
        return Mage::registry('category');
    }

protected function _prepareCollection() {
    	
    	$category = Mage::getModel('catalog/category')->load(Mage::registry('category')->getId());

    	if($category->getData('dynamic_attributes'))
    	{

    		$storeId = Mage::app()->getStore()->getStoreId();
    		//echo $storeId; exit;
    		$resource = Mage::getSingleton('core/resource');
    		$readConnection = $resource->getConnection('core_read');
    		$dynamicquery = "SELECT * FROM " . $resource->getTableName("catalog_category_dynamic_product_index") . " where store_id = ".$storeId." and category_id = ".$category->getId();
    		$dynamicproresults = $readConnection->fetchAll($dynamicquery);
    		if(count($dynamicproresults) != 0)
    		{

    			foreach($dynamicproresults as $dynamicproresults1)
    			{
    				$dynamicproarr = $dynamicproresults1['product_ids'];
    			}
    			$dynamicproarrex = explode(",",$dynamicproarr);
    			//echo $dynamicproarrim; exit;
    			$collection = Mage::getModel('catalog/product')->getCollection()
    			->addAttributeToSelect('name')
    			->addAttributeToSelect('sku')
    			->addAttributeToSelect('price')
    			->addStoreFilter($this->getRequest()->getParam('store'))
    			->addFieldToFilter('entity_id', array('in' => $dynamicproarrex));
    			$this->setCollection($collection);
    			return parent::_prepareCollection();
    		}
    	}

    	
    	/*
        if ($this->getCategory()->getId()) {
            $this->setDefaultFilter(array('in_category' => 1));
        }
        $collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('sku')
                ->addAttributeToSelect('price')
                ->addStoreFilter($this->getRequest()->getParam('store'))
                ->joinField('position', 'catalog/category_product', 'position', 'product_id=entity_id', 'category_id=' . (int) $this->getRequest()->getParam('id', 0), 'left');
        if (Mage::helper('dyncatprod')->addDynamicFilters($collection)) {
            $this->setCollection($collection);
            $this->_preparePage();
            if ($this->getCategory()->getProductsReadonly()) {
                $productIds = $this->_getSelectedProducts();
                if (empty($productIds)) {
                    $productIds = 0;
                }
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
            }
        } else {
            return parent::_prepareCollection();
        }*/
        
    }

}

