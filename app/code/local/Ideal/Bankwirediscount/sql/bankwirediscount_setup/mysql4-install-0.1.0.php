<?php
$installer = $this;
$installer->startSetup();

$installer->getConnection()
->addColumn($installer->getTable('sales/quote_address'),'bankwirediscount', array(
		'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
		'nullable'  => false,
		'length'    => 255,
		'after'     => null, // column name to insert new column after
		'comment'   => 'Bankwirediscount'
));
$installer->getConnection()->addColumn($installer->getTable('sales/quote_address'),'base_bankwirediscount_amount', array(
		'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
		'nullable'  => false,
		'length'    => 255,
		'after'     => null, // column name to insert new column after
		'comment'   => 'Base Bankwirediscount Amount'
));
$installer->getConnection()->addColumn($installer->getTable('sales/order'),'bankwirediscount', array(
		'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
		'nullable'  => false,
		'length'    => 255,
		'after'     => null, // column name to insert new column after
		'comment'   => 'Bankwirediscount'
));
$installer->getConnection()->addColumn($installer->getTable('sales/order'),'base_bankwirediscount_amount', array(
		'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
		'nullable'  => false,
		'length'    => 255,
		'after'     => null, // column name to insert new column after
		'comment'   => 'Base Bankwirediscount Amount'
));
$installer->endSetup();