<?php
/*
NOTICE OF LICENSE

This source file is subject to the NekloEULA that is bundled with this package in the file ICENSE.txt.

It is also available through the world-wide-web at this URL: http://store.neklo.com/LICENSE.txt

Copyright (c)  Neklo (http://store.neklo.com/)
*/


class Neklo_ProductPosition_Model_Resource_Product_Status extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('neklo_productposition/product_status', 'entity_id');
    }

    public function updateProductPositions($categoryId, $attachedProductList, $categoryProductList)
    {
        $adapter = $this->_getWriteAdapter();
        $adapter->delete($this->getMainTable(), array('category_id = ?' => $categoryId));

        $productIdListForInsert = array_diff_key($categoryProductList, $attachedProductList);
        $productIdListForDelete = array_diff_key($attachedProductList, $categoryProductList);
        $productIdListForUpdate = array_diff_key($attachedProductList, $productIdListForDelete);

        $insertData = $productIdListForUpdate;
        if (count($productIdListForInsert)) {
            foreach ($productIdListForInsert as $key => $value) {
                $insertData[$key] = 0;
            }
        }
        ksort($insertData);

        if (count($insertData)) {
            $data = array();
            foreach ($insertData as $productId => $isAttached) {
                $data[] = array(
                    'category_id' => (int)$categoryId,
                    'product_id'  => (int)$productId,
                    'is_attached' => (int)$isAttached,
                );
            }
            $adapter->insertMultiple($this->getMainTable(), $data);
        }
    }
}