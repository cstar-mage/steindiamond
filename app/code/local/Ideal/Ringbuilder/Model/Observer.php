<?php 
class Ideal_Ringbuilder_Model_Observer {
	
	const XML_PATH_DIAMONDNOTIFICATION_RECIPIENT  = 'sales_email/diamondnotification/recipient_email';
	const XML_PATH_DIAMONDNOTIFICATION_SENDER     = 'sales_email/diamondnotification/sender_email_identity';
	const XML_PATH_DIAMONDNOTIFICATION_TEMPLATE   = 'sales_email/diamondnotification/email_template';
	const XML_PATH_DIAMONDNOTIFICATION_ENABLED    = 'sales_email/diamondnotification/enabled';
	
	public function addDiamondToCartEvent($observer) {
		
		try{
		
				$product = $observer->getEvent()->getProduct();
				
				$productId = $product->getId(); 
				
				
				$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();
				
				
				
				$rapnetid = $ringbuilderData['diamond']['rapnetid'];
				
					
				if((!isset($ringbuilderData['diamond'])) && (!isset($ringbuilderData['settings']))) {
					return $this;
				}
				
				$ringProductId = $ringbuilderData['settings']['id'];
				if($productId != $ringProductId) {
					return $this;
				}
				
				$diamondSku = $ringbuilderData['diamond']['sku'];
				
				$diamond = Mage::helper('ringbuilder')->loadDiamondBySku($diamondSku , $rapnetid);
				
				//~ echo "<pre>";
				//~ print_r($diamond->getData());
				
				
				$carat = number_format($diamond->getCarat(), 2, '.', '');
				$cut = $diamond->getCut();
				if($cut){
					if($cut == "EX"){
						$cut = "Excellent";
					}
					elseif ($cut == "VG") {
						$cut = "Very Good";
					}
					elseif ($cut == "G") {
						$cut = "Good";
					}
					elseif ($cut == "F") {
						$cut = "Fair";
					}
				}
				
				if($diamond->getShape() ==  "Round"){
					$diamondproductName = "GIA Certified ".$carat . " " . $diamond->getColor() . " " . $diamond->getClarity() ." " . $diamond->getCut() . " " . "Cut " . $diamond->getShape() . " Diamond " ;
					
					$diamondOption = $carat . " Carat, " . $diamond->getShape() . ", " . $diamond->getCut() . " Cut, " . $diamond->getColor() . " Color, " . $diamond->getClarity() . " Clarity";
				}
				else
				{
					$diamondproductName = "GIA Certified ".$carat . " " . $diamond->getColor() . " " . $diamond->getClarity() . " " . $diamond->getShape() . " Diamond " ;
					$diamondOption = $carat . " Carat, " . $diamond->getShape() . ", " . $diamond->getColor() . " Color, " . $diamond->getClarity() . " Clarity";
				}
					
				
				//TASK: Create Restriction that prevents user from only purchasing a Diamond 
				/* added as custom option */
				$item = $observer->getQuoteItem();
				
				//~ echo "<pre>";
				//~ print_r($item->getData());
				//~ exit;
				
				$additionalOptions[] = array(
						'label' => "Diamond",
						'value' => $diamondOption,
				);
				$additionalOptions[] = array(
						'label' => "Diamond SKU",
						'value' => $diamondSku,
				);
				
				$additionalOptions[] = array(
						'label' => "Diamond Price",
						'value' => $diamond->getTotalprice(),
				);

				$item->addOption(array(
						'code' => 'additional_options',
						'value' => serialize($additionalOptions)
				));
				
				//~ echo $diamondOption;
				//~ echo "<Br>";
				//~ echo $diamondSku;
				//~ echo "<Br>";
				//~ echo Mage::helper('core')->currency($diamond->getTotalprice(), true, false);
				//~ echo "<Br>";
				//~ echo serialize($additionalOptions);		
				//~ exit;
				//~ 
				
				/* added as custom option */
				
		
				$diamondprice = preg_replace('/[^0-9\-]/','',$diamond->getTotalprice());

							
				$new_price = $item->getProduct()->getFinalPrice() + $diamondprice;
				$item->setOriginalCustomPrice($new_price);
				$item->setCustomPrice($new_price);
				$item->getProduct()->setIsSuperMode(true);

			
		
			//	$item->save();
				
					
				return $this;
		 
				//old code commented to create diamond product
				//$this->addDiamondToCart($diamondSku);
				//return $this;
		}catch (Exception $e) {
			echo 'addDiamondToCartEvent --- Caught exception: ',  $e->getMessage(), "\n";
			exit;
		}
	}
	
