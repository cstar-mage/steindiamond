<?php

class Ideal_Appraisal_Model_Config_PropertiesSource
{
    public function toOptionArray() {
        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
            ->getItems();

        $result = array();
        foreach ($attributes as $attribute){
            $result[] = array('value' => $attribute->getAttributecode(), 'label' => $attribute->getFrontendLabel());
        }

        return $result;
    }
}