<?php
class Ideal_Ringbuilder_IndexController extends Mage_Core_Controller_Front_Action
{
    public function diamondPostAction() {
    	
      
    	
    	if($_REQUEST['hintvalue'] == "hinthiddenvalue")
    	{
           
			
			
			$sendername = $_REQUEST['sendername1'];
			$senderemail = $_REQUEST['senderemail1'];
			$recipientsname = $_REQUEST['recipientsname1']; 
			$recipientsemail = $_REQUEST['recipientsemail1'];		
			$dsku = $_REQUEST['dsku1'];		
			
			
			
			$sku = $dsku;
			$_catalog = Mage::getModel('catalog/product');
			$id = $_catalog->getIdBySku($sku);
			
			
			
			$_product = Mage::getModel('catalog/product')->load($id);
				
		
 
		    $wishlisturlmail=Mage::helper('catalog/product')->getEmailToFriendUrl($_product);
		    $wishlisturlmaillst=str_replace("send/","sendmail/",$wishlisturlmail); 
		    $wishlisturlmaillst = $wishlisturlmaillst."?sender[name]=".$sendername."&sender[email]=".$senderemail."&sender[message]=Please Visit above link.&recipients[name][]=".$recipientsname."&recipients[email][]=".$recipientsemail."&sizestyle=".$style."&metalvalue=".$metal."&caratvalue=".$carat1."&qualityvalue=".$quality."&sizes=".$fingersize;
			//$proid = $id;
			//echo $proid;
			
			Mage::app()->getResponse()->setRedirect($wishlisturlmaillst)->sendResponse();	
    		exit;
    		
    		
		} 
        
    	$data = $this->getRequest()->getParams();
    	$sku = $data['sku'];
    	
    	$refererUrl = Mage::helper('core/http')->getHttpReferer() ? Mage::helper('core/http')->getHttpReferer()  : Mage::getUrl();
    	
    	if(!$sku) {
    		Mage::getSingleton('core/session')->addError('Diamond sku not found');
    		Mage::app()->getFrontController()->getResponse()->setRedirect($refererUrl);
    		Mage::app()->getResponse()->sendResponse();
    		return;
    	}
    	
    	
    	
    	
    	
    	
    	Mage::helper('ringbuilder')->setDiamondSession($sku);
    	
    	//Mage::getSingleton('core/session')->addSuccess('Diamond added to selection');
    	
    	//Mage::app()->getFrontController()->getResponse()->setRedirect($refererUrl);
    	//Mage::app()->getResponse()->sendResponse();
    	$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();

      /* echo "<pre>";
       print_r($ringbuilderData);
       exit; */
    	
        if(isset($ringbuilderData['diamond']) && isset($ringbuilderData['settings'])) {
    		$this->_redirect('ringbuilder/index/review');
    	} elseif(isset($ringbuilderData['diamond'])) {
    		$this->_redirect('build-an-engagement-ring');
    	} elseif(isset($ringbuilderData['settings'])) {
    		$this->_redirect('smart-diamond-search');
    	} else {
    		Mage::app()->getFrontController()->getResponse()->setRedirect($refererUrl);
    		Mage::app()->getResponse()->sendResponse();
    	}
    	
    	return;
    	
    	
    	
    }
    
    public function settingPostAction() {
    	
    	$data = $this->getRequest()->getParams();
    	$sku = $data['sku'];
    	$productId = $data['id'];
    	$categoryId = $data['category'];
    	
    	$refererUrl = Mage::helper('core/http')->getHttpReferer() ? Mage::helper('core/http')->getHttpReferer()  : Mage::getUrl();
    	 
    	if(!$sku) {
    		Mage::getSingleton('core/session')->addError('Ring Settings not found');
    		Mage::app()->getFrontController()->getResponse()->setRedirect($refererUrl);
    		Mage::app()->getResponse()->sendResponse();
    		return;
    	}
    	 
    	Mage::helper('ringbuilder')->setSettingsSession($sku,$productId,$categoryId);
    	 
    	//Mage::getSingleton('core/session')->addSuccess('Ring Settings added to selection');
    	
    	//Mage::app()->getFrontController()->getResponse()->setRedirect($refererUrl);
    	//Mage::app()->getResponse()->sendResponse();
    	$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();
    	if(isset($ringbuilderData['diamond']) && isset($ringbuilderData['settings'])) {
    		$this->_redirect('ringbuilder/index/review');
    	} elseif(isset($ringbuilderData['diamond'])) {
    		$this->_redirect('build-an-engagement-ring');
    	} elseif(isset($ringbuilderData['settings'])) {
    		$this->_redirect('smart-diamond-search');
    	} else {
    		Mage::app()->getFrontController()->getResponse()->setRedirect($refererUrl);
    		Mage::app()->getResponse()->sendResponse();
    	}
    	return;
    	
    }
    
    public function reviewAction() {

    	$refererUrl = Mage::helper('core/http')->getHttpReferer() ? Mage::helper('core/http')->getHttpReferer()  : Mage::getUrl();
    	
    	$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();
    	
    	if((!isset($ringbuilderData['diamond'])) && (!isset($ringbuilderData['settings']))) {
    		
    		//Mage::getSingleton('core/session')->addError('Please select Diamond and Ring Settings first to complete your ring');
    		
    		Mage::app()->getFrontController()->getResponse()->setRedirect($refererUrl);
    		Mage::app()->getResponse()->sendResponse();
    		return;
    	}
    	$categoryId = $ringbuilderData['settings']['category'];
    	$productId = $ringbuilderData['settings']['id'];
    	
    	if(!$categoryId || !$productId) {
    		Mage::getSingleton('checkout/session')->unsRingbuilderData();
    		Mage::getSingleton('core/session')->addError('Ring Settings not found');
    		
    		Mage::app()->getFrontController()->getResponse()->setRedirect($refererUrl);
    		Mage::app()->getResponse()->sendResponse();
    		return;
    	}
    	
    	// Prepare helper and params
    	$viewHelper = Mage::helper('catalog/product_view');
    
    	$params = new Varien_Object();
    	$params->setCategoryId($categoryId);
    	//$params->setSpecifyOptions($specifyOptions);
    
    	// Render page
    	try {
    		$viewHelper->prepareAndRender($productId, $this, $params);
    	} catch (Exception $e) {
    		if ($e->getCode() == $viewHelper->ERR_NO_PRODUCT_LOADED) {
    			if (isset($_GET['store'])  && !$this->getResponse()->isRedirect()) {
    				$this->_redirect('');
    			} elseif (!$this->getResponse()->isRedirect()) {
    				$this->_forward('noRoute');
    			}
    		} else {
    			Mage::logException($e);
    			$this->_forward('noRoute');
    		}
    	}
    }
}