	public function updateRingbuilderProductName($observer) {
		
		try{ 
			
				$ringbuilderData = Mage::getSingleton('checkout/session')->getRingbuilderData();
					
				if((!isset($ringbuilderData['diamond'])) && (!isset($ringbuilderData['settings']))) {
					return $this;
				}
				
				$ringProductId = $ringbuilderData['settings']['id'];
				
				
				$diamondSku = $ringbuilderData['diamond']['sku'];
				$diamond = Mage::helper('ringbuilder')->loadDiamondBySku($diamondSku);
				
				$carat = number_format($diamond->getCarat(), 2, '.', '');
				$cut = $diamond->getCut();
				if($cut){
					if($cut == "EX"){
						$cut = "Excellent";
					}
					elseif ($cut == "VG") {
						$cut = "Very Good";
					}
					elseif ($cut == "G") {
						$cut = "Good";
					}
					elseif ($cut == "F") {
						$cut = "Fair";
					}
				}
				
				if($diamond->getShape() ==  "Round"){
					$diamondproductName = "GIA Certified ".$carat . " " . $diamond->getColor() . " " . $diamond->getClarity() ." " . $diamond->getCut() . " " . "Cut " . $diamond->getShape() . " Diamond " ;
				}
				else
				{
					$diamondproductName = "GIA Certified ".$carat . " " . $diamond->getColor() . " " . $diamond->getClarity() . " " . $diamond->getShape() . " Diamond " ;
				}
				
				$event = $observer->getEvent();
				$cart = $event->getCart();
				$items= $cart->getQuote()->getAllItems();
				
				foreach ($items as $item) {
					$productId = $item->getProduct()->getId();
					if($productId != $ringProductId) {
						continue;
					}
					
					if($diamondproductName != ""){
						$item->setName($item->getName() . " With a " . $diamondproductName);
					}
				}
				
				return $this;
		}catch (Exception $e) {
			echo 'updateRingbuilderProductName ----- Caught exception: ',  $e->getMessage(), "\n";
			exit;
		}
	}
	
	public function salesConvertQuoteItemToOrderItem(Varien_Event_Observer $observer)
	{
		$quoteItem = $observer->getItem();
		if ($additionalOptions = $quoteItem->getOptionByCode('additional_options')) {
			$orderItem = $observer->getOrderItem();
			$options = $orderItem->getProductOptions();
			$options['additional_options'] = unserialize($additionalOptions->getValue());
			$orderItem->setProductOptions($options);
		}
	}
	
