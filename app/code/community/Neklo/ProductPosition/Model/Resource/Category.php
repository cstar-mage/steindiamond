<?php
/*
NOTICE OF LICENSE

This source file is subject to the NekloEULA that is bundled with this package in the file ICENSE.txt.

It is also available through the world-wide-web at this URL: http://store.neklo.com/LICENSE.txt

Copyright (c)  Neklo (http://store.neklo.com/)
*/


class Neklo_ProductPosition_Model_Resource_Category extends Mage_Catalog_Model_Resource_Category
{
    /**
     * Get sorted positions of associated to category products
     *
     * @param Mage_Catalog_Model_Category $category
     * @return array
     */
    public function getSortedProductsPosition($category)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->_categoryProductTable, array('product_id', 'position'))
            ->where('category_id = ' . (int)$category->getId())
            ->order(
                array(
                    'position ' . Zend_Db_Select::SQL_ASC,
                    'product_id ' . Zend_Db_Select::SQL_DESC,
                )
            )
        ;
        $sortedProductPosition = $this->_getReadAdapter()->fetchPairs($select);
        return $sortedProductPosition;
    }

    /**
     * Get attached products for category products
     *
     * @param Mage_Catalog_Model_Category $category
     * @return array
     */
    public function getAttachedProducts($category)
    {
        $select = $this->_getReadAdapter()->select()
            ->from(
                array('category_product' => $this->_categoryProductTable),
                array()
            )
            ->joinLeft(
                array('product_status' => $this->getTable('neklo_productposition/product_status')),
                'category_product.product_id = product_status.product_id',
                array()
            )
            ->where('category_product.category_id = ' . (int)$category->getId())
            ->columns(
                array(
                    'product_id'  => 'category_product.product_id',
                    'is_attached' => 'IF(product_status.is_attached, product_status.is_attached, 0)',
                )
            )
        ;
        $attachedProductList = $this->_getReadAdapter()->fetchPairs($select);
        return $attachedProductList;
    }
}