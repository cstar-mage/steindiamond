<?php

/**
 * Observer events
 *
 * @category   Ideal
 * @package    DynCatProd
 * @author     Lucas van Staden (sales@proxiblue.com.au)
 */
class Ideal_DynCatProd_Model_Observer {

    /**
     * Event to add category filter
     * 
     * @param Varien_Event_Observer $observer
     * @return Ideal_DynCatProd_Model_Adminhtml_Observer 
     */
	public function saveCategoryData(Varien_Event_Observer $observer)
	{
		
		/*$category = $observer->getEvent()->getDataObject();
		$resource = Mage::getSingleton('core/resource');
		$tableName = $resource->getTableName('catalog_category_product');
		$readConnection = $resource->getConnection('core_read');
		$query = 'SELECT * FROM ' . $tableName . ' where category_id = '.$category->getId();
		$results = $readConnection->fetchAll($query);
		$products = array();
		if($results)
		{
			foreach($results as $resultsvalue)
			{
				$products[] = $resultsvalue['product_id'];
			}	
		}
		$category = Mage::getModel('catalog/category')->load($category->getId());
		$collection = $category->getProductCollectionDynamic();
		if($collection)
		{
			foreach($collection as $collectionvalue)
			{
				$products[] = $collectionvalue->getEntityId();
			}	
		}
	
		$newCategories = array($category->getId());
		foreach ($products as $id){
			$product = Mage::getModel('catalog/product')->load($id);
		    $product->setCategoryIds(
		        array_merge($product->getCategoryIds(), $newCategories)
		    );
    			$product->save();
		}
		*/
	
	}
    public function catalog_product_collection_apply_limitations_after(Varien_Event_Observer $observer) {
        try {
            $collection = $observer->getCollection();

            if ($collection->hasFlag('category_ids')) {
                //is there a + at the right side, if so include all child categories
                $categories = $collection->getFlag('category_ids');
                $setCategories = explode(',', $categories);
                $rebuiltCategories = array();
                $subCats = array();
                foreach ($setCategories as $categoryId) {
                    $rightMost = substr($categoryId, -1, 1);
                    if ($rightMost == '+') {
                        // get all child category ids.
                        $categoryId = substr($categoryId, 0, strlen($categoryId) - 1);
                        $categoryModel = Mage::getModel('catalog/category')->load($categoryId);
                        if ($categoryModel) {
                            $subCats = explode(',', $categoryModel->getChildren());
                        }
                    }
                    $rebuiltCategories = array_merge($rebuiltCategories, $subCats, array($categoryId));
                }
                $categories = implode(',', array_unique(array_filter($rebuiltCategories)));
                $conditions = array(
                    'cat_index.product_id=e.entity_id',
                    $collection->getConnection()->quoteInto('cat_index.category_id IN (' . $categories . ')', "")
                );
                if (!Mage::app()->getStore()->isAdmin()) {
                    $conditions[] = $collection->getConnection()->quoteInto('cat_index.store_id=?', $collection->getFlag('store_id'));
                }
                $joinCond = join(' AND ', $conditions);
                $fromPart = $collection->getSelect()->getPart(Zend_Db_Select::FROM);

                if (isset($fromPart['cat_index'])) {
                    $fromPart['cat_index']['joinCondition'] = $joinCond;
                    $collection->getSelect()->setPart(Zend_Db_Select::FROM, $fromPart);
                } else {
                    $collection->getSelect()->join(
                            array('cat_index' => $collection->getTable('catalog/category_product_index')), $joinCond, array('cat_index_category_id' => 'category_id')
                    );
                }
            }
        } catch (Exception $e) {
            // log any issues, but allow system to continue.    
            Mage::logException($e);
            //mage::throwException($e->getMessage());
        }
        return $this;
    }

    /**
     * Fix issue with category filter getting in the way
     * 
     * @param Varien_Event_Observer $observer
     * @return Ideal_DynCatProd_Model_Adminhtml_Observer 
     */
    public function catalog_block_product_list_collection(Varien_Event_Observer $observer) {
        try {
            $collection = $observer->getCollection();
            if ($collection->hasFlag('remove_cat_filter')) {
                $fromPart = $collection->getSelect()->getPart(Zend_Db_Select::FROM);
                if (isset($fromPart['cat_index']) && isset($fromPart['cat_index']['joinCondition'])) {
                    $joinParts = explode('AND', $fromPart['cat_index']['joinCondition']);
                    foreach ($joinParts as $key => $part) {
                        if (strPos($part, $collection->getFlag('remove_cat_filter')) > 0) {
                            unset($joinParts[$key]);
                        }
                    }
                    $fromPart['cat_index']['joinCondition'] = implode(' AND ', $joinParts);
                    $collection->getSelect()->setPart(Zend_Db_Select::FROM, $fromPart);
                    $collection->getSelect()->group('e.entity_id');
                }
            }
        } catch (Exception $e) {
            // log any issues, but allow system to continue.    
            Mage::logException($e);
            //mage::throwException($e->getMessage());
        }
        return $this;
    }

}
