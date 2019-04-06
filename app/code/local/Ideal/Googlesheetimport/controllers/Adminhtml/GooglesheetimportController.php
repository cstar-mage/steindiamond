<?php

class Ideal_Googlesheetimport_Adminhtml_GooglesheetimportController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('catalog/googlesheetimport')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Google Sheet Import'), Mage::helper('adminhtml')->__('Google Sheet Import'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {

		$this->loadLayout();
		$this->_setActiveMenu('catalog/googlesheetimport');

		$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Google Sheet Import'), Mage::helper('adminhtml')->__('Google Sheet Import'));

		$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

		$this->_addContent($this->getLayout()->createBlock('googlesheetimport/adminhtml_googlesheetimport_edit'))
			->_addLeft($this->getLayout()->createBlock('googlesheetimport/adminhtml_googlesheetimport_edit_tabs'));

		$this->renderLayout();
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
	
	public function importAction() {
		$this->_forward('edit');
	}
 
	public function getImportCSVAction() {
	
		$action = Mage::helper('googlesheetimport')->getImportCSV();
		if($action['success'] == 0) {
			Mage::getSingleton("adminhtml/session")->addError($action['message']);
		} else {
			Mage::getSingleton("adminhtml/session")->addSuccess($action['message']);
		}
		$this->_redirect("*/*/import");
		return;
	}
	
	public function getUpdateCSVAction() {
	
		$action = Mage::helper('googlesheetimport')->getUpdateCSV();
		if($action['success'] == 0) {
			Mage::getSingleton("adminhtml/session")->addError($action['message']);
		} else {
			Mage::getSingleton("adminhtml/session")->addSuccess($action['message']);
		}
		$this->_redirect("*/*/import");
		return;
	}
	
	public function getImagesAction() {
	
		$action = Mage::helper('googlesheetimport')->getImages();
		if($action['success'] == 0) {
			Mage::getSingleton("adminhtml/session")->addError($action['message']);
		} else {
			Mage::getSingleton("adminhtml/session")->addSuccess($action['message']);
		}
		$this->_redirect("*/*/import");
		return;
	
	}
	
	public function importProductsAction() {
	
		$url = $this->getUrl("*idealAdmin/system_convert_gui/run/", array("id" => 3, "files" => "GoogleSheetImport.csv"));
		$url = str_replace("*idealAdmin","idealAdmin", $url);
		?>
		<script type="text/javascript">
			window.location = "<?php echo $url ?>";
		</script> 
		<?php
	}
			
	public function updateProductsAction() {
		
		$url = $this->getUrl("*idealAdmin/system_convert_gui/run/", array("id" => 3, "files" => "GoogleSheetUpdate.csv"));
		$url = str_replace("*idealAdmin","idealAdmin", $url);
		?>
		<script type="text/javascript">
			window.location = "<?php echo $url ?>";
		</script> 
		<?php
	}		
		
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('filename');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS ;
					$uploader->save($path, $_FILES['filename']['name'] );
					
				} catch (Exception $e) {
		      
		        }
	        
		        //this way the name is saved in DB
	  			$data['filename'] = $_FILES['filename']['name'];
			}
	  			
	  			
			$model = Mage::getModel('googlesheetimport/googlesheetimport');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('googlesheetimport')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('googlesheetimport')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('googlesheetimport/googlesheetimport');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $googlesheetimportIds = $this->getRequest()->getParam('googlesheetimport');
        if(!is_array($googlesheetimportIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($googlesheetimportIds as $googlesheetimportId) {
                    $googlesheetimport = Mage::getModel('googlesheetimport/googlesheetimport')->load($googlesheetimportId);
                    $googlesheetimport->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($googlesheetimportIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $googlesheetimportIds = $this->getRequest()->getParam('googlesheetimport');
        if(!is_array($googlesheetimportIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($googlesheetimportIds as $googlesheetimportId) {
                    $googlesheetimport = Mage::getSingleton('googlesheetimport/googlesheetimport')
                        ->load($googlesheetimportId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($googlesheetimportIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'googlesheetimport.csv';
        $content    = $this->getLayout()->createBlock('googlesheetimport/adminhtml_googlesheetimport_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'googlesheetimport.xml';
        $content    = $this->getLayout()->createBlock('googlesheetimport/adminhtml_googlesheetimport_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

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
}