	public function checkoutCartProductAddAfter(Varien_Event_Observer $observer)
	{
		// for reorder support
		
		try{
		
			$action = Mage::app()->getFrontController()->getAction();
			
			
			if ($action->getFullActionName() == 'sales_order_reorder')
			{
				$item = $observer->getQuoteItem();
				$buyInfo = $item->getBuyRequest();
				if ($options = $buyInfo->getExtraOptions())
				{
					$additionalOptions = array();
					if ($additionalOption = $item->getOptionByCode('additional_options'))
					{
						$additionalOptions = (array) unserialize($additionalOption->getValue());
					}
					foreach ($options as $key => $value)
					{
						$additionalOptions[] = array(
								'label' => $key,
								'value' => $value,
						);
					}
					$item->addOption(array(
							'code' => 'additional_options',
							'value' => serialize($additionalOptions)
					));
				}
			}
		}catch (Exception $e) {
			echo 'checkoutCartProductAddAfter ---- Caught exception: ',  $e->getMessage(), "\n";
			exit;
		}

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
	
	public function diamondNotificationEmail($observer) {
		
		if( !Mage::getStoreConfigFlag(self::XML_PATH_DIAMONDNOTIFICATION_ENABLED) ) {
			return $this;
		}
		$order = $observer->getOrder();
		//$order = Mage::getModel('sales/order')->load(158); //test
		
		$items = $order->getAllItems();
		
		$diamondSkus = array();
		foreach ($items as $item) {
			$options = $item->getProductOptions();
			
			if(isset($options['additional_options']) && count($options['additional_options']) > 0) {
				foreach ($options['additional_options'] as $opt) {
					if($opt['label'] == 'Diamond SKU') {
						$diamondSkus[] = $opt['value'];
					}
				}
			}
		}
		
		if(!count($diamondSkus)) {
			return $this;
		}
		
		try {
			$translate = Mage::getSingleton('core/translate');
			/* @var $translate Mage_Core_Model_Translate */
			$translate->setTranslateInline(false);
			
			$paymentBlock = Mage::helper('payment')->getInfoBlock($order->getPayment())
							->setIsSecureMode(true);
			$paymentBlock->getMethod()->setStore($storeId);
			$paymentBlockHtml = $paymentBlock->toHtml();
			
			foreach ($diamondSkus as $diamondSku) {
				$diamond = Mage::helper('ringbuilder')->loadDiamondBySku($diamondSku);
				if(count($diamond->getData())) {
					//echo "<pre>"; print_r($diamond->getData()); echo "</pre>";
					$diamond->setData('cost',Mage::helper('core')->currency($diamond->getCost(), true, false));
					$diamond->setData('totalprice',Mage::helper('core')->currency($diamond->getTotalprice(), true, false));
					$diamond->setData('price_per_carat',Mage::helper('core')->currency($diamond->getPricePerCarat(), true, false));
					
					$diamond->setData('carat',number_format($diamond->getCarat(), 2, '.', ''));
					$diamond->setData('tabl',number_format($diamond->getTabl(), 1, '.', ''));
					$diamond->setData('depth',number_format($diamond->getDepth(), 1, '.', ''));
					
					//save the information in table
					$diamondorders = Mage::getModel('ringbuilder/diamondorders');
					$diamondorders->setData($diamond->getData());
					$diamondorders->setData('increment_id',$order->getIncrementId());
					$diamondorders->setData('order_id',$order->getId());
					$diamondorders->save();
					
					//Send notification to diamond vendor email
					$vendors = Mage::getModel('ringbuilder/diamondvendors')->getCollection()
								->addFieldToFilter('name',$diamond->getOwner())->getFirstItem();
					$vendorData = $vendors->getData();
					$vendorName = '';
					//echo "<pre>"; print_r($vendorData); exit;
					if(count($vendorData) && $vendorData['email'] != '') {
						
						$vendorName = $vendorData['contact_person'];
						
						$mailTemplate = Mage::getModel('core/email_template');
						/* @var $mailTemplate Mage_Core_Model_Email_Template */
						$mailTemplate->setDesignConfig(array('area' => 'frontend'))
							->setReplyTo($order->getEmail())
							->sendTransactional(
									Mage::getStoreConfig(self::XML_PATH_DIAMONDNOTIFICATION_TEMPLATE),
									Mage::getStoreConfig(self::XML_PATH_DIAMONDNOTIFICATION_SENDER),
									$vendorData['email'],
									null,
									array('diamond' => $diamond,'vendor_name'=>$vendorName,'order'=>$order, 'payment_html' => $paymentBlockHtml)
							);
							
						if (!$mailTemplate->getSentSuccess()) {
							throw new Exception();
						}
					}
					
					//send notification to email from configuration
					$recepients = explode(',',Mage::getStoreConfig(self::XML_PATH_DIAMONDNOTIFICATION_RECIPIENT));
					
					foreach ($recepients as $recepient) {
						$mailTemplate = Mage::getModel('core/email_template');
						/* @var $mailTemplate Mage_Core_Model_Email_Template */
						$mailTemplate->setDesignConfig(array('area' => 'frontend'))
									->setReplyTo($order->getEmail())
									->sendTransactional(
											Mage::getStoreConfig(self::XML_PATH_DIAMONDNOTIFICATION_TEMPLATE),
											Mage::getStoreConfig(self::XML_PATH_DIAMONDNOTIFICATION_SENDER),
											$recepient,
											null,
											array('diamond' => $diamond,'vendor_name'=>$vendorName,'order'=>$order, 'payment_html' => $paymentBlockHtml)
									);
							
						if (!$mailTemplate->getSentSuccess()) {
							throw new Exception();
						}
					}
				}
			}
			
			$translate->setTranslateInline(true);
			
		} catch (Exception $e) {
                $translate->setTranslateInline(true);

                Mage::log('diamondnotification_email_error: '.$e->getMessage());
                return $this;
        }
		
		//exit;
		return $this;
	}
}
