<?php

/**
 * Customer account controller
 */
require_once 'Mage/Customer/controllers/AccountController.php';

class Ideal_Customer_AccountController extends Mage_Customer_AccountController {

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
	 
    public function createPostAction()
    {
        $errUrl = $this->_getUrl('*/*/create', array('_secure' => true));
		
		
		
	   if($captch=$_POST['defaultReal']){
			if ($this->rpHash($_POST['defaultReal']) != $_POST['defaultRealHash']) {
				Mage::getSingleton('core/session')->addError(Mage::helper('contactform')->__('The security code entered was incorrect. Please try again!'));
				//$this->_redirect('*/');
				$this->_redirectReferer();
				return;
			}
		}
		  
		
        if (!$this->_validateFormKey()) {
            $this->_redirectError($errUrl);
            return;
        }

        /** @var $session Mage_Customer_Model_Session */
        $session = $this->_getSession();
        if ($session->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }

        if (!$this->getRequest()->isPost()) {
            $this->_redirectError($errUrl);
            return;
        }

        $customer = $this->_getCustomer();

        try {
            $errors = $this->_getCustomerErrors($customer);

            if (empty($errors)) {
                $customer->cleanPasswordsValidationData();
                $customer->save();
                $this->_dispatchRegisterSuccess($customer);
                $this->_successProcessRegistration($customer);
                return;
            } else {
                $this->_addSessionError($errors);
            }
        } catch (Mage_Core_Exception $e) {
            $session->setCustomerFormData($this->getRequest()->getPost());
            if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                $url = $this->_getUrl('customer/account/forgotpassword');
                $message = $this->__('There is already an account with this email address. If you are sure that it is your email address, <a href="%s">click here</a> to get your password and access your account.', $url);
            } else {
                $message = $this->_escapeHtml($e->getMessage());
            }
            $session->addError($message);
        } catch (Exception $e) {
            $session->setCustomerFormData($this->getRequest()->getPost());
            $session->addException($e, $this->__('Cannot save the customer.'));
        }

        $this->_redirectError($errUrl);
    }

}