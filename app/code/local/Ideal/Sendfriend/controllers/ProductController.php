<?php

/**
 * Customer account controller
 */
require_once 'Mage/Sendfriend/controllers/ProductController.php';

class Ideal_Sendfriend_ProductController extends Mage_Sendfriend_ProductController {

	private function rpHash($value) {
		$hash = 5381;
		$value = strtoupper($value);
		for($i = 0; $i < strlen($value); $i++) {
			$hash = ($this->leftShift32($hash, 5) + $hash) + ord(substr($value, $i));
		}
		return $hash;
	 }
	 // Perform a 32bit left shift
	 private function leftShift32($number, $steps) {
		  // convert to binary (string)
		  $binary = decbin($number);
		  // left-pad with 0's if necessary
		  $binary = str_pad($binary, 32, "0", STR_PAD_LEFT);
		  // left shift manually
		  $binary = $binary.str_repeat("0", $steps);
		  // get the last 32 bits
		  $binary = substr($binary, strlen($binary) - 32);
		  // if it's a positive number return it
		  // otherwise return the 2's complement
		  return ($binary{0} == "0" ? bindec($binary) :
		  -(pow(2, 31) - bindec(substr($binary, 1))));
	 }
	 
   /**
     * Send Email Post Action
     *
     */
    public function sendmailAction()
    {
		
		if($captch=$_POST['defaultRealss']){
						if ($this->rpHash($_POST['defaultRealss']) != $_POST['defaultRealssHash']) {
							Mage::getSingleton('core/session')->addError(('The security code entered was incorrect. Please try again!'));
							//$this->_redirect('*/');
							$this->_redirectReferer();
							return;
						}
		}
					
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/send', array('_current' => true));
        }

        $product    = $this->_initProduct();
        $model      = $this->_initSendToFriendModel();
        $data       = $this->getRequest()->getPost();

        if (!$product || !$data) {
            $this->_forward('noRoute');
            return;
        }

        $categoryId = $this->getRequest()->getParam('cat_id', null);
        if ($categoryId) {
            $category = Mage::getModel('catalog/category')
                ->load($categoryId);
            $product->setCategory($category);
            Mage::register('current_category', $category);
        }

        $model->setSender($this->getRequest()->getPost('sender'));
        $model->setRecipients($this->getRequest()->getPost('recipients'));
        $model->setProduct($product);

        try {
            $validate = $model->validate();
            if ($validate === true) {
                $model->send();
                Mage::getSingleton('catalog/session')->addSuccess($this->__('The link to a friend was sent.'));
                $this->_redirectSuccess($product->getProductUrl());
                return;
            }
            else {
                if (is_array($validate)) {
                    foreach ($validate as $errorMessage) {
                        Mage::getSingleton('catalog/session')->addError($errorMessage);
                    }
                }
                else {
                    Mage::getSingleton('catalog/session')->addError($this->__('There were some problems with the data.'));
                }
            }
        }
        catch (Mage_Core_Exception $e) {
            Mage::getSingleton('catalog/session')->addError($e->getMessage());
        }
        catch (Exception $e) {
            Mage::getSingleton('catalog/session')
                ->addException($e, $this->__('Some emails were not sent.'));
        }

        // save form data
        Mage::getSingleton('catalog/session')->setSendfriendFormData($data);

        $this->_redirectError(Mage::getURL('*/*/send', array('_current' => true)));
    }

}
