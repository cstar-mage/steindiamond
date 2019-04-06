<?php

$installer = $this;

$installer->startSetup();

$installer->run("ALTER TABLE {$this->getTable('brandmanager')} ADD `target` tinyint(1) NOT NULL ; ");

$installer->endSetup();
