    <?php
     
    class Dolphin_Slideshow_Block_Adminhtml_Slideshow_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
    {
    	
    	protected function _prepareLayout() {
    		parent::_prepareLayout();
    		if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
    			$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
    			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
    		}
    	}
    	
        protected function _prepareForm()
        {
            $form = new Varien_Data_Form();
            $this->setForm($form);
            $fieldset = $form->addFieldset('slideshow_form', array('legend'=>Mage::helper('slideshow')->__('Item information')));
           
            $configSettings = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
            		array(
            				'add_images' => true,
            				'add_widgets' => true,
            				'add_variables' => true,
            				'files_browser_window_url'=> Mage::helper("adminhtml")->getUrl("adminhtml/cms_wysiwyg_images/index"),
            		));
            
            $fieldset->addField('title', 'text', array(
                'label'     => Mage::helper('slideshow')->__('Title'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'title',
            ));
			
			if (!Mage::app()->isSingleStoreMode()) {
				$fieldset->addField('stores', 'multiselect', array(
					'name'      => 'stores[]',
					'label'     => Mage::helper('slideshow')->__('Select Store'),
					'title'     => Mage::helper('slideshow')->__('Select Store'),
					'required'  => true,
					'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
				));
			}
			else {
				$fieldset->addField('stores', 'hidden', array(
					'name'      => 'stores[]',
					'value'     => Mage::app()->getStore(true)->getId()
				));
			}
				
            $fieldset->addField('slide_url', 'text', array(
                'label'     => Mage::helper('slideshow')->__('Url'),
                'required'  => false,
                'name'      => 'slide_url',
            ));

            $fieldset->addField('slide_target', 'select', array(
                'label'     => Mage::helper('slideshow')->__('Target'),
                'name'      => 'slide_target',
                'values'    => array(
                    array(
                        'value'     => '_blank',
                        'label'     => Mage::helper('slideshow')->__('Blank'),
                    ),
                    array(
                        'value'     => '_new',
                        'label'     => Mage::helper('slideshow')->__('New'),
                    ),
                    array(
                        'value'     => '_parent',
                        'label'     => Mage::helper('slideshow')->__('Parent'),
                    ),
                    array(
                        'value'     => '_self',
                        'label'     => Mage::helper('slideshow')->__('Self'),
                    ),
                    array(
                        'value'     => '_top',
                        'label'     => Mage::helper('slideshow')->__('Top'),
                    ),
                ),
            ));

			/* $fieldset->addField('filename', 'image', array(
				  'label'     => Mage::helper('slideshow')->__('Image File'),
				  'required'  => true,
				  'name'      => 'filename',
			)); */
            
            $fieldset->addField('image_slide', 'editor', array(
            		'label'     => Mage::helper('evolved')->__('Insert Image File:'),
            		'name'      => 'image_slide',
            		'style'     =>  'height:50px;',
            		'config'    => $configSettings,
            		'after_element_html' => '<style>.add-widget,.add-variable { display:none; }</style>',
            ));
            
            $fieldset->addField('desktop_img', 'image', array(
            		'label'     => Mage::helper('slideshow')->__('Desktop Image'),
            		'name'      => 'desktop_img',
            		'after_element_html' => '',
            ));
            $fieldset->addField('desktop_img_check', 'checkbox', array(
            		'label'     => Mage::helper('slideshow')->__(''),
            		'name'      => 'desktop_img_check',    
            		'onclick'   => 'this.value = this.checked ? 1 : 0;',        		
            		'after_element_html' => '<strong>Advance Options</strong>',
            ));                               
            $field =  $fieldset->addField('desktop_img_textarea', 'editor', array(
            		'label'     => Mage::helper('evolved')->__(''),
            		'name'      => 'desktop_img_textarea',
            		'style'     =>  'height:50px;',
            		'config'    => $configSettings,
            		'after_element_html' => '<style>.add-widget,.add-variable { display:none; }</style>',
            ));
            
            $field->setAfterElementHtml('<script>
				//<![CDATA[
				setTimeout(function(){
				 var jq = jQuery.noConflict();
				jq(document).ready(function(){	
					jq("#buttonsdesktop_img_textarea").parent().hide();
					var checkbox01 = jq("#desktop_img_check").val();
					if(checkbox01 == ""){
						jq("#desktop_img_check").val("0");
					}				
					if(checkbox01 == "1"){
						jq("#desktop_img_check").prop("checked", true);
						jq("#buttonsdesktop_img_textarea").parent().show();  		
						jq("#desktop_img").parent().hide();		
					}     
					jq("#desktop_img_check").click(function(){		  
						jq("#buttonsdesktop_img_textarea").parent().toggle();  		
						jq("#desktop_img").parent().toggle();		
					});		
				});
				}, 2000);
				 
				//]]>
				</script>');
				
				
				
            
            
            $fieldset->addField('landscape_ipad_img', 'image', array(
            		'label'     => Mage::helper('slideshow')->__('Landscape ipad Image'),
            		'name'      => 'landscape_ipad_img',
            ));
             $fieldset->addField('landscape_ipad_img_check', 'checkbox', array(
            		'label'     => Mage::helper('slideshow')->__(''),
            		'name'      => 'landscape_ipad_img_check',            	
            		'onclick'   => 'this.value = this.checked ? 1 : 0;',        			
            		'after_element_html' => '<strong>Advance Options</strong>',
            ));                        
            $field =  $fieldset->addField('landscape_ipad_img_textarea', 'editor', array(
            		'label'     => Mage::helper('evolved')->__(''),
            		'name'      => 'landscape_ipad_img_textarea',
            		'style'     =>  'height:50px;',
            		'config'    => $configSettings,
            		'after_element_html' => '<style>.add-widget,.add-variable { display:none; }</style>',
            ));
            
            $field->setAfterElementHtml('<script>
				//<![CDATA[
				setTimeout(function(){
				 var jq = jQuery.noConflict();
				jq(document).ready(function(){	
					jq("#buttonslandscape_ipad_img_textarea").parent().hide();
					var checkbox01 = jq("#landscape_ipad_img_check").val();
					if(checkbox01 == ""){
						jq("#landscape_ipad_img_check").val("0");
					}	
					if(checkbox01 == "1"){
						jq("#landscape_ipad_img_check").prop("checked", true);
						jq("#buttonslandscape_ipad_img_textarea").parent().show();  		
						jq("#landscape_ipad_img").parent().hide();		
					}  					
					jq("#landscape_ipad_img_check").click(function(){		  
						jq("#buttonslandscape_ipad_img_textarea").parent().toggle();  		
						jq("#landscape_ipad_img").parent().toggle();		
					});		
				});
				}, 2000);
				 
				//]]>
				</script>');
				
				
				
            
            
            
            
            $fieldset->addField('portrait_ipad_img', 'image', array(
            		'label'     => Mage::helper('slideshow')->__('Portrait ipad Image'),
            		'name'      => 'portrait_ipad_img',
            ));
             $fieldset->addField('portrait_ipad_img_check', 'checkbox', array(
            		'label'     => Mage::helper('slideshow')->__(''),
            		'name'      => 'portrait_ipad_img_check',            		
            		'onclick'   => 'this.value = this.checked ? 1 : 0;',        		
            		'after_element_html' => '<strong>Advance Options</strong>',
            ));      
             $field =  $fieldset->addField('portrait_ipad_img_textarea', 'editor', array(
            		'label'     => Mage::helper('evolved')->__(''),
            		'name'      => 'portrait_ipad_img_textarea',
            		'style'     =>  'height:50px;', 
            		'config'    => $configSettings,
            		'after_element_html' => '<style>.add-widget,.add-variable { display:none; }</style>',
            ));
            
            $field->setAfterElementHtml('<script>
				//<![CDATA[
				setTimeout(function(){
				 var jq = jQuery.noConflict();
				jq(document).ready(function(){	
					jq("#buttonsportrait_ipad_img_textarea").parent().hide();
					var checkbox01 = jq("#portrait_ipad_img_check").val();
					if(checkbox01 == ""){
						jq("#portrait_ipad_img_check").val("0");
					}		
					if(checkbox01 == "1"){
						jq("#portrait_ipad_img_check").prop("checked", true);
						jq("#buttonsportrait_ipad_img_textarea").parent().show();  		
						jq("#portrait_ipad_img").parent().hide();		
					}  						
					jq("#portrait_ipad_img_check").click(function(){		  
						jq("#buttonsportrait_ipad_img_textarea").parent().toggle();  		
						jq("#portrait_ipad_img").parent().toggle();		
					});		
				});
				}, 2000);
				 
				//]]>
				</script>');
            
            
            
            
            
            
            
            
            
            $fieldset->addField('mobile_img', 'image', array(
            		'label'     => Mage::helper('slideshow')->__('Mobile Image'),
            		'name'      => 'mobile_img',
            ));
              $fieldset->addField('mobile_img_check', 'checkbox', array(
            		'label'     => Mage::helper('slideshow')->__(''),
            		'name'      => 'mobile_img_check',            		
            		'onclick'   => 'this.value = this.checked ? 1 : 0;',        		
            		'after_element_html' => '<strong>Advance Options</strong>',
            ));      
             $field =  $fieldset->addField('mobile_img_textarea', 'editor', array(
            		'label'     => Mage::helper('evolved')->__(''),
            		'name'      => 'mobile_img_textarea',
            		'style'     =>  'height:50px;', 
            		'config'    => $configSettings,
            		'after_element_html' => '<style>.add-widget,.add-variable { display:none; }</style>',
            ));
            
            $field->setAfterElementHtml('<script>
				//<![CDATA[
				setTimeout(function(){
				 var jq = jQuery.noConflict();
				jq(document).ready(function(){	
					jq("#buttonsmobile_img_textarea").parent().hide();
					var checkbox01 = jq("#mobile_img_check").val();
					if(checkbox01 == ""){
						jq("#mobile_img_check").val("0");
					}
					if(checkbox01 == "1"){		
						jq("#mobile_img_check").prop("checked", true);
						jq("#buttonsmobile_img_textarea").parent().show();  		
						jq("#mobile_img").parent().hide();		
					}  	
					jq("#mobile_img_check").click(function(){		  
						jq("#buttonsmobile_img_textarea").parent().toggle();  		
						jq("#mobile_img").parent().toggle();		
					});		
				});
				}, 2000);
				 
				//]]>
				</script>');
            
            
            
            
            
     
            $fieldset->addField('content', 'editor', array(
                'name'      => 'content',
                'label'     => Mage::helper('slideshow')->__('Content'),
                'title'     => Mage::helper('slideshow')->__('Content'),
                'style'     => 'height:100px;',
                'wysiwyg'   => false,
                'required'  => false,
            ));	   
            
            $fieldset->addField('sort_order', 'text', array(
            		'label'     => Mage::helper('slideshow')->__('Sort Order'),
            		'name'      => 'sort_order',
            ));
			
            $fieldset->addField('status', 'select', array(
                'label'     => Mage::helper('slideshow')->__('Status'),
                'name'      => 'status',
                'values'    => array(
                    array(
                        'value'     => 1,
                        'label'     => Mage::helper('slideshow')->__('Active'),
                    ),
     
                    array(
                        'value'     => 0,
                        'label'     => Mage::helper('slideshow')->__('Inactive'),
                    ),
                ),
            ));

            if ( Mage::getSingleton('adminhtml/session')->getSlideshowData() )
            {
                $form->setValues(Mage::getSingleton('adminhtml/session')->getSlideshowData());
                Mage::getSingleton('adminhtml/session')->setSlideshowData(null);
            } elseif ( Mage::registry('slideshow_data') ) {
                $form->setValues(Mage::registry('slideshow_data')->getData());
            }
           // echo "<pre>"; print_r(Mage::registry('slideshow_data')->getData()); echo "</pre>";
            return parent::_prepareForm();
        }
    }
    
    
       
    
