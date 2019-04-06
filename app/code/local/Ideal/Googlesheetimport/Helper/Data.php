<?php

class Ideal_Googlesheetimport_Helper_Data extends Mage_Core_Helper_Abstract
{

	public function getImages() {
		
		$images_csv_url = Mage::getStoreConfig('googlesheetimport/settings/images_csv_url');
		
		$url = $images_csv_url;
	
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
		
		$result = curl_exec($ch);
		curl_close($ch);
		
		$lines = explode( "\n", $result );
		//echo "<pre>"; print_r($lines); exit;
		
		foreach ($lines as $httpUrl) {
			
			$httpUrl = trim(str_replace(" ","%20",$httpUrl));
			
			$imageName = basename($httpUrl);
			
			$localpath = Mage::getBaseDir()."/media/import/";
			if(!is_dir($localpath)) mkdir($localpath,0777,true);
			
			
				
				if(!file_exists($localpath.$imageName))
					copy($httpUrl, $localpath.$imageName);
			
				//echo $httpUrl . "==" . $localpath.$imageName; exit;
		}
		
		return array("success"=>1,"message"=>"Images Imported successfully.");
	}
	
	public function getImportCSV() {
		$product_csv_url = Mage::getStoreConfig('googlesheetimport/settings/product_csv_url');
		
		$url = $product_csv_url;
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
		
		$result = curl_exec($ch);
		curl_close($ch);
		
		
		$lines = explode( "\n", $result );
		$lines = array_map('str_getcsv', $lines);
		//echo "<pre>"; print_r($lines); exit;
		
		$existingProducts = Mage::getModel('catalog/product')->getCollection();
		$existingProducts->addAttributeToSelect('sku');
		$existSkus = array();
		foreach($existingProducts as $exists) {
			$existSkus[] = $exists->getSku();
		}
		//echo "<pre>"; print_r($existSkus); exit;
		
		$skuKey = NULL;
		$headerRow = $lines[0];
		//echo "<pre>"; print_r($headerRow); exit;
		foreach ($headerRow as $key => $header) {
			if($header == 'sku') {
				$skuKey = $key;
			}
		}
		//echo $skuKey; exit;
		
		/* if($skuKey == NULL) {
			return array("success"=>0,"message"=>"SKU column not found.");
		} */
		
		$path = Mage::getBaseDir("var") . DS ."import" . DS;
		$fp = fopen($path."GoogleSheetImport.csv", "w") or die("can't open file");
		
		$row = 0;
		foreach ($lines as $line) {
			
			if($row == 0) {
				fputcsv($fp, $line);
				//fputs($fp, $line);
				$row++;
				continue;
			}
			
			if(in_array($line[$skuKey], $existSkus)) {
				continue;
			}
			
			fputcsv($fp, $line);
			//fputs($fp, $line);
			$row++;
		}
		
		//fputs($fp, $result);
		fclose($fp);
		
		return array("success"=>1,"message"=>"Import CSV written successfully.");
	}
	
	public function getUpdateCSV() {
		
		$product_csv_url = Mage::getStoreConfig('googlesheetimport/settings/product_csv_url');
		
		$url = $product_csv_url;
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
		
		$result = curl_exec($ch);
		curl_close($ch);
		
		
		$lines = explode( "\n", $result );
		$lines = array_map('str_getcsv', $lines);
		//echo "<pre>"; print_r($lines); exit;
		
		$existingProducts = Mage::getModel('catalog/product')->getCollection();
		$existingProducts->addAttributeToSelect('sku');
		$existSkus = array();
		foreach($existingProducts as $exists) {
			$existSkus[] = $exists->getSku();
		}
		//echo "<pre>"; print_r($existSkus); exit;
		
		$skuKey = NULL;
		$headerRow = $lines[0];
		//echo "<pre>"; print_r($headerRow); exit;
		foreach ($headerRow as $key => $header) {
			if($header == 'sku') {
				$skuKey = $key;
			}
			
			if($header == 'status') {
				$statusKey = $key;
			}
			if($header == 'price') {
				$priceKey = $key;
			}
			if($header == 'special_price') {
				$special_priceKey = $key;
			}
			if($header == 'qty') {
				$qtyKey = $key;
			}
			if($header == 'manufacturer') {
				$manufacturerKey = $key;
			}
			if($header == 'brand') {
				$brandKey = $key;
			}
		}
		//echo $special_priceKey; exit;
		//echo $skuKey; exit;
		
		/* if($skuKey == NULL) {
			return array("success"=>0,"message"=>"SKU column not found.");
		} */
		
		$path = Mage::getBaseDir("var") . DS ."import" . DS;
		$fp = fopen($path."GoogleSheetUpdate.csv", "w") or die("can't open file");
		
		$row = 0;
		foreach ($lines as $line) {
				
			$fields = explode(",",$line);
			if($row == 0) {
				fputcsv($fp, array('sku','qty','status','price','special_price','brand','manufacturer'));
				$row++;
				continue;
			}
				
			if(!in_array($line[$skuKey], $existSkus)) {
				continue;
			}
				
			fputcsv($fp, array($line[$skuKey],$line[$qtyKey],$line[$statusKey],$line[$priceKey],$line[$special_priceKey],$line[$brandKey],$line[$manufacturerKey]));
			
			//fputs($fp, $line);
			$row++;
		}
		
		//fputs($fp, $result);
		fclose($fp);
		
		return array("success"=>1,"message"=>"Update CSV written successfully.");
		
	}
	
