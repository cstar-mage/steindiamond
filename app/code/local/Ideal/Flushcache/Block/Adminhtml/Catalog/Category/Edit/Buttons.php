<?php
class Ideal_Flushcache_Block_Adminhtml_Catalog_Category_Edit_Buttons 
    extends Mage_Adminhtml_Block_Catalog_Category_Abstract {
    public function addButtons()
    {
        $this->getParentBlock()->getChild('form')
                    ->addAdditionalButton('litemage_purge_all', array(
                        'label' => $this->helper('flushcache')->__('Flush All LiteMage Cache'),
                        'class' => 'go',
                        'onclick' => "setLocation('{$this->getUrl('adminhtml/flushcache/purgeAll')}')"
        ));
        return $this;
    }
}