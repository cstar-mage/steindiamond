<?php
/**
 * MageWorx
 * Search Suite
 *
 * @category   MageWorx
 * @package    MageWorx_SearchSuiteAutocomplete
 * @copyright  Copyright (c) 2015 MageWorx (http://www.mageworx.com/)
 */
class MageWorx_SearchSuiteAutocomplete_Block_Helper extends Mage_Review_Block_Helper
{
    protected function _construct()
    {
        parent::_construct();

        $this->_availableTemplates['short'] = 'mageworx/searchsuiteautocomplete/review/summary_short.phtml';
    }
}
