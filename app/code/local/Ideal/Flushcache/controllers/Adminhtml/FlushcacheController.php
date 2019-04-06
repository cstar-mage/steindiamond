<?php
class Ideal_Flushcache_Adminhtml_FlushcacheController extends Mage_Adminhtml_Controller_action {
	
	
	public function purgeAllAction()
    {
        Mage::getModel( 'litemage/observer_purge' )->adminPurgeCache(null);
        $this->_redirect('*/catalog_category');
    }
	
	protected function _isAllowed() {
		return true;
	}
}
