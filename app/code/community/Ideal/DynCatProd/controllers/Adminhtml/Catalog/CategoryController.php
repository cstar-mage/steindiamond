<?php

class Ideal_DynCatProd_Adminhtml_Catalog_CategoryController extends Mage_Adminhtml_Controller_Action
{
    

    /**
     * Grid Action
     * Display list of products related to current category
     *
     * @return void
     */
   /* public function gridAction()
    {
        $categoryId = $this->getRequest()->getParam('category_id');
        $category = Mage::getModel('catalog/category')->load($categoryId);
        Mage::register('category',$category);
        Mage::register('current_category',$category);
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('dyncatprod/adminhtml_catalog_category_tab_dyncatprod_grid', 'category.dyncatprod.grid')
                ->toHtml()
        );
    }*/
    
    protected function _initCategory($getRootInstead = false)
    {
    	$this->_title($this->__('Catalog'))
    	->_title($this->__('Categories'))
    	->_title($this->__('Manage Categories'));
    
    	$categoryId = (int) $this->getRequest()->getParam('id',false);
    	$storeId    = (int) $this->getRequest()->getParam('store');
    	$category = Mage::getModel('catalog/category');
    	$category->setStoreId($storeId);
    
    	if ($categoryId) {
    		$category->load($categoryId);
    		if ($storeId) {
    			$rootId = Mage::app()->getStore($storeId)->getRootCategoryId();
    			if (!in_array($rootId, $category->getPathIds())) {
    				// load root category instead wrong one
    				if ($getRootInstead) {
    					$category->load($rootId);
    				}
    				else {
    					$this->_redirect('*/*/', array('_current'=>true, 'id'=>null));
    					return false;
    				}
    			}
    		}
    	}
    
    	if ($activeTabId = (string) $this->getRequest()->getParam('active_tab_id')) {
    		Mage::getSingleton('admin/session')->setActiveTabId($activeTabId);
    	}
    
    	Mage::register('category', $category);
    	Mage::register('current_category', $category);
    	Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getParam('store'));
    	return $category;
    }
    
 public function saveAction()
    {
    	

        if (!$category = $this->_initCategory()) {
            return;
        }
       

        $storeId = $this->getRequest()->getParam('store');
        $refreshTree = 'false';

        if ($data = $this->getRequest()->getPost()) {
        	
            $category->addData($data['general']);
            if (!$category->getId()) {
                $parentId = $this->getRequest()->getParam('parent');
                if (!$parentId) {
                    if ($storeId) {
                        $parentId = Mage::app()->getStore($storeId)->getRootCategoryId();
                    }
                    else {
                        $parentId = Mage_Catalog_Model_Category::TREE_ROOT_ID;
                    }
                }
                $parentCategory = Mage::getModel('catalog/category')->load($parentId);
                $category->setPath($parentCategory->getPath());
            }

            /**
             * Check "Use Default Value" checkboxes values
             */
            if ($useDefaults = $this->getRequest()->getPost('use_default')) {
                foreach ($useDefaults as $attributeCode) {
                    $category->setData($attributeCode, false);
                }
            }

            /**
             * Process "Use Config Settings" checkboxes
             */
            if ($useConfig = $this->getRequest()->getPost('use_config')) {
                foreach ($useConfig as $attributeCode) {
                    $category->setData($attributeCode, null);
                }
            }

            /**
             * Create Permanent Redirect for old URL key
             */
            if ($category->getId() && isset($data['general']['url_key_create_redirect']))
            // && $category->getOrigData('url_key') != $category->getData('url_key')
            {
                $category->setData('save_rewrites_history', (bool)$data['general']['url_key_create_redirect']);
            }

            $category->setAttributeSetId($category->getDefaultAttributeSetId());

            if (isset($data['category_products']) &&
                !$category->getProductsReadonly()
            ) {

                $products = Mage::helper('core/string')->parseQueryStr($data['category_products']);
                $category->setPostedProducts($products);
            }

            Mage::dispatchEvent('catalog_category_prepare_save', array(
                'category' => $category,
                'request' => $this->getRequest()
            ));

            /**
             * Proceed with $_POST['use_config']
             * set into category model for proccessing through validation
             */
            $category->setData("use_post_data_config", $this->getRequest()->getPost('use_config'));

            try {
                $validate = $category->validate();
                if ($validate !== true) {
                    foreach ($validate as $code => $error) {
                        if ($error === true) {
                            Mage::throwException(Mage::helper('catalog')->__('Attribute "%s" is required.', $category->getResource()->getAttribute($code)->getFrontend()->getLabel()));
                        }
                        else {
                            Mage::throwException($error);
                        }
                    }
                }

                /**
                 * Unset $_POST['use_config'] before save
                 */
                $category->unsetData('use_post_data_config');

                $category->save();

                if($category->getData('dynamic_attributes'))
                { //http://www.diegomestre.com/en/making-queries-with-addattributetofilter-in-magento/
                	
                	$category = Mage::getModel('catalog/category')->load($category->getId());
                	
                	$storeId = Mage::app()->getStore()->getStoreId();
                	
                	
                	
                	// Mage::log(unserialize($category->getData('dynamic_attributes')));
                	$dynamiccatcondition = unserialize($category->getData('dynamic_attributes'));
                	$productcollection = Mage::getModel('catalog/product')->getCollection();
                	             	
                	$arr = array();
                	
                	foreach($dynamiccatcondition as $dynamiccatcondition1)
                	{
                		// Mage::log("#######################");
                		// Mage::log("Attribute : ".$dynamiccatcondition1['attribute']);
                		// Mage::log("conditionrule : ".$dynamiccatcondition1['conditionrule']);
                		// Mage::log("value : ".$dynamiccatcondition1['value']);
                		
                		$resource = Mage::getSingleton('core/resource');
                		$readConnection = $resource->getConnection('core_read');
                		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
                		
                		$_attribute_code = Mage::getModel('eav/entity_attribute')->load($dynamiccatcondition1['attribute'])->getAttributeCode();
                		$attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')->setCodeFilter($_attribute_code)->getFirstItem();
                		// Mage::log("----------------------".$attributeInfo->getData('frontend_input')."------------------------");
                		if(($attributeInfo->getData('frontend_input') == "multiselect") || ($attributeInfo->getData('frontend_input') == "select"))
                		{
                			$optionid = array();
                			// Mage::log("=========selcet=======");

                			switch ($dynamiccatcondition1['conditionrule']) {
                				case 'eq':
                					$query = "SELECT * FROM " . $resource->getTableName("eav_attribute_option_value") . " where store_id = ".$storeId." and value like '".$dynamiccatcondition1['value']."'";
                					break;
                				case 'neq':
                					$query = "SELECT * FROM " . $resource->getTableName("eav_attribute_option_value") . " where store_id = ".$storeId." and value NOT like '".$dynamiccatcondition1['value']."'";
                					break;
                				case 'like':
                					$query = "SELECT * FROM " . $resource->getTableName("eav_attribute_option_value") . " where store_id = ".$storeId." and value like '%".$dynamiccatcondition1['value']."%'";
                					break;
                				case 'nlike':
                					$query = "SELECT * FROM " . $resource->getTableName("eav_attribute_option_value") . " where store_id = ".$storeId." and value NOT like '%".$dynamiccatcondition1['value']."%'";
                					break;

                			}
                			$optresults = $readConnection->fetchAll($query);
                			foreach($optresults as $optresults1)
                			{
                				$optionid[] = $optresults1['option_id'];
                			}
                			$arr[] = array('attribute'=> $_attribute_code ,array('in' => $optionid));
                		}
                		elseif($attributeInfo->getData('frontend_input') == "price")
                		{
                			switch ($dynamiccatcondition1['conditionrule']) {
                				case 'eq':
									  $arr[] = array('attribute'=> $_attribute_code ,'eq' => $dynamiccatcondition1['value']);
                					break;
                				case 'lt':
	                     			  $arr[] = array('attribute'=> $_attribute_code ,'lt' => $dynamiccatcondition1['value']);
	                     			break;
                     			case 'lteq':
                     				$arr[] = array('attribute'=> $_attribute_code ,'lteq' => $dynamiccatcondition1['value']);
                     				break;
	                     		case 'gt':
	                     			  $arr[] = array('attribute'=> $_attribute_code ,'gt' => $dynamiccatcondition1['value']);
                					break;
                				case 'gteq':
                					  $arr[] = array('attribute'=> $_attribute_code ,'gteq' => $dynamiccatcondition1['value']);
                					break;
                			}
                		}
                		else 
                		{
                			// Mage::log("=========other=======");	
                			switch ($dynamiccatcondition1['conditionrule']) {
                				case 'eq':
                					$arr[] = array('attribute'=> $_attribute_code ,'like' => ''.$dynamiccatcondition1['value'].'');
                					break;
                				case 'neq':
                					$arr[] = array('attribute'=> $_attribute_code ,'neq' => ''.$dynamiccatcondition1['value'].'');
                					break;
                				case 'like':
                					$arr[] = array('attribute'=> $_attribute_code ,'like' => '%'.$dynamiccatcondition1['value'].'%');
                					break;
                				case 'nlike':
                					$arr[] = array('attribute'=> $_attribute_code ,'nlike' => '%'.$dynamiccatcondition1['value'].'%');
                					break;

                			}
                		}
                	}
                	$productcollection->addAttributeToFilter($arr);
                	$dynamiccat_pro = array();
                	foreach($productcollection as $productcollection1)
                	{
                		$dynamiccat_pro[] = $productcollection1['entity_id'];
                	}
                	foreach($dynamiccat_pro as $dynamiccat_pro1key => $dynamiccat_pro1value)
                	{
                		$assign_pro[$dynamiccat_pro1value] = 0;
                	}
                	
   					$dynamicquery = "SELECT * FROM " . $resource->getTableName("catalog_category_dynamic_product_index") . " where store_id = ".$storeId." and category_id = ".$category->getId();
   					$dynamicproresults = $readConnection->fetchAll($dynamicquery);
   					$proimplodestr = implode(",",$dynamiccat_pro);
   					
   					// Mage::log("Store ::::::::::::: ".$storeId);
   					
   					//Mage::log("Store ::::::::::::: ".$storeId);
   					//Mage::log(count($dynamicproresults));
   					//Mage::log($dynamicproresults);   					
   					if(count($dynamicproresults)==0)
   					{
   						Mage::log("category : ".$category->getId()."  product_ids : ".$proimplodestr." store_id : ".$storeId);
   						$write->insert(
   								$resource->getTableName("catalog_category_dynamic_product_index"),
   								array("category_id" => $category->getId(), "product_ids" => $proimplodestr, "store_id" => $storeId)
   						);

   					}
   					else 
   					{
   						$write->update(
   								$resource->getTableName("catalog_category_dynamic_product_index"),
   								array("product_ids" => $proimplodestr),
   								"category_id=".$category->getId(),
   								"store_id=".$storeId
   						);
   					}
                	
                	$category->setPostedProducts($assign_pro);
                	$category->save();
                	
                	
                	// Mage::log($productcollection->getData());
                	// Mage::log($assign_pro);
                	// Mage::log($dynamicproresults);
                	// Mage::log(count($dynamicproresults));
                	//// Mage::log($ccdpi->getData());
                }
                
                
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('catalog')->__('The category has been saved.'));
                $refreshTree = 'true';
            }
            catch (Exception $e){
                $this->_getSession()->addError($e->getMessage())
                    ->setCategoryData($data);
                $refreshTree = 'false';
            }
        }
        $url = $this->getUrl('*/*/edit', array('_current' => true, 'id' => $category->getId()));
        $this->getResponse()->setBody(
            '<script type="text/javascript">parent.updateContent("' . $url . '", {}, '.$refreshTree.');</script>'
        );
    }
    /**
     * Check if admin has permissions to visit related pages
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/categories');
    }
}
