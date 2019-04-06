<?php 
class Ideal_Googlesheetimport_Model_Observer {
	
	const XML_PATH_DIAMONDSEARCH_DATA_IMPORT_ENABLED = 'cronjobs/googlesheetimport_diamondsearch_data/enabled_import';
	const XML_PATH_REGULAR_DATA_IMPORT_ENABLED = 'cronjobs/googlesheetimport_regular_data/enabled_import';
	const XML_PATH_REGULAR_DATA_UPDATE_ENABLED = 'cronjobs/googlesheetupdate_regular_data/enabled_import';
	
	public function diamondsearchDataImport() {
		
		if (!Mage::getStoreConfigFlag(self::XML_PATH_DIAMONDSEARCH_DATA_IMPORT_ENABLED)) {
			return;
		}
		
		$importCSV = Mage::helper('uploadtool')->getGoogleCsv();
		$saveCSV = Mage::helper('uploadtool')->importGoogle();
		
		//echo $importCSV['message'];
		//echo $saveCSV['message'];
		return $this;
	}
	
	public function regularDataImport() {
	
		if (!Mage::getStoreConfigFlag(self::XML_PATH_REGULAR_DATA_IMPORT_ENABLED)) {
			return;
		}

		$importCSV = Mage::helper('googlesheetimport')->getImportCSV();
		$saveCSV = Mage::helper('googlesheetimport')->importProducts();
	
		//echo $importCSV['message'];
		//echo $saveCSV['message'];
		return $this;
	}
	public function regularDataUpdate() {
	
		if (!Mage::getStoreConfigFlag(self::XML_PATH_REGULAR_DATA_UPDATE_ENABLED)) {
			return;
		}

		$importCSV = Mage::helper('googlesheetimport')->getUpdateCSV();
		$saveCSV = Mage::helper('googlesheetimport')->updateProducts();
	
		//echo $importCSV['message'];
		//echo $saveCSV['message'];
		return $this;
	}
	
}
?>
