<?php

/**
 * MageWorx
 * Search Suite
 *
 * @category   MageWorx
 * @package    MageWorx_SearchSuite
 * @copyright  Copyright (c) 2015 MageWorx (http://www.mageworx.com/)
 */
class MageWorx_SearchSuite_Model_Resource_Tracking_Purchase extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct() {
        $this->_init('mageworx_searchsuite/purchase_tracking', 'id');
    }

}
