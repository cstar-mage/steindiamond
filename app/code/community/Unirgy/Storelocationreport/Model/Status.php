<?php

class Unirgy_Storelocationreport_Model_Status extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 2;

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('storelocationreport')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('storelocationreport')->__('Disabled')
        );
    }
}