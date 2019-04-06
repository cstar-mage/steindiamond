<?php
$installer = $this;
$installer->startSetup();

$installer->run("
		DELETE FROM {$this->getTable('core_config_data')} WHERE `path` = 'diamondsearch/slider_settings/certificate_slider';		 
");
$installer->run("
		ALTER TABLE {$this->getTable('uploadtool_diamonds_inventory')} CHANGE `totalprice` `totalprice` DECIMAL( 12, 2 ) NOT NULL ; 
");
$installer->endSetup();
