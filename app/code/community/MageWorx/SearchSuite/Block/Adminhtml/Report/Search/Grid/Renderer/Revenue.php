<?php

/**
 * MageWorx
 * Search Suite
 *
 * @category   MageWorx
 * @package    MageWorx_SearchSuite
 * @copyright  Copyright (c) 2015 MageWorx (http://www.mageworx.com/)
 */
class MageWorx_SearchSuite_Block_Adminhtml_Report_Search_Grid_Renderer_Revenue extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $value = floatval($row->getData('real_revenue'));

        return Mage::helper('core')->formatPrice($value, false);
    }

}