	public function importProducts() {
		//$_SERVER['SERVER_PORT']='443';
		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
	
		$profileId = 3; //put your profile id here
	
		$filename = Mage::app()->getRequest()->setParam('files','GoogleSheetImport.csv');
		$filename = Mage::app()->getRequest()->getParam('files'); // set the filename that is to be imported - file needs to be present in var/import directory
	
		if (!isset($filename))  {
			die("No file has been set!");
		}
		$logFileName= $filename.'.log';
		$recordCount = 0;
	
		//Mage::log("Import Started",null,$logFileName);
	
		$profile = Mage::getModel('dataflow/profile');
	
		$userModel = Mage::getModel('admin/user');
		$userModel->setUserId(0);
		Mage::getSingleton('admin/session')->setUser($userModel);
	
		if ($profileId) {
			$profile->load($profileId);
			if (!$profile->getId()) {
				//Mage::getSingleton('adminhtml/session')->addError('The profile you are trying to save no longer exists');
				return array("success"=>0 ,"message"=>"The profile you are trying to save no longer exists");
			}
		}
	
		Mage::register('current_convert_profile', $profile);
	
		$profile->run();
	
		$batchModel = Mage::getSingleton('dataflow/batch');
		if ($batchModel->getId()) {
			if ($batchModel->getAdapter()) {
				$batchId = $batchModel->getId();
				$batchImportModel = $batchModel->getBatchImportModel();
				$importIds = $batchImportModel->getIdCollection();
	
				$batchModel = Mage::getModel('dataflow/batch')->load($batchId);
				$adapter = Mage::getModel($batchModel->getAdapter());
				foreach ($importIds as $importId) {
					$recordCount++;
	
					try{
						$batchImportModel->load($importId);
						if (!$batchImportModel->getId()) {
							$errors[] = Mage::helper('dataflow')->__('Skip undefined row');
							continue;
						}
	
						$importData = $batchImportModel->getBatchData();
						try {
							$adapter->saveRow($importData);
						} catch (Exception $e) {
							echo $e->getMessage();
							continue;
						}
	
						if ($recordCount%100 == 0) {
							echo 'Processed: '.$recordCount . ''.chr(13).'\n';
						}
					} catch(Exception $ex) {
						echo 'Record# ' . $recordCount . ' - SKU = ' . $importData['sku']. ' - Error - ' . $ex->getMessage().'\n';
					}
				}
				foreach ($profile->getExceptions() as $e) {
					array("success"=>0 ,"message"=> $e->getMessage());
				}
			}
		}
		return array("success"=>1 ,"message"=>"Product Import Completed.");
	}
	
	public function updateProducts() {
	
		//$_SERVER['SERVER_PORT']='443';
		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
	
		$profileId = 3; //put your profile id here
	
		$filename = Mage::app()->getRequest()->setParam('files','GoogleSheetUpdate.csv');
		$filename = Mage::app()->getRequest()->getParam('files'); // set the filename that is to be imported - file needs to be present in var/import directory
	
		if (!isset($filename))  {
			die("No file has been set!");
		}
		$logFileName= $filename.'.log';
		$recordCount = 0;
	
		//Mage::log("Import Started",null,$logFileName);
	
		$profile = Mage::getModel('dataflow/profile');
	
		$userModel = Mage::getModel('admin/user');
		$userModel->setUserId(0);
		Mage::getSingleton('admin/session')->setUser($userModel);
	
		if ($profileId) {
			$profile->load($profileId);
			if (!$profile->getId()) {
				Mage::getSingleton('adminhtml/session')->addError('The profile you are trying to save no longer exists');
			}
		}
	
		Mage::register('current_convert_profile', $profile);
	
		$profile->run();
	
		$batchModel = Mage::getSingleton('dataflow/batch');
		if ($batchModel->getId()) {
			if ($batchModel->getAdapter()) {
				$batchId = $batchModel->getId();
				$batchImportModel = $batchModel->getBatchImportModel();
				$importIds = $batchImportModel->getIdCollection();
	
				$batchModel = Mage::getModel('dataflow/batch')->load($batchId);
				$adapter = Mage::getModel($batchModel->getAdapter());
				foreach ($importIds as $importId) {
					$recordCount++;
	
					try{
						$batchImportModel->load($importId);
						if (!$batchImportModel->getId()) {
							$errors[] = Mage::helper('dataflow')->__('Skip undefined row');
							continue;
						}
	
						$importData = $batchImportModel->getBatchData();
						try {
							$adapter->saveRow($importData);
						} catch (Exception $e) {
							echo $e->getMessage();
							continue;
						}
	
						if ($recordCount%100 == 0) {
							echo 'Processed: '.$recordCount . ''.chr(13).'\n';
						}
					} catch(Exception $ex) {
						echo 'Record# ' . $recordCount . ' - SKU = ' . $importData['sku']. ' - Error - ' . $ex->getMessage().'\n';
					}
				}
				foreach ($profile->getExceptions() as $e) {
					echo $e->getMessage();
				}
			}
		}
	
		return array("success"=>1 ,"message"=> "Product Update Completed.");
	}
}