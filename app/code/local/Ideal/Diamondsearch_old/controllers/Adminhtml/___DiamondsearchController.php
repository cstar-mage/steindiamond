<?php
class Ideal_Diamondsearch_Adminhtml_DiamondsearchController extends Mage_Adminhtml_Controller_action
{
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('diamondsearch/Settings')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   

	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function settingsAction() {
		$this->_forward('edit');
	}
	
	public function editAction() {
		if($this->getRequest()->getParam('id'))
		{
			$id  = $this->getRequest()->getParam('id');
		}
		else
		{
			$collection  = Mage::getModel('diamondsearch/diamondsearch')->getCollection();
			foreach($collection as $row) {
				$id = $row['diamondsearch_id'];
			}
		}
		$model  = Mage::getModel('diamondsearch/diamondsearch')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			$collection = Mage::getModel('diamondsearch/diamondsearch')->getCollection();
			$formData = array();
			foreach($collection as $row) {	
				$field = $row['field'];
				$value = $row['value'];				
				$formData[$field] = $value;
			}

			Mage::register('diamondsearch_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('diamondsearch/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->getLayout()->getBlock('head')->addJs('evolved/jquery-1.8.3.min.js');
			$this->getLayout()->getBlock('head')->addJs('evolved/jquery.noconflict.js');
			$this->getLayout()->getBlock('head')->addJs('evolved/jquery.mColorPicker.min.js');

			$this->_addContent($this->getLayout()->createBlock('diamondsearch/adminhtml_diamondsearch_edit'))
				->_addLeft($this->getLayout()->createBlock('diamondsearch/adminhtml_diamondsearch_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('diamondsearch')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			$storeId = Mage::app()->getStore()->getStoreId();
			try {
				$config = new Mage_Core_Model_Config();

				$config -> saveConfig('diamondsearch/general_settings/include_jquery', $data["include_jquery"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/general_settings/ds_skin', $data["ds_skin"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/general_settings/show_origin', $data["show_origin"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/general_settings/show_rapper', $data["show_rapper"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/general_settings/header_text', $data["header_text"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/general_settings/compare_request', $data["compare_request"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/general_settings/showblock_detail', $data["showblock_detail"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/general_settings/showcolumn_rapdiscount', $data["showcolumn_rapdiscount"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/general_settings/showcolumn_availability', $data["showcolumn_availability"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/general_settings/showcolumn_dimensions', $data["showcolumn_dimensions"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/general_settings/showcolumn_depth', $data["showcolumn_depth"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/general_settings/showcolumn_tabl', $data["showcolumn_tabl"] , 'default', $storeId);

				$config -> saveConfig('diamondsearch/general_settings/inhouse_vendor', $data["inhouse_vendor"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/general_settings/showcolumn_inhouse', $data["showcolumn_inhouse"] , 'default', $storeId);

				$config -> saveConfig('diamondsearch/general_settings/diamond_description', $data["diamond_description"] , 'default', $storeId);
				
				//uploading certificate sample image
			 	if(isset($_FILES['	']['name']) && $_FILES['certificate_sample']['name'] != '') {
					try {
						/* File Upload code here */
		            $uploader = new Varien_File_Uploader('certificate_sample');
		            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything
		            $uploader->setAllowRenameFiles(false);
		            $uploader->setFilesDispersion(false);
		            $path = Mage::getBaseDir('media') . DS. 'dsearch'.DS ;
		            $destFile = $path."sample_cert.".pathinfo($_FILES['certificate_sample']['name'], PATHINFO_EXTENSION);
		            $filename = $uploader->getNewFileName($destFile);
		            $uploader->save($path, $filename);
		            $certificate_sample = 'dsearch'.DS.$filename;
		            $config -> saveConfig('diamondsearch/general_settings/certificate_sample', $certificate_sample , 'default', $storeId);
					} catch (Exception $e) {}
				}
				else
				{
					if(isset($data['certificate_sample']['delete']) && $data['certificate_sample']['delete'] == 1){
						$certificate_sample = '';
						$config -> saveConfig('diamondsearch/general_settings/certificate_sample', $certificate_sample , 'default', $storeId);
					}
			 
				} 
		 
				$collection = unserialize(Mage::getStoreConfig("diamondsearch/slider_settings/certificate_slider"));
				$i=0;
				foreach($collection as $item)
				{
 
					$data["certificate_slider"]['value']['option_'.$i]['image']=$item['image'];
					$i++;
				}	
	 
				$cert=$this->getRequest()->getPost('certificate_slider');
 
				$max = sizeof($_FILES['image']['name']);
				$images['image']=array();
				$j=0;
				for($i=0;$i < $max; $i++)
				{
					
					if(isset($_FILES['image']['name'][$i])  && $_FILES['image']['name'][$i] != '' )
					{
							$uploader = new Varien_File_Uploader('image['.$i.']');
							$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything
							$uploader->setAllowRenameFiles(false);
							$uploader->setFilesDispersion(false);
							$path = Mage::getBaseDir('media') . DS. 'dsearch'.DS ;
							$option='option_'.$i;
							$label=$cert['value'][$option]['label'];
							$label=str_replace(' ','_',$label);
							$destFile = $path.$label.".".pathinfo($_FILES['image']['name'][$i], PATHINFO_EXTENSION);
							$filename = $uploader->getNewFileName($destFile);
							$uploader->save($path, $filename);
							$images['image'][$j]=$filename;
							$data["certificate_slider"]['value']['option_'.$j]['image']=$filename;
						 	
					}
					
			 		$j++;
				}
 
				$imagedelete=$this->getRequest()->getPost('deleteimage');
		 
				if($imagedelete != '')
				{
					foreach($imagedelete as $image => $value)
					{
 
						if($value==1)
						{
				 
							$imagename=$data["certificate_slider"]['value']['option_'.$image]['image'];
							unlink(Mage::getBaseDir('media') . DS .'dsearch' .DS . $imagename);
							$data["certificate_slider"]['value']['option_'.$image]['image']='';
						}
					}
				}
 
				$config -> saveConfig('diamondsearch/design_settings/shape_color', $data["shape_color"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/slider_bgcolor', $data["slider_bgcolor"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/slider_shadow_color', $data["slider_shadow_color"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/slider_disabled_bgcolor', $data["slider_disabled_bgcolor"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/slider_disabled_shadow_color', $data["slider_disabled_shadow_color"] , 'default', $storeId);
				
				$config -> saveConfig('diamondsearch/design_settings/colorswitch_button_textcolor', $data["colorswitch_button_textcolor"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/colorswitch_button_color', $data["colorswitch_button_color"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/colorswitch_button_hover_color', $data["colorswitch_button_hover_color"] , 'default', $storeId);
				
				$config -> saveConfig('diamondsearch/design_settings/advanced_search_textcolor', $data["advanced_search_textcolor"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/advanced_search_bgcolor', $data["advanced_search_bgcolor"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/tabs_textcolor', $data["tabs_textcolor"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/tabs_bgcolor', $data["tabs_bgcolor"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/table_header_textcolor', $data["table_header_textcolor"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/table_header_bgcolor', $data["table_header_bgcolor"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/table_row_odd_bgcolor', $data["table_row_odd_bgcolor"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/table_row_even_bgcolor', $data["table_row_even_bgcolor"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/table_row_hover_bgcolor', $data["table_row_hover_bgcolor"] , 'default', $storeId);
				
				$config -> saveConfig('diamondsearch/design_settings/table_sort_arrow_style', $data["table_sort_arrow_style"] , 'default', $storeId);
				
				$config -> saveConfig('diamondsearch/design_settings/view_button_textcolor', $data["view_button_textcolor"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/view_button_color', $data["view_button_color"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/view_button_hover_color', $data["view_button_hover_color"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/viewpage_table_textcolor', $data["viewpage_table_textcolor"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/viewpage_table_bgcolor', $data["viewpage_table_bgcolor"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/viewpage_button_textcolor', $data["viewpage_button_textcolor"] , 'default', $storeId);
				$config -> saveConfig('diamondsearch/design_settings/viewpage_button_bgcolor', $data["viewpage_button_bgcolor"] , 'default', $storeId);
				
				$config -> saveConfig('diamondsearch/shape_settings/shape_style', $data["shape_style"], 'default', $storeId);
		 
				foreach($_FILES['shape_available']['name']['value'] as $keys => $values)
				{
					if($values['shapeimage'])
					{
						$data['shape_available']['value'][$keys]['shapeimage'] = $values['shapeimage'];
						echo $values['shapeimage'];
						echo $keys;
						
						
						
					}	
				}
				
			 

 
				
				$config -> saveConfig('diamondsearch/shape_settings/shape_available', serialize( Mage::helper('diamondsearch')->filterSliderArray( $data["shape_available"])) , 'default', $storeId);
				$config -> saveConfig('diamondsearch/shape_settings/specialshape_available', serialize( Mage::helper('diamondsearch')->filterSliderArray( $data["specialshape_available"])) , 'default', $storeId);
	 

				$config -> saveConfig('diamondsearch/slider_settings/color_slider', serialize( Mage::helper('diamondsearch')->filterSliderArray( $data["color_slider"])) , 'default', $storeId);
				$config -> saveConfig('diamondsearch/slider_settings/fancycolor_slider', serialize(Mage::helper('diamondsearch')->filterSliderArray( $data["fancycolor_slider"])) , 'default', $storeId);
				$config -> saveConfig('diamondsearch/slider_settings/clarity_slider', serialize(Mage::helper('diamondsearch')->filterSliderArray( $data["clarity_slider"])) , 'default', $storeId);
				$config -> saveConfig('diamondsearch/slider_settings/cut_slider', serialize(Mage::helper('diamondsearch')->filterSliderArray( $data["cut_slider"])) , 'default', $storeId);
				$config -> saveConfig('diamondsearch/slider_settings/fluorescence_slider', serialize(Mage::helper('diamondsearch')->filterSliderArray( $data["fluorescence_slider"])) , 'default', $storeId);
				$config -> saveConfig('diamondsearch/slider_settings/certificate_slider', serialize(Mage::helper('diamondsearch')->filterSliderArray( $data["certificate_slider"])) , 'default', $storeId);
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('diamondsearch')->__('Settings was successfully saved'));
				$this->_redirect('*/*/settings');
				return;
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/settings');
				return;
			}
		}
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('diamondsearch/diamondsearch');
				 
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
        $diamondsearchIds = $this->getRequest()->getParam('diamondsearch');
        if(!is_array($diamondsearchIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($diamondsearchIds as $diamondsearchId) {
                    $diamondsearch = Mage::getModel('diamondsearch/diamondsearch')->load($diamondsearchId);
                    $diamondsearch->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($diamondsearchIds)
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
        $diamondsearchIds = $this->getRequest()->getParam('diamondsearch');
        if(!is_array($diamondsearchIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($diamondsearchIds as $diamondsearchId) {
                    $diamondsearch = Mage::getSingleton('diamondsearch/diamondsearch')
                        ->load($diamondsearchId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($diamondsearchIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'diamondsearch.csv';
        $content    = $this->getLayout()->createBlock('diamondsearch/adminhtml_diamondsearch_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'diamondsearch.xml';
        $content    = $this->getLayout()->createBlock('diamondsearch/adminhtml_diamondsearch_grid')
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
