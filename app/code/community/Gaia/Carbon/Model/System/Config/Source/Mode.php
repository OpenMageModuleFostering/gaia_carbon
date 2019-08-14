<?php
/**
 * Description of class...
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_Model_System_Config_Source_Mode
    extends Gaia_Carbon_Model_System_Config_Source_Abstract
{
    
    const OPTIONAL	=     '0';
    const MANDATORY	=     '5';
    
    protected function _setupOptions()
    {
        $this->_options = array(
            self::OPTIONAL   => Mage::helper('carbon')->__('Optional'),
            self::MANDATORY  => Mage::helper('carbon')->__('Mandatory'),
        );
    } 
}