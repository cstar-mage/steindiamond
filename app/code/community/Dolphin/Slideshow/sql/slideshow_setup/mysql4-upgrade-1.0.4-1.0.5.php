<?php
$installer = $this;
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */

$installer->startSetup();
$installer->run("
	ALTER TABLE `{$installer->getTable('slideshow')}` 
		ADD (
				`desktop_img_check` text NULL,				
				`desktop_img_textarea` text NULL,				
				`landscape_ipad_img_check` text NULL,				
				`landscape_ipad_img_textarea` text NULL,				
				`portrait_ipad_img_check` text NULL,				
				`portrait_ipad_img_textarea` text NULL,				
				`mobile_img_check` text NULL,				
				`mobile_img_textarea` text NULL
			);
");
$installer->endSetup();
