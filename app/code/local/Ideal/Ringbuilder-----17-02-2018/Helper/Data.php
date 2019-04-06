<?php 
class Ideal_Ringbuilder_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function setDiamondSession($sku) {
		
		
		$diamond = $this->loadDiamondBySku($sku);
		//echo "<pre>"; print_r($diamond->getData()); exit;
		
		$viewUrl = $this->viewDiamondUrl($diamond);
		$price = $diamond->getTotalprice();
		
		$refererUrl = Mage::helper('core/http')->getHttpReferer() ? Mage::helper('core/http')->getHttpReferer()  : Mage::getUrl();
		//$changeUrl = $refererUrl;
		$changeUrl = Mage::getUrl().'diamondsearch';

		$diamondImage = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."import/shape/".strtolower($diamond->getShape()).".jpg";
		
		$ringbuilderData = array();
		$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();
		$ringbuilderData['diamond'] = array('sku'=>$sku,'shape'=>$diamond->getShape(),'price'=>$price,'view_url'=>$viewUrl,'change_url'=>$changeUrl,'display_image'=>$diamondImage);
		
		Mage::getSingleton('checkout/session')->setRingbuilderData($ringbuilderData);
		
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
		
		//$_product = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);
		$_product = Mage::getModel('catalog/product')->load($productId);
		$price = $_product->getFinalPrice();
		
		$refererUrl = Mage::helper('core/http')->getHttpReferer() ? Mage::helper('core/http')->getHttpReferer()  : Mage::getUrl();
		//$viewUrl = $refererUrl;
		$viewUrl = $_product->getProductUrl();
		//echo $viewUrl; exit;
		$changeUrl = Mage::getUrl('build-an-engagement-ring');
		$productImage = "";
		
		if($_product->getImage() && $_product->getImage() != "no_selection") {
			$productImage = Mage::helper('catalog/image')->init($_product, 'image')->resize(250)->__toString();
			//__toString() is reuired when ImageCDN used
		}
		//echo $productImage; exit;
		
		$ringbuilderData = array();
		$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();
		$ringbuilderData['settings'] = array('sku'=>$sku,'id'=>$productId,'category'=>$categoryId,'price'=>$price,'view_url'=>$viewUrl,'change_url'=>$changeUrl,'display_image'=>$productImage,'shape'=>$_product->getAttributeText('center_shape_availability'));
			
		Mage::getSingleton('checkout/session')->setRingbuilderData($ringbuilderData);
	
		return;
	}
	
	public function loadDiamondBySku($sku) {
		
		$diamond = Mage::getSingleton('diamondsearch/diamondsearch')->getCollection()
					->addFieldToFilter('lotno',$sku)
					->getFirstItem();
		
		if(count($diamond->getData()) == 0) { // compatibility with stock_number
			$diamond = Mage::getSingleton('diamondsearch/diamondsearch')->getCollection()
						->addFieldToFilter('id',$sku)
						->getFirstItem();
		}
		
		return $diamond;
	}
	
	public function viewDiamondUrl($diamond) {
		$stone = $diamond->getData();
		$viewUrl = Mage::helper("diamondsearch")->getDiamondLink($stone['carat'],$stone['shape'],$stone['cut'],$stone['certificate'],$stone['color'],$stone['clarity'],$stone['lotno'],$stone['cert_number']);
		//$viewUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)."diamondsearch/index/view/id/".$stone['id'].DS.$stone['carat']."-".$stone['shape']."-".$stone['cut']."-".$stone['certificate']."-".$color."-".$stone['clarity']."-diamond-stock-".$stone['lotno']."-cert-".$stone['cert_number'];
		//Exampple: http://www.rockher.com/diamondsearch/index/view/id/6333/details/0.91-Heart-N-GIA-D-SI1-diamond-stock-ZH14-34-cert-2166031764
		return $viewUrl;
	}
}
