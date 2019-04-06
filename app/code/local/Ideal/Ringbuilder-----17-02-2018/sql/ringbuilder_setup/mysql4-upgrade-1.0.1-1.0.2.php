<?php
$installer = $this;
$installer->startSetup();
$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('ringbuilder_diamondvendors')};
CREATE TABLE IF NOT EXISTS {$this->getTable('ringbuilder_diamondvendors')} (
		`vendor_id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(255) NOT NULL,
		`contact_person` varchar(255) NOT NULL,
		`email` varchar(255) NOT NULL,
		PRIMARY KEY (`vendor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
");
$installer->endSetup();
?> 