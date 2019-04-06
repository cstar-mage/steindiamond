<?php
$installer = $this;
$installer->startSetup();

$installer->run("
	ALTER TABLE {$this->getTable('uploadtool_diamonds_inventory')} ADD `photo8` TEXT NOT NULL;
	ALTER TABLE {$this->getTable('uploadtool_diamonds_inventory')} ADD `photo9` TEXT NOT NULL;
");

$installer->endSetup();
