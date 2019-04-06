<?php 
class Ideal_Ringbuilder_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function setDiamondSession($sku,$rapnetid) {
		
		$diamond = $this->loadDiamondBySku($sku , $rapnetid);
		//echo "<pre>"; print_r($diamond->getData()); exit;  
		
		$dia_name = number_format((float)$diamond->getCarat(), 2, '.', '')." Carat ".$diamond->getShape();
		$viewUrl = $this->viewDiamondUrl($diamond);
		$urlid = $rapnetid;
		$price = $diamond->getTotalprice();
		
		
		
		$refererUrl = Mage::helper('core/http')->getHttpReferer() ? Mage::helper('core/http')->getHttpReferer()  : Mage::getUrl();
		//$changeUrl = $refererUrl;
		$changeUrl = Mage::getUrl().'diamondsearch';

		$diamondImage = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."import/shape/".strtolower($diamond->getShape()).".jpg";
		
		$ringbuilderData = array();
		$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();
		$ringbuilderData['diamond'] = array('name'=>$dia_name,'sku'=>$sku,'rapnetid'=>$urlid,'shape'=>$diamond->getShape(),'price'=>$price,'view_url'=>$viewUrl,'change_url'=>$changeUrl,'display_image'=>$diamondImage);
		//~ echo "<pre>";
		//~ print_r($ringbuilderData);
		//~ exit;
		Mage::getSingleton('checkout/session')->setRingbuilderData($ringbuilderData);
		
		//~ $ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();
		//~ echo "<pre>";
		//~ print_r($ringbuilderData);
		//~ exit;
		
		
		return;
	}   

	public function unsetDiamondSession() {
		$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();
		unset($ringbuilderData['diamond']);
		Mage::getSingleton('checkout/session')->setRingbuilderData($ringbuilderData);
		return;
	}
	
	public function unsetSettingsSession() {
		$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();
		unset($ringbuilderData['settings']);
		Mage::getSingleton('checkout/session')->setRingbuilderData($ringbuilderData);
		return;
	}
	
	public function setSettingsSession($sku,$productId,$categoryId) {
		//~ echo $sku;
		//~ echo "<br>";
		//~ echo $productId;
		//~ echo "<br>"; 
		//~ echo $categoryId;
		//~ exit;
		//$_product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);
		$_product = Mage::getModel('catalog/product')->load($productId);
		$price = $_product->getFinalPrice();
		
		$refererUrl = Mage::helper('core/http')->getHttpReferer() ? Mage::helper('core/http')->getHttpReferer()  : Mage::getUrl();
		//$viewUrl = $refererUrl;
		$proname = $_product->getName();
		$viewUrl = $_product->getProductUrl();
		//echo $viewUrl; exit;
		$changeUrl = Mage::getUrl('engagement-rings.html');
		$productImage = "";
		
		if($_product->getImage() && $_product->getImage() != "no_selection") {
			$productImage = Mage::helper('catalog/image')->init($_product, 'image')->resize(250)->__toString();
			//__toString() is reuired when ImageCDN used
		}
		//echo $productImage; exit;
		
		$ringbuilderData = array();
		$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();
		$ringbuilderData['settings'] = array('name'=>$proname,'sku'=>$sku,'id'=>$productId,'category'=>$categoryId,'price'=>$price,'view_url'=>$viewUrl,'change_url'=>$changeUrl,'display_image'=>$productImage,'shape'=>$_product->getAttributeText('center_shape_availability'));
			
		Mage::getSingleton('checkout/session')->setRingbuilderData($ringbuilderData);
		
		$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();
		
		//~ echo "<pre>";
		//~ print_r($ringbuilderData);
		//~ exit;
		return;
	}
	
	public function loadDiamondBySku($sku,$rapnetid) {
		
		//~ echo $sku;
		//~ echo "<br>";
		//~ echo $rapnetid;
		//~ exit;
		
		
		$diamond = Mage::getSingleton('diamondsearch/diamondsearch')->getCollection()
					->addFieldToFilter('lotno',$sku)
					->getFirstItem();
		
		
		if(count($diamond->getData()) == 0) { // compatibility with stock_number
			$diamond = Mage::getSingleton('diamondsearch/diamondsearch')->getCollection()
						->addFieldToFilter('id',$sku)
						->getFirstItem();
		}
		
		
		
		if (empty($diamond->getData())) {
			
			$id = $rapnetid;	

			$user = Mage::helper('uploadtool')->diamondSettings('rapnet_username');
			$passwd = Mage::helper('uploadtool')->diamondSettings('rapnet_password');
			if($user == "" || $passwd == ""){
				return array("success"=>0,"message"=>"Please Enter valid RapNet Login Detail to settings");
			}
			$request_json = array();
			$request_json["request"]["header"]["username"] = $user;
			$request_json["request"]["header"]["password"] = $passwd;
			$request_json["request"]["body"]["diamond_id"]=(int)$id;
			$response = Mage::helper('diamondsearch/rapnet')->getRapnetInstantInvSingleDiamondAPIResponse($user,$passwd,json_encode($request_json));
			$diamond = Mage::helper("diamondsearch")->getRapnetSingleDiamondArray($response, $_REQUEST["is_fancy"], $_REQUEST["is_specialshape"]);


					
			
			$postObject = new Varien_Object();
			$postObject->setData($diamond);
					
			
			return $postObject;
			
		}else{
			
			return $diamond;	
			
		}
		
		
		
		 
		
	//	return $postObject;
	}
	
	public function viewDiamondUrl($diamond) {
		$stone = $diamond->getData();
		$viewUrl = Mage::helper("diamondsearch")->getDiamondLink($stone['carat'],$stone['shape'],$stone['cut'],$stone['certificate'],$stone['color'],$stone['clarity'],$stone['lotno'],$stone['cert_number']);
		//$viewUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)."diamondsearch/index/view/id/".$stone['id'].DS.$stone['carat']."-".$stone['shape']."-".$stone['cut']."-".$stone['certificate']."-".$color."-".$stone['clarity']."-diamond-stock-".$stone['lotno']."-cert-".$stone['cert_number'];
		//Exampple: http://www.rockher.com/diamondsearch/index/view/id/6333/details/0.91-Heart-N-GIA-D-SI1-diamond-stock-ZH14-34-cert-2166031764
		return $viewUrl;
	}
}
