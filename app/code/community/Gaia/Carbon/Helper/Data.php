<?php

/**
 * Description of class...
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_Helper_Data 
    extends Mage_Core_Helper_Abstract 
{
    const LOG_FILENAME	      = 'gaia-carbon.log';
    
    const SANDBOX_KEY	      = '2';
    const SANDBOX_HASH	      = 'sandbox';
    
    const XPATH_ACTIVE	      = 'carbon/general/active';
    const XPATH_ENVIRONMENT   = 'carbon/general/environment';
    const XPATH_APIKEY	      = 'carbon/general/api_key';
    const XPATH_APIHASH	      = 'carbon/general/api_hash';
    const XPATH_SIG_APIKEY    = 'carbon/general/api_sigkey';
    const XPATH_SIG_APIHASH   = 'carbon/general/api_sighash';
    const XPATH_MODE	      = 'carbon/general/mode';
    const XPATH_WEIGHTUNIT    = 'carbon/general/weight_unit';
    const XPATH_LOG_ACTIVE    = 'carbon/log/active';
    const XPATH_LOG_VERBOSE   = 'carbon/log/verbose';
    
    /**
     * Is this plugin enabled?
     *
     * @param mixed $store
     * @return bool
     */
    public function isEnabled($store = null)
    {
        return Mage::getStoreConfigFlag(self::XPATH_ACTIVE, $store);
    }
    
    /**
     * Is this sandbox or production?
     * 
     * @param mixed $store
     * @return boolean
     */
    public function isSandbox($store = null)
    {
	return Mage::getStoreConfig(self::XPATH_ENVIRONMENT, $store) 
		=== Gaia_Carbon_Model_System_Config_Source_Environment::SANDBOX;
    }
    
    /**
     * Returns carbon offset apply mode
     * 
     * @param mixed $store
     * @return string
     */
    public function getApplyMode($store = null)
    {
	return Mage::getStoreConfig(self::XPATH_MODE, $store);
    }
    
    /**
     * Returns Gaia API key
     * 
     * @param mixed $store
     * @return string
     */
    public function getApiKey($store = null)
    {
	if ($this->isSandbox($store)) {
	    return self::SANDBOX_KEY;
	}
	return Mage::getStoreConfig(self::XPATH_APIKEY, $store);
    }
    
    /**
     * Returns Gaia Api hash
     * 
     * @param mixed $store
     * @return string
     */
    public function getApiHash($store = null)
    {
	if ($this->isSandbox($store)) {
	    return self::SANDBOX_HASH;
	}
	return Mage::getStoreConfig(self::XPATH_APIHASH, $store);
    }
    
    /**
     * Returns Gaia API key
     * 
     * @param mixed $store
     * @return string
     */
    public function getSigApiKey($store = null)
    {
	return Mage::getStoreConfig(self::XPATH_SIG_APIKEY, $store);
    }
    
    /**
     * Returns Gaia Api hash
     * 
     * @param mixed $store
     * @return string
     */
    public function getSigApiHash($store = null)
    {
	return Mage::getStoreConfig(self::XPATH_SIG_APIHASH, $store);
    }
    
    /**
     * Returns product weight unit from configuration
     * 
     * @param mixed $store
     * @return string
     */
    public function getWeightUnit($store = null)
    {
	return Mage::getStoreConfig(self::XPATH_WEIGHTUNIT, $store);
    }
    
    /**
     * Is logging enabled?
     * 
     * @param mixed $store
     * @return boolean
     */
    public function isLogEnabled($store = null)
    {
	return Mage::getStoreConfigFlag(self::XPATH_LOG_ACTIVE, $store);
    }
    
    /**
     * Is verbose log option enabled?
     * 
     * @param mixed $store
     * @return boolean
     */
    public function isLogVerbose($store = null)
    {
	return Mage::getStoreConfigFlag(self::XPATH_LOG_VERBOSE, $store);
    }    
    
    /**
     * Wrapper for Magentos' own log function
     * 
     * @param mixed $data
     * @param boolean $verbose
     * @param int $store
     */
    public function log($log, $verbose = false, $store = null) 
    {
	if ($this->isLogEnabled($store)) {
	    if ($verbose === false || ($verbose && $this->isLogVerbose($store))) {		
		Mage::log($log, null, self::LOG_FILENAME, true);
	    }
	}
    }
    
    /**
     * Gets shipping origin address from configuration
     * 
     * @param mixed $store
     * @return string
     */
    public function getOriginAddress($store = null)
    {
	$return = array(
	    Mage::getStoreConfig('shipping/origin/street_line1', $store),
	    Mage::getStoreConfig('shipping/origin/street_line2', $store),
	    Mage::getStoreConfig('shipping/origin/city', $store),
	    $this->getRegionName(Mage::getStoreConfig('shipping/origin/region_id', $store)),
	    Mage::getStoreConfig('shipping/origin/postcode', $store),
	    Mage::getStoreConfig('shipping/origin/country_id', $store),
	);
	return implode(',', array_filter($return));
    }
    
    /**
     * Gets shipping origin street from configuration
     * 
     * @param mixed $store
     * @return string
     */
    public function getOriginStreet($store = null)
    {
	$return = array(
	    Mage::getStoreConfig('shipping/origin/street_line1', $store),
	    Mage::getStoreConfig('shipping/origin/street_line2', $store),
	);
	return implode(',', array_filter($return));
    }
    
    /**
     * Gets shipping origin city from configuration
     * 
     * @param mixed $store
     * @return string
     */
    public function getOriginCity($store = null)
    {
	return trim(Mage::getStoreConfig('shipping/origin/city', $store));
    }
    
    /**
     * Gets shipping origin state from configuration
     * 
     * @param mixed $store
     * @return string
     */
    public function getOriginState($store = null)
    {
	return $this->getRegionName(Mage::getStoreConfig('shipping/origin/region_id', $store));
    }
    
    
    /**
     * Returns region name
     * 
     * @param mixed $region
     * @return string
     */
    public function getRegionName($region)
    {
	if (is_numeric($region)) {
	    return Mage::getModel('directory/region')->load($region)->getName();
	}
	return trim($region);
    }
    
    /**
     * Gets shipping origin postal code from configuration
     * 
     * @param mixed $store
     * @return string
     */
    public function getOriginPostcode($store = null)
    {
	return trim(Mage::getStoreConfig('shipping/origin/postcode', $store));
    }
    
    /**
     * Gets shipping origin country id from configuration
     * 
     * @param mixed $store
     * @return string
     */
    public function getOriginCountryId($store = null)
    {
	return trim(Mage::getStoreConfig('shipping/origin/country_id', $store));
    }
    
    /**
     * Converts given weight from configured unit to kilograms
     * 
     * @param float $value Weight to convert
     * @return float Converted weight in kilograms
     */
    public function getWeightInKilograms($value, $currentUnit = null)
    {
	$value = floatval($value);
	$currentUnit = $currentUnit ? $currentUnit : $this->getWeightUnit();
	//from units as specified in configuration
	switch($currentUnit) {
	    case Gaia_Carbon_Model_System_Config_Source_Unit_Weight::GRAMS:
		return $value * 0.001; 
	    
	    case Gaia_Carbon_Model_System_Config_Source_Unit_Weight::OUNCES: 
		return $value * 0.0283495; 
	    
	    case Gaia_Carbon_Model_System_Config_Source_Unit_Weight::POUNDS: 
		return $value * 0.453592; 
	    
	    default: return $value; 
	}
    }
}