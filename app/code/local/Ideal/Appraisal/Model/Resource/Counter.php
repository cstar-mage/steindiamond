<?php

class Ideal_Appraisal_Model_Resource_Counter extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('IdealAppraisal/Counter', 'id');
    }
}