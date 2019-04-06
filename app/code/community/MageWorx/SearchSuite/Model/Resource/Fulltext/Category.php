<?php

/**
 * MageWorx
 * Search Suite
 *
 * @category   MageWorx
 * @package    MageWorx_SearchSuite
 * @copyright  Copyright (c) 2015 MageWorx (http://www.mageworx.com/)
 */
class MageWorx_SearchSuite_Model_Resource_Fulltext_Category extends MageWorx_SearchSuite_Model_Resource_Fulltext_Abstract {

    public function _construct() {
        $this->_init('mageworx_searchsuite/category_fulltext', 'category_id');
    }

    protected function _rebuildStoreIndexForIndividual($storeId, $categoryIds)
    {
        $categories = $this->_getCategories($storeId, $categoryIds);
        $indexes   = array();
        $deleteIds = array();

        foreach ($categories as $category) {

            $deleteIds[] = $category->getId();

            if (!$category->getIsActive()) {
                continue;
            }

            $categoryIndex = $this->_prepareCategoryIndexData($category);

            $indexes[$category->getId()] = join(' ', $categoryIndex);

        }

        if ($deleteIds) {
            $this->_storeCategoryIndexClean($storeId, $deleteIds);
        }

        if ($indexes) {
            $this->_saveIndexes($storeId, $indexes);
        }
    }

    protected function _rebuildStoreIndexForAll($storeId, $categoryIds = null)
    {
        $this->_storeCategoryIndexClean($storeId);

        $lastId = 0;

        while (true) {
            $categories = $this->_getSearchableCategories($storeId, $categoryIds, $lastId);
            if ($categories->count() == 0) {
                break;
            }
            $indexes = array();
            foreach ($categories as $category) {
                $indexes[$category->getId()] = join(' ', $this->_prepareCategoryIndexData($category));
            }
            $lastId += $categories->count();
            $this->_saveIndexes($storeId, $indexes);
        }

        return $this;
    }


    /**
     * @param Mage_Catalog_Model_Category $category
     * @return array
     */
    protected function _prepareCategoryIndexData($category)
    {
        $index = array();
        $index[] = $category->getName();
        if ($category->getDescription()) {
            $index[] = $this->_prepareDescription($category->getDescription());
        }

        return $index;
    }

    /**
     * @param string $rawDescription
     * @return string
     */
    protected function _prepareDescription($rawDescription)
    {
        $searchString  = array('@&lt;script.*?&gt;.*?&lt;/script&gt;@si', '@&lt;style.*?&gt;.*?&lt;/style&gt;@si');
        $replaceString = array('', '');
        $html = trim(preg_replace($searchString, $replaceString, $rawDescription));
        $html = preg_replace("#\s+#si", " ", trim(strip_tags($html)));
        return html_entity_decode($html, ENT_QUOTES, "UTF-8");
    }

    /**
     * Delete all data (or by specific IDs only) for store
     *
     * @param int $storeId
     * @param null|array $ids
     * @return $this
     */
    protected function _storeCategoryIndexClean($storeId, $ids = null)
    {
        $where = array();
        $where[] = $this->_getWriteAdapter()->quoteInto('store_id= ?', $storeId);

        if ($ids) {
            $where[] = $this->_getWriteAdapter()->quoteInto($this->_idFieldName . ' IN(?)', $ids);
        }

        $this->_getWriteAdapter()->delete($this->getMainTable(), $where);

        return $this;
    }

    protected function _getCategories($storeId, $categoryIds) {

        $collection = Mage::getModel('catalog/category')->getCollection();
        $collection->setStoreId($storeId)
            ->addAttributeToSelect(array('name', 'description'))
            ->addAttributeToSelect('is_active')
            ->addFieldToFilter('path', array('neq' => '1'))
            ->addAttributeToFilter('parent_id', array('neq' => '0'))
            ->addIdFilter($categoryIds);

        return $collection;
    }

    protected function _getSearchableCategories($storeId, $categoryIds = null, $lastId = 0, $limit = 100) {

        $collection = Mage::getModel('catalog/category')->getCollection();

        $collection->setStoreId($storeId)
            ->addAttributeToSelect(array('name', 'description'))
            ->addFieldToFilter('path', array('neq' => '1'))
            ->addAttributeToFilter('parent_id', array('neq' => '0'))
            ->addIsActiveFilter();

        if (!is_null($categoryIds)) {
            $collection->addIdFilter($categoryIds);
        }
        $collection->getSelect()->limit($limit, $lastId);
        return $collection;
    }

    public function prepareResult($object, $queryText, $query) {
        if (!$query->getIsCategoryProcessed()) {
            $this->_performSearch('category_id', $this->getTable('mageworx_searchsuite/category_result'), $queryText, $query);
            $query->setIsCategoryProcessed(1);
            $query->save();
        }

        return $this;
    }

    public function rebuildIndex($storeId = null, $ids = null) {
        $this->resetSearchResults('is_category_processed', $this->getTable('mageworx_searchsuite/category_result'));
        parent::rebuildIndex($storeId, $ids);
    }

}