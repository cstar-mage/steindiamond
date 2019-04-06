<?php
/*
NOTICE OF LICENSE

This source file is subject to the NekloEULA that is bundled with this package in the file ICENSE.txt.

It is also available through the world-wide-web at this URL: http://store.neklo.com/LICENSE.txt

Copyright (c)  Neklo (http://store.neklo.com/)
*/


class Neklo_ProductPosition_Helper_Product extends Mage_Core_Helper_Data
{
    const IMAGE_WIDTH = 100;
    const NO_SELECTION = 'no_selection';

    protected $_jsonMap = array(
        'entity_id',
        'name',
        'sku',
        'price',
        'position',
    );

    /**
     * @return string
     */
    public function getAttachedProductsJson()
    {
        $products = Mage::getResourceSingleton('neklo_productposition/category')->getAttachedProducts($this->getCategory());
        if (count($products) === 0) {
            return '{}';
        }
        return Mage::helper('core')->jsonEncode($products);
    }

    /**
     * @return string
     */
    public function getSortedProductsPositionJson()
    {
        $products = Mage::getResourceSingleton('neklo_productposition/category')->getSortedProductsPosition($this->getCategory());
        if (count($products) === 0) {
            return '{}';
        }
        $productIdList = array_keys($products);
        $productPositionList = range(1, count($productIdList));
        $products = array_combine($productIdList, $productPositionList);
        return Mage::helper('core')->jsonEncode($products);
    }

    /**
     * @param int $page
     * @param int $count
     *
     * @return string
     */
    public function getCollectionJson($page = 1, $count = 20)
    {
        $productDataList = array();
        $collection = $this->getCollection($page, $count);
        $startPosition = $count * $page - $count + 1;
        foreach ($collection as $product) {
            $productData = $product->toArray($this->_jsonMap);
            unset($productData['stock_item']);
            $productData['image'] = $this->resizeImage($product);
            $productData['price'] = Mage::helper('core')->currency($productData['price'], true, false);
            $productData['position'] = $startPosition;
            $productData['attached'] = var_export(!!$product->getIsAttached(), true);
            $productDataList[] = $productData;
            $startPosition++;
        }
        return Mage::helper('core')->jsonEncode($productDataList);
    }

    /**
     * @param int $page
     * @param int $count
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getCollection($page = 1, $count = 20)
    {
        /* @var $collection Mage_Catalog_Model_Resource_Product_Collection */
        $collection = $this->getProductCollection();
        
        $desablestatus =  Mage::getStoreConfig('neklo_productposition/general/hide_disabled_product');     
        
        if($desablestatus == 1){   
        
			$collection->addAttributeToFilter('status',array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED) ); 
        
		}
        
        
        $collection->setPageSize($count);
        if ($collection->getLastPageNumber() >= $page) {
            $collection->setCurPage($page);
            $collection->setOrder('position', 'ASC');
            $collection->setOrder('entity_id', 'DESC');
        } else {
            $collection->addFieldToFilter('entity_id', 0);
        }
        return $collection;
    }

    /**
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getProductCollection()
    {
        /* @var Mage_Catalog_Model_Resource_Product_Collection $collection */
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('price')
            ->addAttributeToSelect('thumbnail')
            ->joinField(
                "position",
                "catalog/category_product",
                "position",
                "product_id = entity_id",
                "category_id = {$this->getRequestCategoryId()}",
                "inner"
            )
            ->joinAttribute(
                'visibility',
                'catalog_product/visibility',
                'entity_id',
                null,
                'inner',
                $this->getRequestStoreId()
            )
            ->addAttributeToFilter(
                'visibility',
                array(
                    'in' => array(
                        Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG,
                        Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
                    )
                )
            )
        ;
        $collection->getSelect()->joinLeft(
            array('product_status' => $collection->getTable('neklo_productposition/product_status')),
            'e.entity_id = product_status.product_id AND product_status.category_id = ' . (int)$this->getRequestCategoryId(),
            array('is_attached' => 'IF(product_status.is_attached, product_status.is_attached, 0)')
        );
        return $collection;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     *
     * @return string
     */
    public function resizeImage($product)
    {
        $currentDesign = Mage::getDesign()->setAllGetOld(
            array(
                'area' => Mage_Core_Model_Design_Package::DEFAULT_AREA,
            )
        );
        $imageHelper = Mage::helper('catalog/image')->init($product, 'thumbnail');
        $imageHelper
            ->constrainOnly(false)
            ->keepAspectRatio(false)
            ->keepFrame(false)
        ;
        $resizedImage = (string)$imageHelper->resize(self::IMAGE_WIDTH, null);
        Mage::getDesign()->setAllGetOld($currentDesign);
        return $resizedImage;
    }

    /**
     * @return int
     */
    public function getRequestCategoryId()
    {
        return (int)Mage::app()->getRequest()->getParam('id', 0);
    }

    /**
     * @return int
     */
    public function getRequestStoreId()
    {
        return (int)Mage::app()->getRequest()->getParam('store', 0);
    }

    /**
     * @return Mage_Catalog_Model_Category
     */
    public function getCategory()
    {
        return Mage::registry('category');
    }
}
