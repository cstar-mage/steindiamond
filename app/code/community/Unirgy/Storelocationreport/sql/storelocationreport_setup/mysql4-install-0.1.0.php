<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('ustorelocator_report')};
CREATE TABLE {$this->getTable('ustorelocator_report')} (
  `storelocationreport_id` int(11) unsigned NOT NULL auto_increment,
  `search_string` text NOT NULL default '',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`storelocationreport_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 