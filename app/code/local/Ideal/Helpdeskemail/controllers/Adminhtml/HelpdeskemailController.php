<?php
 
class Ideal_Helpdeskemail_Adminhtml_HelpdeskemailController extends Mage_Adminhtml_Controller_Action
{
	//const XML_PATH_EMAIL_RECIPIENT  = 'contacts/email/recipient_email';
	//const XML_PATH_EMAIL_RECIPIENT  = 'trans_email/ident_custom1/email';
	//const XML_PATH_EMAIL_SENDER     = 'trans_email/ident_custom1/name';
	const XML_PATH_EMAIL_RECIPIENT  = 'contacts/email/recipient_email';
	const XML_PATH_EMAIL_SENDER     = 'contacts/email/sender_email_identity';
	const XML_PATH_EMAIL_TEMPLATE_USER   = 'helpdeskemail/general/customer_email_template';
	const XML_PATH_EMAIL_TEMPLATE_ADMIN   = 'helpdeskemail/general/email_template';

	public function indexAction()
	{
		if($data = $this->getRequest()->getPost())
		{
	
			$imageUrl = NULL;
			$img = array();
			if (isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
				try {
					$uploader = new Varien_File_Uploader('filename');
					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png','pdf','xls','csv','doc'));
					$uploader->setAllowRenameFiles(false);
					$uploader->setFilesDispersion(false);
					$path = Mage::getBaseDir('media') . DS . 'helpdeskemail' . DS ;
					// $path = Mage::getBaseDir('media') . DS . 'logo' . DS;
					$logoName = $_FILES['filename']['name'];
					$img[] = $_FILES['filename']['name'];
					$uploader->save($path, $logoName);
			
					$filename1 = "media/helpdeskemail/".$_FILES['filename']['name'];
					 $NewimageName = str_replace(' ', '_', $_FILES['filename']['name']);
					$imageUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."helpdeskemail/".$NewimageName;
					
					$note_imagess = serialize($img);
				} catch (Exception $e) {
			
				}
			}
			
			if($imageUrl) {
				$_POST['content_msg'] = $_POST['content'];
				$_POST['content'] = $_POST['content'] . "\n" . $imageUrl;
				$_POST['note_imagename'] = $NewimageName;
				$_POST['note_imagepath'] = $imageUrl;
				$_POST['note_image_serialize'] = $note_imagess;
			}

			$ch = curl_init();
			
			$postvars = '';
			foreach($_POST as $key=>$value) {
				$postvars .= $key . "=" . $value . "&";
			}
			
			$email = Mage::getStoreConfig('trans_email/ident_general/email');
			$storeName = Mage::getStoreConfig('general/store_information/name');
			
			$project = str_replace("www.","",$_SERVER['SERVER_NAME']); 
			
			$postvars .= 'email' . "=" . $email . "&";
			$postvars .= 'company' . "=" . $storeName . "&";
			$postvars .= 'client_project' . "=" . $project . "&";
			
			
			$url = "http://production.idealbrandmarketing.com/include/insertTicketTask.php";
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
			curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
			curl_setopt($ch,CURLOPT_TIMEOUT, 20);
			$response = curl_exec($ch);
			
             //echo "<pre>"; print_r($response); echo "</pre>";exit;
			//print " email sender - ".Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER)." == ".Mage::getStoreConfig('trans_email/ident_general/email')."curl response is: <pre>" ;print_r($response);exit;
			
			foreach (json_decode($response, true) as $key=>$value) {
				$TicketResponse = ($key == 'response_result')?$value:"error";
				$RespEmail = ($key == 'Task_Created_By')?$value:"";
				$Task_Id = ($key == 'Task_Id')?$value:"";
				//echo "<br>" . $key . " -> " . $value;exit;
				if (($key == 'response_result') ) { continue; }
				if ($key == 'Note_Image') {
					$postObject .= str_replace("_", " ", $key) . ' : <img src="'.$value.'" /><br>'; 
					continue;
				}
				if ($key == 'Task_Id') {
					$postObject .= 'Ticket Link : <a href="http://production.idealbrandmarketing.com/task_detail.php?ti='.$value.'" target="_blank">http://production.idealbrandmarketing.com/task_detail.php?ti='.$value.'</a><br>';
					continue;
				}
				if ($key == 'Mail_Subject') {
					$SetMailSubject = $value;
					continue;
				}

				$postObject .= str_replace("_", " ", $key) . ' : ' . $value . '<br>';
			}
			
			//echo " email sender - ".Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER)." == ".Mage::getStoreConfig('trans_email/ident_general/email')."curl response is: <pre>" ;print_r($response);exit;
			//echo Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE_ADMIN) . " -- " .Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER) . " -- " . Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT)."<br>";
			
            //echo Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE_USER) . " -- " .Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER) . " -- " . Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT);exit;
			//echo "<pre>";print_r($array);echo "</pre>";
			 //exit;
			//echo "<pre>";print_r($postObject);echo "</pre>";exit;
			 
			curl_close ($ch);
			
			//if($response == 'success') {
			if($TicketResponse == 'success') {
				try
				{

                    
					$RespEmailTracy = 'john@idealbrandmarketing.com';
					$mailTemplate = Mage::getModel('core/email_template');
 										
					//admin email
					/* @var $mailTemplate Mage_Core_Model_Email_Template */
					$mailTemplate->setReplyTo($RespEmailTracy)
								 ->sendTransactional(
										Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE_ADMIN),
										Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
										$RespEmailTracy,
										null,
										array('data' => $postObject , 'sub' => $SetMailSubject)
								);
					
					if (!$mailTemplate->getSentSuccess()) {
						throw new Exception();
					}
					

					$email1 = Mage::getStoreConfig('trans_email/ident_general/email');
					$mailTemplate2 = Mage::getModel('core/email_template');

					// customer email
                    
					$mailTemplate2->setReplyTo($email1)
								->sendTransactional(
										Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE_USER),
										Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
										$email1,
										null
										
								);
					
					
					if (!$mailTemplate2->getSentSuccess()) {
						throw new Exception();
					}


					/*
					$templateId = 45;
					$sender = Array('name' => 'Ankur',
					'email' => 'support@idealbrandmarketing.com');
					//recepient
					$email = 'support@idealbrandmarketing.com';
					$emaiName = 'Ankur';
					$vars = Array();
					$vars = Array('customVar'=>"test",
					);
					$storeId = Mage::app()->getStore()->getId();
					$translate = Mage::getSingleton('core/translate');
					Mage::getModel('core/email_template')
					->sendTransactional($templateId, $sender, $email, $emailName, $vars, $storeId);
					$translate->setTranslateInline(true);
					*/
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('helpdeskemail')->__('Ticket created Successfully.'));
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('helpdeskemail')->__('Ticket E-Mail Sent Successfully.'));
				}
				catch(Exception $e)
				{
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('helpdeskemail')->__('Ticket created Successfully.'));
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('helpdeskemail')->__('Unable to send Ticket E-Mail'));
				}
			} else {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('helpdeskemail')->__('There is something wrong while creating ticket.'));
			}
			
			Mage::app()->getResponse()->setRedirect($_SERVER['HTTP_REFERER'])->sendResponse();
			
		}
	}
	
	public function indexOldAction()
    {
    	if($data = $this->getRequest()->getPost())
    	{
    		if (isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
    			try {
    				$uploader = new Varien_File_Uploader('filename');
    				$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png','pdf','xls','csv','doc'));
    				$uploader->setAllowRenameFiles(false);
    				$uploader->setFilesDispersion(false);
    				$path = Mage::getBaseDir('media') . DS . 'helpdeskemail' . DS ;
    				// $path = Mage::getBaseDir('media') . DS . 'logo' . DS;
    				$logoName = $_FILES['filename']['name'];
    				$uploader->save($path, $logoName);
    		
    			} catch (Exception $e) {
    		
    			}
    		}

    		$filename1 = "media/helpdeskemail/".$_FILES['filename']['name'];

    		$postObject = new Varien_Object();
    		$postObject->setData($_POST);

    		$mailTemplate = Mage::getModel('core/email_template');
    		
			$fileContents = file_get_contents(Mage::getBaseDir().'/'.$filename1);
			$attachment = $mailTemplate->getMail()->createAttachment($fileContents);
			$attachment->filename = $filename1;    			
    			
			$helpdeskconfig = new Mage_Core_Model_Config();
			$helpdeskconfig ->saveConfig('helpdeskemail/general/email_template', 'helpdeskemail_general_email_template', 'default', 0);
			
    		$mailTemplate->setDesignConfig(array('area' => 'frontend'));
    		$mailTemplate->setReplyTo(Mage::getStoreConfig('trans_email/ident_general/email'));

               		
    		try
    		{
    			$mailTemplate->sendTransactional(
    				'helpdeskemail_general_email_template',
    				Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
    				'help@idealbrandmarketing.com',
    				null,
    				array(
    						'data' => $postObject,
    				)
    		);
    			 
    			//$mail->send();
    			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('helpdeskemail')->__('Successfully to send helpdeskticket email'));
    		}
    		catch(Exception $e)
    		{
    			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('helpdeskemail')->__('Unable to send helpdeskticket email'));
    		}
    		Mage::app()->getResponse()->setRedirect($_SERVER['HTTP_REFERER'])->sendResponse();
    	}
    	else 
    	{
    		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('helpdeskemail')->__('Unable to send helpdeskticket email'));
    		Mage::app()->getResponse()->setRedirect($_SERVER['HTTP_REFERER'])->sendResponse();
    	}
    	//echo "hi"; exit;
    }

    protected function _isAllowed()
    {
    	return true;
    }
    
}
