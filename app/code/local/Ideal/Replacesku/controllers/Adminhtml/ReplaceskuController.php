<?php

class Ideal_Replacesku_Adminhtml_ReplaceskuController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('replacesku/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('replacesku/replacesku')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('replacesku_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('replacesku/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('replacesku/adminhtml_replacesku_edit'))
				->_addLeft($this->getLayout()->createBlock('replacesku/adminhtml_replacesku_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('replacesku')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			if(isset($_FILES["filename"]["name"]) && $_FILES["filename"]["name"] != "") {
				try {	
					$uploader = new Varien_File_Uploader("filename");
					
	           		$uploader->setAllowedExtensions(array("csv"));
					$uploader->setAllowRenameFiles(false);
					$uploader->setFilesDispersion(false);
							
					$path = Mage::getBaseDir("var") . DS ."import" . DS;
					//$uploader->save($path, $_FILES["filename"]["name"] );
					$uploader->save($path, "replce_product_sku.csv" );
					Mage::getSingleton("adminhtml/session")->addSuccess("CSV file Uploaded successfully.");
				} 
				catch (Exception $e){
		      			Mage::getSingleton("adminhtml/session")->addError(Mage::helper("adminhtml")->__("File not uploaded"));
						$this->_redirect("*/*/new");
						return;
		        }
				$this->_redirect("*/*/new");
            	return;
			}
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('replacesku')->__('Unable to find file to upload'));
		$this->_redirect('*/*/new');
	}
	
	
	public function insertinpopupAction()
	{
		
		$updates_file="./var/import/replce_product_sku.csv";
		$sku_entry=array();
		
		$updates_handle=fopen($updates_file, 'r');
		
		$row = $su = 0;
		if($updates_handle)
		{
			while($sku_entry=fgetcsv($updates_handle, 1000, ","))
			{
				if($row != 0)
				{
					//echo "<pre>";print_r($sku_entry);die;
					$old_sku=$sku_entry[0];
					$new_sku=$sku_entry[1];
					try {
						$get_item = Mage::getModel('catalog/product')->loadByAttribute('sku', $old_sku);
						if ($get_item) {
							$get_item->setSku($new_sku)->save();
							$su++;
						} else {
							Mage::getSingleton('adminhtml/session')->addError(Mage::helper('replacesku')->__('Unable to find value to update. SKU : '.$old_sku));
						}
					} catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError(Mage::helper("adminhtml")->__("Cannot retrieve product: ".$e->getMessage()));
						$this->_redirect("*/*/new");
						return;
					}
				}
				$row++;	
			}
		}
		fclose($updates_handle);
		if($su > 0){
			Mage::getSingleton("adminhtml/session")->addSuccess($su . " SKU(s) Successfully Updated.");
		}
		if($su == 0) {
			Mage::getSingleton("adminhtml/session")->addError("CSV data not found. Please make sure csv has two columns with header row(sku,new_sku)");
		}
		$this->_redirect("*/*/new");
        return;
	} 
	
    /*public function exportCsvAction()
    {
        $fileName   = 'replacesku.csv';
        $content    = $this->getLayout()->createBlock('replacesku/adminhtml_replacesku_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'replacesku.xml';
        $content    = $this->getLayout()->createBlock('replacesku/adminhtml_replacesku_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }*/

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
    protected function _isAllowed()
    {
    	return true;
		//return Mage::getSingleton('admin/session')->isAllowed('catalog/replacesku');
    }
}
