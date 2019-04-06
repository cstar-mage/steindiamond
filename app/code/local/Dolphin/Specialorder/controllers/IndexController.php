<?php
class Dolphin_Specialorder_IndexController extends Mage_Core_Controller_Front_Action
{
	const XML_PATH_EMAIL_RECIPIENT  = 'specialorder/email/recipient_email';
	const XML_PATH_EMAIL_SENDER     = 'specialorder/email/sender_email_identity';
	const XML_PATH_EMAIL_TEMPLATE   = 'specialorder/email/email_template';
	const XML_PATH_AUTOEMAIL_TEMPLATE   = 'specialorder/email/autoemail_template';
	const XML_PATH_ENABLED          = 'specialorder/contacts/enabled';	
	const XML_PATH_EMAILSPECIALORDER_TEMPLATE   = 'specialorder/email/specialorder_template';
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
	
	public function preDispatch()
	{
		parent::preDispatch();
	
		if( !Mage::getStoreConfigFlag(self::XML_PATH_ENABLED) ) {
			$this->norouteAction();
		}
	}
	
    public function indexAction()
    {
		$this->loadLayout();
		//$this->getLayout()->getBlock('specialorder')->setFormAction( Mage::getUrl('*/*/post') );
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');     
		$this->renderLayout();
    }
    
    
     public function postspecialorderAction()
    {
		 if($captch=$_POST['defaultReal10']){
    if ($this->rpHash($_POST['defaultReal10']) != $_POST['defaultReal10Hash']) {
     Mage::getSingleton('core/session')->addError(Mage::helper('contactform')->__('The security code entered was incorrect. Please try again!'));
     //$this->_redirect('*/');
     $this->_redirectReferer();
     return;
    }
   } 
    	$post = $this->getRequest()->getPost();
    	 
    	/*echo "<pre>";
    	 print_r($_POST);echo "<pre>";
    	 print_r($_FILES);echo "</pre></pre>";
    	exit;*/
    
    	if ($post) {
    		$interestedindata = $_POST['interestedin'];
    		//echo '<pre>'; print_r($stone);
    		$interestedin = implode(',',$interestedindata);
    		//exit;
    		$_POST['interestedin'] = $interestedin;
    			
    		$contactpreference1 = $_POST['contactpreference'];
    		$contactpreference = implode(',',$contactpreference1);
    		$_POST['contactpreference'] = $contactpreference;
    			
    		$translate = Mage::getSingleton('core/translate');
    		/* @var $translate Mage_Core_Model_Translate */
    		$translate->setTranslateInline(false);
    		
/*if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
    			try {
    				// Starting upload
    				$uploader = new Varien_File_Uploader('filename');
    				// Any extention would work
    				$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
    				$uploader->setAllowRenameFiles(false);
    					
    				// Set the file upload mode
    				// false -> get the file directly in the specified folder
    				// true -> get the file in the product like folders
    				//	(file.jpg will go in something like /media/f/i/file.jpg)
    				$uploader->setFilesDispersion(false);
    					
    				// We set media as the upload dir
    				$path = Mage::getBaseDir('media') . DS . "brandmanager" . DS ;
    				$result = $uploader->save($path, $_FILES['filename']['name'] );
    				$data['filename'] = $result['file'];
    					
    				$data['filename'] = "brandmanager/".$data['filename'];
    					
    			} catch (Exception $e) {
    				$data['filename'] = $_FILES['filename']['name'];
    			}
    		}*/
    		for($i = 0; $i <= 6; $i++){
    			try {
    				$uploader = new Varien_File_Uploader('imageupload'.$i);
    				$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
    				$uploader->setAllowRenameFiles(false);
    				$uploader->setFilesDispersion(false);
    				$path = Mage::getBaseDir('media'). DS . "special_order" . DS . $post['email'] . DS;
    				
    				$uploader->save($path, str_replace(' ', '',$_FILES['imageupload'.$i]['name'])); 
    				
    				$post['image'.$i] = "media/special_order/".$post['email']."/".$_FILES['imageupload'.$i]['name'];
    
    			} catch (Exception $e) {
    
    			}
    		}
    
    		try {
				
				if(($_FILES['imageupload1']['size'])>0){
    			if(isset($_FILES['imageupload1']['name'])) {
					
    			$upload1= "media/special_order/".$post['email']."/".str_replace(' ', '',$_FILES['imageupload1']['name']);
    			}else {$upload1='';} 
    			    			}else {$upload1='';} 
    			 if(($_FILES['imageupload2']['size'])>0){   			
    			if(isset($_FILES['imageupload1']['name'])) {
    			$upload2= "media/special_order/".$post['email']."/".str_replace(' ', '',$_FILES['imageupload2']['name']);
    			}else {$upload2='';}
    			}
    			else {$upload2='';}
    			$postObject = new Varien_Object();
    			//print_r($_POST); die();
    			$postObject->setData($_POST);
    		/*	echo $upload1;
    			echo "</br>-->".$_FILES['imageupload2']['name'];
    			exit; 
    			echo $upload1;
    			echo $upload2;
    			exit;*/
    			
    			$postObject->setData('imageupload1',$upload1);
    			$postObject->setData('imageupload2',$upload2);
    			$error = false;
    
    			if (!Zend_Validate::is(trim($_POST['name']) , 'NotEmpty')) {
    				$error = true;
    			}
    			/*  if (!Zend_Validate::is(trim($post['interestedin']) , 'NotEmpty')) {
    			 $error = true;
    			 } */
    
    			if (!Zend_Validate::is(trim($_POST['phone']) , 'NotEmpty')) {
    				$error = true;
    			}
    
    			if (!Zend_Validate::is(trim($_POST['email']), 'EmailAddress')) {
    				$error = true;
    			}
    
    			if ($error) {
    				throw new Exception();
    			}
    		 	$mailTemplate = Mage::getModel('core/email_template');
    			//@var $mailTemplate Mage_Core_Model_Email_Template  
    			
    			 $mailTemplate->setDesignConfig(array('area' => 'frontend'))
    			 ->setReplyTo($_POST['email'])
    			 ->sendTransactional(
    			 Mage::getStoreConfig(self::XML_PATH_EMAILSPECIALORDER_TEMPLATE),
    			 Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
    			 Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
    			 null,
    			 array('data' => $postObject)
    			 );
    			 
    			 $mailTemplate->setDesignConfig(array('area' => 'frontend'))
    			 ->setReplyTo(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT))
    			 ->sendTransactional(
    			 		Mage::getStoreConfig(self::XML_PATH_EMAILSPECIALORDER_TEMPLATE),
    			 		Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
    			 		$_POST['email'], 
    			 		null,
    			 		array('data' => $postObject)
    			 ); 
    			$model = Mage::getModel('specialorder/specialorder')->setData($post);
    			if(!$model->save()){
					echo "asd323";exit;
    				throw new Exception();
    			}
    			if (!$mailTemplate->getSentSuccess()){ echo "asd";exit;throw new Exception(); }
    			
    			
    			
    			
    /*
    			$translate->setTranslateInline(true);
    			 Mage::getSingleton('customer/session')->addSuccess('Your Message was send Successfully');
    			 $this->_redirect('special_order');
    
    			 return; */ 
    			 
    			 /* $success_message = Mage::getStoreConfig('contactform/general/success_message');
    			 Mage::getSingleton('customer/session')->addSuccess(Mage::helper('specialorder')->__('Your Special order was send successfully'));
    			 $this->_redirectUrl($post['curl']);
    			 return; */
    
    			 } catch (Exception $e) {
    			 $translate->setTranslateInline(true);
    
    			 Mage::getSingleton('customer/session')->addError(Mage::helper('specialorder')->__('Sorry Special order could not send').$e->getMessage());
    			 $this->_redirectUrl($post['curl']);
    			 return;
            }
    		$translate->setTranslateInline(true);
            Mage::getSingleton('customer/session')->addSuccess('Your Special order was send Successfully');
    		//$this->_redirect('special_order');
    		$this->_redirectUrl($post['curl']);
    
    			return;
    
    	} else {
    	$this->_redirectUrl($post['curl']);
    	}
    }
    
}
