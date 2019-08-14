<?php

set_time_limit(0);

/* @var $this Mage_Eav_Model_Entity_Setup */
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer = $this;
$installer->startSetup();

$installer->run("
    ALTER TABLE  {$this->getTable('sales/quote_address')} ADD  `carbon_amount` DECIMAL( 10, 2 ) NOT NULL;
    ALTER TABLE  {$this->getTable('sales/quote_address')} ADD  `base_carbon_amount` DECIMAL( 10, 2 ) NOT NULL;
    ALTER TABLE  {$this->getTable('sales/order')} ADD  `carbon_amount` DECIMAL( 10, 2 ) NOT NULL;
    ALTER TABLE  {$this->getTable('sales/order')} ADD  `base_carbon_amount` DECIMAL( 10, 2 ) NOT NULL;
    ALTER TABLE  {$this->getTable('sales/order')} ADD  `carbon_amount_invoiced` DECIMAL( 10, 2 ) NOT NULL;
    ALTER TABLE  {$this->getTable('sales/order')} ADD  `base_carbon_amount_invoiced` DECIMAL( 10, 2 ) NOT NULL;
    ALTER TABLE  {$this->getTable('sales/order')} ADD  `carbon_amount_refunded` DECIMAL( 10, 2 ) NOT NULL;
    ALTER TABLE  {$this->getTable('sales/order')} ADD  `base_carbon_amount_refunded` DECIMAL( 10, 2 ) NOT NULL;  
    ALTER TABLE  {$this->getTable('sales/invoice')} ADD  `carbon_amount` DECIMAL( 10, 2 ) NOT NULL;
    ALTER TABLE  {$this->getTable('sales/invoice')} ADD  `base_carbon_amount` DECIMAL( 10, 2 ) NOT NULL;
    ALTER TABLE  {$this->getTable('sales/creditmemo')} ADD  `carbon_amount` DECIMAL( 10, 2 ) NOT NULL;
    ALTER TABLE  {$this->getTable('sales/creditmemo')} ADD  `base_carbon_amount` DECIMAL( 10, 2 ) NOT NULL;   
");

$installer->endSetup();
