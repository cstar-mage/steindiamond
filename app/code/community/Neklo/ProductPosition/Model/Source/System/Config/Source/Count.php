<?php
/*
NOTICE OF LICENSE

This source file is subject to the NekloEULA that is bundled with this package in the file ICENSE.txt.

It is also available through the world-wide-web at this URL: http://store.neklo.com/LICENSE.txt

Copyright (c)  Neklo (http://store.neklo.com/)
*/


class Neklo_ProductPosition_Model_Source_System_Config_Source_Count
{
    const PER_PAGE_VALUE_LIST = 'catalog/frontend/grid_per_page_values';
    const PER_PAGE_VALUE_DELIMITER = ',';

    public function getPerPageValues($store = null)
    {
        $valueList = Mage::getStoreConfig(self::PER_PAGE_VALUE_LIST, $store);
        return explode(self::PER_PAGE_VALUE_DELIMITER, $valueList);
    }

    public function toOptionArray()
    {
        $valueList = $this->getPerPageValues();
        $optionArray = array();
        foreach ($valueList as $value) {
            $optionArray[] = array(
                'value' => $value,
                'label' => $value,
            );
        }
        return $optionArray;
    }

    public function toArray()
    {
        $valueList = $this->getPerPageValues();
        $optionArray = array();
        foreach ($valueList as $value) {
            $optionArray[$value] = $value;
        }
        return $optionArray;
    }
}