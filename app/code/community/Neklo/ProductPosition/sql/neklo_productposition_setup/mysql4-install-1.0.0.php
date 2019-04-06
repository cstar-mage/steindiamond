<?php
/*
NOTICE OF LICENSE

This source file is subject to the NekloEULA that is bundled with this package in the file ICENSE.txt.

It is also available through the world-wide-web at this URL: http://store.neklo.com/LICENSE.txt

Copyright (c)  Neklo (http://store.neklo.com/)
*/

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
$installer->run("
    CREATE TABLE IF NOT EXISTS `{$installer->getTable('neklo_productposition/product_status')}` (
        `entity_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `category_id` INT(10) UNSIGNED NOT NULL,
        `product_id` INT(10) UNSIGNED NOT NULL,
        `is_attached` SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0,
        PRIMARY KEY (`entity_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->endSetup();