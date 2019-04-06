<?php 
class Ideal_Ringbuilder_Model_Observer {
	
	public function addDiamondToCartEvent($observer) {
		
		$product = $observer->getEvent()->getProduct();
		$productId = $product->getId(); 
		
		$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();
		 
		if((!isset($ringbuilderData['diamond'])) && (!isset($ringbuilderData['settings']))) {
			return $this;
		}
		
		$ringProductId = $ringbuilderData['settings']['id'];
		if($productId != $ringProductId) {
			return $this;
		}
		
		$diamondSku = $ringbuilderData['diamond']['sku'];
		$this->addDiamondToCart($diamondSku);
		
		return $this;
	}
	
	public function addDiamondToCart($sku)
	{
		
		if($sku == '') {
			return;
		}
	
		$diamond = Mage::helper('ringbuilder')->loadDiamondBySku($sku);
		
		$diamondcarat = $diamond->getCarat();
		$diamondcarat = round($diamondcarat, 2);
		
		
		 $itemNum = $sku;
		 $title = $diamond->getShape();
		 $productName = $diamond->getShape() . " " . $diamondcarat . " " . $diamond->getColor() . " " . $diamond->getClarity();
		 $description = 'Shape: '.$diamond->getShape().'<br />'.'Carat:'.$diamondcarat.'<br />'.'Color:'.$diamond->getColor().'<br />'.'Clarity :'.$diamond->getClarity();
		 $price = $diamond->getTotalprice();
		 $stock_number = $diamond->getStockNumber();
		
		 $url_key = $sku. "-shape-".$diamond->getShape()."-carat-".$diamondcarat."-color-".$diamond->getColor()."-clarity-".$diamond->getClarity();
		
		$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();
		$diamondurl = $ringbuilderData['diamond']['view_url'];				
		$storeId = Mage::app()->getStore()->getId();
	
	     
		$id = Mage::getModel('catalog/product')->getIdBySku($itemNum);
		if ($id) {
			$product = Mage::getModel('catalog/product')->load($id);
			//echo count($product->getMediaGalleryImages()); exit;
			//echo "<pre>"; print_r($product->getMediaGalleryImages()); exit;
		} else {
			$product = Mage::getModel('catalog/product');
		}
	
		$product->setTypeId('simple');  //
		$product->setTaxClassId(0); //none
		$product->setWebsiteIds(array(1));  // store id
		$product->setAttributeSetId(9); //Videos Attribute Set Id
	
		$product->setSku($itemNum);
		$product->setVendorSku($stock_number);
		$product->setName($productName);
		$product->setDescription($description);
		$product->setPrice($price);
		$product->setShortDescription(ereg_replace("\n","",$description));
		$product->setWeight(0);
		$product->setStatus(1); //enabled
		$product->setVisibilty(4); //catalog and search
		$product->setMetaDescription(ereg_replace("\n","",$description));
		$product->setMetaTitle($productName);
		$product->setUrlKey($url_key); // make proper urls for product
	    $product->setDiamondurl($diamondurl);
		if(Mage::getStoreConfig("diamondsearch/general_settings/add_cart_diamond_images")==1)
		{
			$gallery_img = '/shapes_white/'.strtolower($title).'.jpg';
		}
		else
		{
			$gallery_img = '/shape/'.strtolower($title).'.jpg';
		}
	
		if (!$id) {
			$product->addImageToMediaGallery(Mage::getBaseDir('media') . DS . 'import' . $gallery_img, array('small_image','thumbnail','image'), false,true);
		}
	
		$product->save();
	
		$stockItem = Mage::getModel('cataloginventory/stock_item');
		$stockItem->loadByProduct($product->getId());
	
		if (! $stockItem->getId()) {
			$stockItem->setProductId($product->getId())->setStockId(1);
		}
	
		$stockItem->setData('is_in_stock',1);
		$stockItem->save();
	
		$stockItem->loadByProduct($product->getId());
		$stockItem->setData('qty', 1);
		$stockItem->save();
	
		$id = $product->getId();
		
		$_product = Mage::getModel('catalog/product')->load($id);
		$cart = Mage::getModel('checkout/cart');
		$cart->init();
		$cart->addProduct($_product, array('qty' => 1));
		$cart->save();
		Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
		
		//Mage::getSingleton('checkout/session')->addSuccess('Diamond #'.$sku.' added to cart successfully.');
		//Mage::getSingleton('checkout/session')->unsRingbuilderData();
		
		return;
	}
}
