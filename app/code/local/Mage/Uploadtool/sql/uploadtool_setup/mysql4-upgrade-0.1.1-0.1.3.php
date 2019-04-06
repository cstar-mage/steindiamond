<?php
$installer = $this;

$installer->startSetup();

$installer->run("
		CREATE TABLE IF NOT EXISTS {$this->getTable('uploadtool_diamondinquiries')} (
		`diamondinquiries_id` int(11) NOT NULL AUTO_INCREMENT,
		`firstname` varchar(255) NOT NULL,
		`lastname` varchar(255) NOT NULL,
		`phone` varchar(255) NOT NULL,
		`email` varchar(255) NOT NULL,
		`comment` varchar(255) NOT NULL, 
		PRIMARY KEY (`diamondinquiries_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		
		CREATE TABLE IF NOT EXISTS {$this->getTable('uploadtool_custom_vendor')} (
			  `custom_vendor_id` int(11) NOT NULL AUTO_INCREMENT,
			  `custom_vendor_name` varchar(255) NOT NULL,
			  `last_updated` varchar(255) NOT NULL, 
			  PRIMARY KEY (`custom_vendor_id`),
			  UNIQUE KEY `id` (`custom_vendor_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();