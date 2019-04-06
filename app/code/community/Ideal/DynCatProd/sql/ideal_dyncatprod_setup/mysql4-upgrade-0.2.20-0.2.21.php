<?php
$installer = $this;
$installer->startSetup();
$installer->run("
	ALTER TABLE {$this->getTable('catalog_eav_attribute')}
		ADD COLUMN `used_for_dyncatprod_by` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Is Used For Dynamic category product' AFTER `layered_navigation_canonical`;	
	");
$installer->endSetup();
