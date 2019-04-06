<?php
class Ideal_Ringbuilder_IndexController extends Mage_Core_Controller_Front_Action
{
    public function diamondPostAction() {

    	
        
    	$data = $this->getRequest()->getParams();
    	$sku = $data['sku'];
    	$rapnetid = $data['id'];
    	
    	
		
	
    	
    	$refererUrl = Mage::helper('core/http')->getHttpReferer() ? Mage::helper('core/http')->getHttpReferer()  : Mage::getUrl();
    	
    	if(!$sku) {
    		Mage::getSingleton('core/session')->addError('Diamond sku not found');
    		Mage::app()->getFrontController()->getResponse()->setRedirect($refererUrl);
    		Mage::app()->getResponse()->sendResponse();
    		return;
    	}
    	
    	// for restrict add to ring where shape not matching for ring and diamond		
    	$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();	
    	if(count($ringbuilderData['settings']) > 0 && count($ringbuilderData['steps']) > 0){			
		}else{
			$ringbuilderData['steps'] = array('sname'=>'diamond');
			Mage::getSingleton('checkout/session')->setRingbuilderData($ringbuilderData);
		}
		
    		 
    	//~ if(isset($ringbuilderData['settings']) && $ringbuilderData['settings']['shape'] != '') {
			//~ 
    		//~ $diamond = Mage::helper('ringbuilder')->loadDiamondBySku($sku,$rapnetid);   		
    		//~ 
    		//~ if(ucfirst($ringbuilderData['settings']['shape']) != ucfirst($diamond->getShape())) {
    			//~ 
    			//~ Mage::helper('ringbuilder')->unsetDiamondSession();  // remove bad shape if any
    			//~ 
    			//~ Mage::getSingleton('core/session')->addError('Diamond Shape not matching with Selected Ring. Please select same shape for setting and diamond');
    			//~ 
    			//~ $shape = $ringbuilderData['settings']['shape'];
    			//~ if($shape != '') {
    				//~ $smartUrl = Mage::getBaseUrl().'diamondsearch?shape='.$shape;
    				//~ $this->_redirectUrl($smartUrl);
    			//~ } else {
    				//~ $this->_redirect('diamondsearch');
    			//~ }
    			//~ return;
    		//~ }
    	//~ }
    	
    	
    	
    	
    	
    	Mage::helper('ringbuilder')->setDiamondSession($sku,$rapnetid);
    	
    	//Mage::getSingleton('core/session')->addSuccess('Diamond added to selection');
    	
    	//Mage::app()->getFrontController()->getResponse()->setRedirect($refererUrl);
    	//Mage::app()->getResponse()->sendResponse();
    	$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();

	  //~ echo "<pre>";
       //~ print_r($ringbuilderData);
       //~ exit; 
       
    	
        if(isset($ringbuilderData['diamond']) && isset($ringbuilderData['settings'])) {
    		$this->_redirect('ringbuilder/index/review',array('rapnetid'=>$rapnetid,'diamond'=>$ringbuilderData['diamond']['sku'],'settings'=>$ringbuilderData['settings']['sku']));
    	} elseif(isset($ringbuilderData['diamond'])) {
    		
    		//center_shape_availability
    		$attr = Mage::getModel('catalog/product')->getResource()->getAttribute("center_shape_availability");
    		$shapeFilterId = "";
    		if ($attr->usesSource()) {
    			$shapeFilterId = $attr->getSource()->getOptionId($ringbuilderData['diamond']['center_shape_availability']);/* Updated
 shape to center_shape-availablility by CStar */
    		}  
    		  		//echo $ringbuilderData['diamond']['shape'] . "==" . $shapeFilterId; exit;
    		  		
    		  		
    		  		
	 
       
       
    		if($shapeFilterId != "") {
    			$filterUrl = Mage::getBaseUrl().'engagement-rings.html?center_shape_availability='.$shapeFilterId;
    			$this->_redirectUrl($filterUrl);
    		} else {				
    			$this->_redirect('engagement-rings.html');    			
    		}
    		
    		
    		
    	} elseif(isset($ringbuilderData['settings'])) {
    		
    		$shape = $ringbuilderData['settings']['shape'];
    		if($shape != '') {
    			$smartUrl = Mage::getBaseUrl().'diamondsearch?shape='.$shape;
    			$this->_redirectUrl($smartUrl);
    		} else {
    			$this->_redirect('engagement-rings.html');
    		}
    		
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
    	 
    	// for restrict add to ring where shape not matching for ring and diamond
    	$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();
    	
    	if(count($ringbuilderData['diamond']) > 0 && count($ringbuilderData['steps']) > 0){			
		}else{
			$ringbuilderData['steps'] = array('sname'=>'setting');
			Mage::getSingleton('checkout/session')->setRingbuilderData($ringbuilderData);
		}
    	
    	
    	
    	
    	
    	if((isset($ringbuilderData['diamond'])) && $ringbuilderData['diamond']['shape'] != '') {
    		
    		//$_product = Mage::getModel('catalog/product')->load($productId);
    		
    		
    		//~ echo $_product->getAttributeText('center_shape_availability');
    		//~ print_r($_product->getData());
    		
				//~ if(ucfirst($ringbuilderData['diamond']['shape']) != ucfirst($_product->getAttributeText('center_shape_availability'))) {
					//~ 
					//~ Mage::helper('ringbuilder')->unsetSettingsSession(); // remove bad shape if any
					//~ 
					//~ Mage::getSingleton('core/session')->addError('Ring Shape not matching with Selected Diamond. Please select same shape for setting and diamond');
					 //~ 
					//~ $attr = Mage::getModel('catalog/product')->getResource()->getAttribute("center_shape_availability");
					//~ $shapeFilterId = "";
					//~ if ($attr->usesSource()) {
						//~ $shapeFilterId = $attr->getSource()->getOptionId($ringbuilderData['diamond']['shape']);
					//~ }
					//~ 
					//~ if($shapeFilterId != "") {
						//~ $filterUrl = Mage::getBaseUrl().'engagement-rings.html';
						//~ $this->_redirectUrl($filterUrl);
					//~ } else {
						//~ $this->_redirect('engagement-rings.html');
					//~ }
					//~ 
					//~ return;
				//~ }
				
				
    	}
    	
    	Mage::helper('ringbuilder')->setSettingsSession($sku,$productId,$categoryId);
    	 
    	//Mage::getSingleton('core/session')->addSuccess('Ring Settings added to selection');
    	
    	//Mage::app()->getFrontController()->getResponse()->setRedirect($refererUrl);
    	//Mage::app()->getResponse()->sendResponse();
    	$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();
    	
    	
		
    	if(isset($ringbuilderData['diamond']) && isset($ringbuilderData['settings'])) {
			//~ echo "<pre>";
    	//~ print_r($ringbuilderData);
    	//~ exit;
			$this->_redirect('ringbuilder/index/review',array('rapnetid'=>$ringbuilderData['diamond']['rapnetid'],'diamond'=>$ringbuilderData['diamond']['sku'],'settings'=>$ringbuilderData['settings']['sku']));
    	} elseif(isset($ringbuilderData['diamond'])) {
    		
    		//center_shape_availability
    		$attr = Mage::getModel('catalog/product')->getResource()->getAttribute("center_shape_availability");
    		$shapeFilterId = "";
    		if ($attr->usesSource()) {
    			$shapeFilterId = $attr->getSource()->getOptionId($ringbuilderData['diamond']['shape']);
    		}
    		
    		if($shapeFilterId != "") {
    			$filterUrl = Mage::getBaseUrl().'engagement-rings.html';
    			$this->_redirectUrl($filterUrl);
    		} else {
    			$this->_redirect('engagement-rings.html');
    		}
    		
    	} elseif(isset($ringbuilderData['settings'])) {
    		
    		$shape = $ringbuilderData['settings']['shape'];
    		if($shape != '') {
    			$smartUrl = Mage::getBaseUrl().'diamondsearch?shape='.$shape;
    			$this->_redirectUrl($smartUrl);
    		} else {
    			$this->_redirect('diamondsearch');
    		}
    		
    	} else {
    		Mage::app()->getFrontController()->getResponse()->setRedirect($refererUrl);
    		Mage::app()->getResponse()->sendResponse();
    	}
    	return;
    	
    }
    
    public function reviewAction() {
			
    	$refererUrl = Mage::helper('core/http')->getHttpReferer() ? Mage::helper('core/http')->getHttpReferer()  : Mage::getUrl();
    	
    	$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();
    	
    	//~ echo "<pre>";    	
    	//~ print_r($ringbuilderData);
    	//~ exit;
    	//if(((!isset($ringbuilderData['diamond']))) && $this->getRequest()->getParam('diamond') != '') {
   
    	if($this->getRequest()->getParam('diamond') != '') {
			
    		$dsku = $this->getRequest()->getParam('diamond');
    		$rapnetid = $this->getRequest()->getParam('rapnetid');    		
    		$diamond = Mage::helper('ringbuilder')->loadDiamondBySku($dsku,$rapnetid);
    		//echo "<pre>"; print_r($diamond->getData()); exit;
    		if(count($diamond) > 0) {
    			Mage::helper('ringbuilder')->setDiamondSession($dsku,$rapnetid);
    		}
    	}
    	
    	if($this->getRequest()->getParam('settings') != '') {
    		$sku = $this->getRequest()->getParam('settings');
    		
    		$productId = Mage::getSingleton('catalog/product')->getIdBySku($sku);
    		if ($productId) {
    			$product = Mage::getSingleton('catalog/product')->load($productId);
    			
    			$categoryId = '';
    			$builder = false;
    			$categoryIds = $product->getCategoryIds();
    			
    			foreach ($categoryIds as $catId) {
    				$_category = Mage::getSingleton('catalog/category')->load($catId);
    				if($_category->getRingbuilderEnabled()) {
    					$categoryId = $catId;
    					$builder = true;
    				}
    			}
    			
    			if((trim($product->getAttributeText('sub_class')) == 'Engage - SEMI') || $builder == true) {
    				Mage::helper('ringbuilder')->setSettingsSession($sku,$productId,$categoryId);
    			}
    		}
    	}
    	
    	//required to call again for updated Ringbuilder session from url params
    	$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();
    	//echo "<pre>"; print_r($ringbuilderData);  exit;
    	
    	if((!isset($ringbuilderData['diamond'])) && (!isset($ringbuilderData['settings']))) {
    		
    		//Mage::getSingleton('core/session')->addError('Please select Diamond and Ring Settings first to complete your ring');
    		
    		Mage::app()->getFrontController()->getResponse()->setRedirect($refererUrl);
    		Mage::app()->getResponse()->sendResponse();
    		return;
    	} else if(!isset($ringbuilderData['diamond'])) {
    		
    		//Mage::getSingleton('core/session')->addError('Please select Diamond to complete your ring');
    		
    		$shape = $ringbuilderData['settings']['shape'];
    		if($shape != '') {
    			$smartUrl = Mage::getBaseUrl().'diamondsearch?shape='.$shape;
    			$this->_redirectUrl($smartUrl);
    		} else {
    			$this->_redirect('diamondsearch');
    		}
    		
    		return; 
    		
    	} else if(!isset($ringbuilderData['settings'])) {
    		
    		//Mage::getSingleton('core/session')->addError('Please select Ring to complete your ring');
    		//center_shape_availability
    		$attr = Mage::getModel('catalog/product')->getResource()->getAttribute("center_shape_availability");
    		$shapeFilterId = "";
    		if ($attr->usesSource()) {
    			$shapeFilterId = $attr->getSource()->getOptionId($ringbuilderData['diamond']['shape']);
    		}
    		
    		if($shapeFilterId != "") {
    			$filterUrl = Mage::getBaseUrl().'engagement-rings.html';
    			$this->_redirectUrl($filterUrl);
    		} else {
    			$this->_redirect('engagement-rings.html');
    		}
    		
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
    
    public function clearAction() {
    	
    	$refererUrl = Mage::helper('core/http')->getHttpReferer() ? Mage::helper('core/http')->getHttpReferer()  : Mage::getUrl();
    	
    	Mage::helper('ringbuilder')->unsetDiamondSession();
    	Mage::helper('ringbuilder')->unsetSettingsSession(); 
    	
    	Mage::app()->getFrontController()->getResponse()->setRedirect($refererUrl);
    	Mage::app()->getResponse()->sendResponse();
    	return;
    }
}
