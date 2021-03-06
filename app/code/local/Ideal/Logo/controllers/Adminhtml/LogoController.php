<?php

class Ideal_Logo_Adminhtml_LogoController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('logo/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		
		
		$this->loadLayout();
		$this->_setActiveMenu('logo/items');
		
		$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));
		
		$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
		
		$this->_addContent($this->getLayout()->createBlock('logo/adminhtml_logo_edit'))
		->_addLeft($this->getLayout()->createBlock('logo/adminhtml_logo_edit_tabs'));
		
		$this->renderLayout();

	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {

		try {
			
			if(isset($_FILES['design_header_logo_src']['name']) && $_FILES['design_header_logo_src']['name'] != '') {
				try {
					/* Starting upload */
					$uploader = new Varien_File_Uploader('design_header_logo_src');
						
					// Any extention would work
					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
						
					// Set the file upload mode
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
						
					//echo Mage::getStoreConfig('design/package/name'); exit;
					
					// We set media as the upload dir
					$path = Mage::getDesign()->getSkinBaseDir(array('_area'=>'frontend','_package'=>'evolved','_theme'=>'default'));
					
					$uploader->save($path."/images/", $_FILES['design_header_logo_src']['name'] );
					
					$config = new Mage_Core_Model_Config();
					$config ->saveConfig('design/header/logo_src', "images/".$_FILES['design_header_logo_src']['name'] , 'default', 0);
					
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					$this->_redirect('logo/adminhtml_logo/new');
					return;
				}
			}
			
			
			if(isset($_FILES['design_header_logo_src_small']['name']) && $_FILES['design_header_logo_src_small']['name'] != '') {
				try {
					/* Starting upload */
					$uploader = new Varien_File_Uploader('design_header_logo_src_small');
			
					// Any extention would work
					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
			
					// Set the file upload mode
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
			
					//echo Mage::getStoreConfig('design/package/name'); exit;
						
					// We set media as the upload dir
					$path = Mage::getDesign()->getSkinBaseDir(array('_area'=>'frontend','_package'=>'evolved','_theme'=>'default'));
						
					$uploader->save($path."/images/", $_FILES['design_header_logo_src_small']['name'] );
						
					$config = new Mage_Core_Model_Config();
					$config ->saveConfig('design/header/logo_src_small', "images/".$_FILES['design_header_logo_src_small']['name'] , 'default', 0);
						
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					$this->_redirect('logo/adminhtml_logo/new');
					return;
				}
			}
			
			if(isset($_FILES['design_head_shortcut_icon']['name']) && $_FILES['design_head_shortcut_icon']['name'] != '') {
				try {
					/* Starting upload */
					$uploader = new Varien_File_Uploader('design_head_shortcut_icon');
			
					// Any extention would work
					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png','ico'));
					$uploader->setAllowRenameFiles(false);
			
					// Set the file upload mode
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
			
					//echo Mage::getStoreConfig('design/package/name'); exit;
						
					$path = Mage::getBaseDir('media')."/favicon/default/";
					if(!is_dir($path)) {
						mkdir($path);
					}
					$uploader->save($path, $_FILES['design_head_shortcut_icon']['name'] );
					
					copy($path.$_FILES['design_head_shortcut_icon']['name'],Mage::getBaseDir().'/favicon.ico');
					copy($path.$_FILES['design_head_shortcut_icon']['name'],Mage::getBaseDir().'/skin/adminhtml/default/default/favicon.ico');
					copy($path.$_FILES['design_head_shortcut_icon']['name'],Mage::getBaseDir().'/skin/frontend/evolved/default/favicon.ico');
					copy($path.$_FILES['design_head_shortcut_icon']['name'],Mage::getBaseDir().'/skin/frontend/base/default/favicon.ico');
					
					$config = new Mage_Core_Model_Config();
					$config ->saveConfig('design/head/shortcut_icon', "default/".$_FILES['design_head_shortcut_icon']['name'] , 'default', 0);
						
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					$this->_redirect('logo/adminhtml_logo/new');
					return;
				}
			}
			
			if(isset($_FILES['email_logo_src']['name']) && $_FILES['email_logo_src']['name'] != '') {
				try {
					/* Starting upload */
					$uploader = new Varien_File_Uploader('email_logo_src');
			
					// Any extention would work
					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
			
					// Set the file upload mode
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
			
					// We set media as the upload dir
					//$path = Mage::getDesign()->getSkinBaseDir(array('_area'=>'frontend'));
					//echo $path; exit;
						
					$media_path = Mage::getBaseDir('media');
					//echo $media_path."/email/logo/default/";
					//exit;
					//$uploader->save($path."/images/", $_FILES['email_logo_src']['name'] );
					$uploader->save($media_path."/email/logo/default/", $_FILES['email_logo_src']['name'] );
						
					$config = new Mage_Core_Model_Config();
					//$config ->saveConfig('design/email/logo_src', "images/".$_FILES['email_logo_src']['name'] , 'default', 0);
					$config ->saveConfig('design/email/logo', "default/".$_FILES['email_logo_src']['name'] , 'default', 0);
						
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					$this->_redirect('logo/adminhtml_logo/new');
					return;
				}
			}
			
			
			if(isset($_FILES['baseimg_placeholder_src']['name']) && $_FILES['baseimg_placeholder_src']['name'] != '') {
				try {
					/* Starting upload */
					$uploader = new Varien_File_Uploader('baseimg_placeholder_src');
						
					// Any extention would work
					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
						
					// Set the file upload mode
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
						
					// We set media as the upload dir
					//$path = Mage::getDesign()->getSkinBaseDir(array('_area'=>'frontend'));
					//echo $path; exit;
			
					$media_path = Mage::getBaseDir('media');
					//echo $media_path."/email/logo/default/";
					//exit;
					//$uploader->save($path."/images/", $_FILES['email_logo_src']['name'] );
					$uploader->save($media_path."/catalog/product/placeholder/default/", $_FILES['baseimg_placeholder_src']['name'] );
			
					$skinPath = Mage::getDesign()->getSkinBaseDir(array('_area'=>'frontend','_package'=>'evolved','_theme'=>'default'));
					$ext = pathinfo($_FILES['baseimg_placeholder_src']['name'], PATHINFO_EXTENSION);
					copy($media_path."/catalog/product/placeholder/default/".$_FILES['baseimg_placeholder_src']['name'] , $skinPath. "/images/catalog/product/placeholder/image.".$ext );
					copy($media_path."/catalog/product/placeholder/default/".$_FILES['baseimg_placeholder_src']['name'] , Mage::getBaseDir()."/skin/adminhtml/default/default/bl/customgrid/images/catalog/product/placeholder.jpg");
					
					$config = new Mage_Core_Model_Config();
					//$config ->saveConfig('design/email/logo_src', "images/".$_FILES['email_logo_src']['name'] , 'default', 0);
					$config ->saveConfig('catalog/placeholder/image_placeholder', "default/".$_FILES['baseimg_placeholder_src']['name'] , 'default', 0);
			
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					$this->_redirect('logo/adminhtml_logo/new');
					return;
				}
			}
			
			if(isset($_FILES['smallimage_placeholder_src']['name']) && $_FILES['smallimage_placeholder_src']['name'] != '') {
				try {
					/* Starting upload */
					$uploader = new Varien_File_Uploader('smallimage_placeholder_src');
						
					// Any extention would work
					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
						
					// Set the file upload mode
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
						
					// We set media as the upload dir
					//$path = Mage::getDesign()->getSkinBaseDir(array('_area'=>'frontend'));
					//echo $path; exit;
			
					$media_path = Mage::getBaseDir('media');
					//echo $media_path."/email/logo/default/";
					//exit;
					//$uploader->save($path."/images/", $_FILES['email_logo_src']['name'] );
					$uploader->save($media_path."/catalog/product/placeholder/default/", $_FILES['smallimage_placeholder_src']['name'] );
			
					$skinPath = Mage::getDesign()->getSkinBaseDir(array('_area'=>'frontend','_package'=>'evolved','_theme'=>'default'));
					$ext = pathinfo($_FILES['smallimage_placeholder_src']['name'], PATHINFO_EXTENSION);
					copy($media_path."/catalog/product/placeholder/default/".$_FILES['smallimage_placeholder_src']['name'] , $skinPath. "/images/catalog/product/placeholder/small_image.".$ext );
					
					
					$config = new Mage_Core_Model_Config();
					//$config ->saveConfig('design/email/logo_src', "images/".$_FILES['email_logo_src']['name'] , 'default', 0);
					$config ->saveConfig('catalog/placeholder/small_image_placeholder', "default/".$_FILES['smallimage_placeholder_src']['name'] , 'default', 0);
			
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					$this->_redirect('logo/adminhtml_logo/new');
					return;
				}
			}
			
			if(isset($_FILES['thumbnail_placeholder_src']['name']) && $_FILES['thumbnail_placeholder_src']['name'] != '') {
				try {
					/* Starting upload */
					$uploader = new Varien_File_Uploader('thumbnail_placeholder_src');
			
					// Any extention would work
					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
			
					// Set the file upload mode
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
			
					// We set media as the upload dir
					//$path = Mage::getDesign()->getSkinBaseDir(array('_area'=>'frontend'));
					//echo $path; exit;
						
					$media_path = Mage::getBaseDir('media');
					//echo $media_path."/email/logo/default/";
					//exit;
					//$uploader->save($path."/images/", $_FILES['email_logo_src']['name'] );
					$uploader->save($media_path."/catalog/product/placeholder/default/", $_FILES['thumbnail_placeholder_src']['name'] );
						
					
					$skinPath = Mage::getDesign()->getSkinBaseDir(array('_area'=>'frontend','_package'=>'evolved','_theme'=>'default'));
					$ext = pathinfo($_FILES['thumbnail_placeholder_src']['name'], PATHINFO_EXTENSION);
					copy($media_path."/catalog/product/placeholder/default/".$_FILES['thumbnail_placeholder_src']['name'] , $skinPath. "/images/catalog/product/placeholder/thumbnail.".$ext );
					
					
					$config = new Mage_Core_Model_Config();
					//$config ->saveConfig('design/email/logo_src', "images/".$_FILES['email_logo_src']['name'] , 'default', 0);
					$config ->saveConfig('catalog/placeholder/thumbnail_placeholder', "default/".$_FILES['thumbnail_placeholder_src']['name'] , 'default', 0);
						
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					$this->_redirect('logo/adminhtml_logo/new');
					return;
				}
			}

			$config = new Mage_Core_Model_Config();
			$config ->saveConfig('design/header/logo_alt', $this->getRequest()->getPost('design_header_logo_alt') , 'default', 0);
			$config ->saveConfig('design/email/logo_alt', $this->getRequest()->getPost('email_logo_alt') , 'default', 0);
			
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('logo')->__('Logo was successfully saved'));
			$this->_redirect('logo/adminhtml_logo/new');
			return;
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			$this->_redirect('logo/adminhtml_logo/new');
			return;
		}
			
	}
	protected function _isAllowed()
    {
    	return true;
    }
}
