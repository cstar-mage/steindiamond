<?php
/**
 * MGT-Commerce GmbH
 * http://www.mgt-commerce.com
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@mgt-commerce.com so we can send you a copy immediately.
 *
 * @category    Mgt
 * @package     Mgt_Akismet
 * @author      Stefan Wieczorek <stefan.wieczorek@mgt-commerce.com>
 * @copyright   Copyright (c) 2012 (http://www.mgt-commerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mgt_Akismet_Adminhtml_MgtakismetController extends Mage_Adminhtml_Controller_Action
{
    public function testAction()
    {
        $akismet = Mage::getSingleton('mgt_akismet/akismet');
        $service = $akismet->getService();
        $apiKey = $akismet->getApiKey();
        if ($apiKey && $service->verifyKey($apiKey)) {
            $message = 'Your akismet api key is valid';
            Mage::getSingleton('adminhtml/session')->addSuccess($message);
        } else {
            $message = 'Your akismet api key is not valid';
            Mage::getSingleton('adminhtml/session')->addError($message);
        }
        $this->_redirectReferer();
    }
}