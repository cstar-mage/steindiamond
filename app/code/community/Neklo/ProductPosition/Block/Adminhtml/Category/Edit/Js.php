<?php
/*
NOTICE OF LICENSE

This source file is subject to the NekloEULA that is bundled with this package in the file ICENSE.txt.

It is also available through the world-wide-web at this URL: http://store.neklo.com/LICENSE.txt

Copyright (c)  Neklo (http://store.neklo.com/)
*/


class Neklo_ProductPosition_Block_Adminhtml_Category_Edit_Js extends Mage_Adminhtml_Block_Template
{
    /**
     * @return bool
     */
    public function canShow()
    {
        return $this->getConfig()->isEnabled();
    }

    /**
     * @return Neklo_ProductPosition_Helper_Config
     */
    public function getConfig()
    {
        return Mage::helper('neklo_productposition/config');
    }
}