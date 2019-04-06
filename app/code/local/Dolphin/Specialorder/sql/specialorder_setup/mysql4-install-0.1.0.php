<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('specialorder')};
CREATE TABLE {$this->getTable('specialorder')} (
  `customcontact_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',  
  `email` varchar(255) NOT NULL default '',
  `zipcode` varchar(255) NOT NULL default '',
  `phone` varchar(255) NOT NULL default '',  
  `comments` varchar(255) NOT NULL default '',
  `pricerange` varchar(255) NOT NULL default '',
  `moreimportant` varchar(255) NOT NULL default '',
  `month` varchar(255) NOT NULL default '',
  `day` varchar(255) NOT NULL default '',
  `year` varchar(255) NOT NULL default '',   
  `producttype` varchar(255) NOT NULL default '',
  `created_time` datetime NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`customcontact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 
