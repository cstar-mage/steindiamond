<?php
$installer = $this;
$installer->startSetup();
$installer->run("
	ALTER TABLE {$this->getTable('mr_contactform')}
	ADD COLUMN `date` text NOT NULL AFTER `comment`;
	");
$installer->endSetup();