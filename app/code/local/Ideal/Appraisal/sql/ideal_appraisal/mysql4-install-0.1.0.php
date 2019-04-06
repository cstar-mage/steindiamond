<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("	

CREATE TABLE IF NOT EXISTS {$this->getTable('ideal_appraisal_counter')} (
  `id` smallint(5) unsigned NOT NULL,
  `counter` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");

$conn = $installer->getConnection();

$conn->query("INSERT INTO `{$this->getTable('ideal_appraisal_counter')}` (`counter`) VALUES (1000)");

$installer->endSetup();