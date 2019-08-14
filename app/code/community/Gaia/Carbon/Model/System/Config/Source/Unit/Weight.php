<?php
/**
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_Model_System_Config_Source_Unit_Weight
    extends Gaia_Carbon_Model_System_Config_Source_Abstract
{
    const GRAMS     = 'Grams';
    const KILOGRAMS = 'Kilograms';
    const OUNCES    = 'Ounces';
    const POUNDS    = 'Pounds';
    
    protected function _setupOptions()
    {
        $this->_options = array(
            self::GRAMS     => 'Grams',
            self::KILOGRAMS => 'Kilograms',
            self::OUNCES    => 'Ounces',
            self::POUNDS    => 'Pounds',
        );
    }
}
