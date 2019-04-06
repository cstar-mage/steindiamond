<?php
/*
NOTICE OF LICENSE

This source file is subject to the NekloEULA that is bundled with this package in the file ICENSE.txt.

It is also available through the world-wide-web at this URL: http://store.neklo.com/LICENSE.txt

Copyright (c)  Neklo (http://store.neklo.com/)
*/


class Neklo_ProductPosition_Helper_Config extends Mage_Core_Helper_Data
{
    const GENERAL_IS_ENABLED = 'neklo_productposition/general/is_enabled';

    const CATALOG_GRID_PER_PAGE = 'catalog/frontend/grid_per_page';
    const DEFAULT_PER_PAGE_VALUE = 20;
    const CATALOG_GRID_COLUMN_COUNT = 'neklo_productposition/grid/column_count';
    const DEFAULT_COLUMN_COUNT_VALUE = 4;

    /**
     * @param null|int|Mage_Core_Model_Store $store
     *
     * @return bool
     */
    public function isEnabled($store = null)
    {
        $isConfigEnabled = Mage::getStoreConfigFlag(self::GENERAL_IS_ENABLED, $store);
        $isModuleEnabled = $this->isModuleEnabled();
        $isModuleOutputEnabled = $this->isModuleOutputEnabled();
        return $isConfigEnabled && $isModuleEnabled && $isModuleOutputEnabled;
    }

    public function getPerPageValue()
    {
        $perPageValue = (int)Mage::getStoreConfig(self::CATALOG_GRID_PER_PAGE);
        if ($perPageValue === 0) {
            return self::DEFAULT_COLUMN_COUNT_VALUE;
        }
        return $perPageValue;
    }

    public function getColumnCount()
    {
        $columnCount = (int)Mage::getStoreConfig(self::CATALOG_GRID_COLUMN_COUNT);
        if ($columnCount === 0) {
            return self::DEFAULT_PER_PAGE_VALUE;
        }
        return $columnCount;
    }

    public function getRowCount()
    {
        $perPageValue = $this->getPerPageValue();
        $columnCount = $this->getColumnCount();
        return ceil($perPageValue / $columnCount);
    }
}