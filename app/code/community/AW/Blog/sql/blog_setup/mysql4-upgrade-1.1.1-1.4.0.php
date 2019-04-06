<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
$installer->run("
    ALTER TABLE {$this->getTable('blog/blog')} MODIFY `post_content` MEDIUMTEXT NOT NULL;
    ALTER TABLE {$this->getTable('blog/blog')}  ADD `featured_image` VARCHAR(255) NOT NULL;
");
$installer->endSetup();