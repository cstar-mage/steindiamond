<?php

$installer = $this;

$installer->startSetup();

$installer->addAttribute('catalog_category', 'ringbuilder_enabled', array(
    'type'              => 'int',
    'label'             => 'Enable Ring Builder',
    'input'             => 'select',
    'source'            => 'eav/entity_attribute_source_boolean',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'required'          => false,
    'visible'           => true,
    'user_defined'      => true,
    'default'           => 0,
    'position'          => -1,
));

$entityTypeId = $entityTypeId = $this->getEntityTypeId('catalog_category');
$sets = $this->_conn->fetchAll('select * from '.$this->getTable('eav/attribute_set').' where entity_type_id=?', $entityTypeId);
try {
    foreach ($sets as $set) {
        $this->addAttributeToSet($entityTypeId, $set['attribute_set_id'], 'General Information', 'ringbuilder_enabled', 200);
    }
} catch (Exception $e) {

}

$installer->endSetup();
