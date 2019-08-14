<?php
/**
 * Description of class...
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_Model_System_Config_Source_Environment 
    extends Gaia_Carbon_Model_System_Config_Source_Abstract
{
    
    const SANDBOX	=     '0';
    const PRODUCTION	=     '5';
    
    protected function _setupOptions()
    {
        $this->_options = array(
            self::SANDBOX     => Mage::helper('carbon')->__('Sandbox'),
            self::PRODUCTION  => Mage::helper('carbon')->__('Production'),
        );
    } 
}