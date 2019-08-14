<?php

/**
 * @method Gaia_Carbon_Model_Api_Location setStreet()
 * @method Gaia_Carbon_Model_Api_Location setCity()
 * @method Gaia_Carbon_Model_Api_Location setState()
 * @method Gaia_Carbon_Model_Api_Location setPostcode()
 * @method Gaia_Carbon_Model_Api_Location setCountry()
 * @method Gaia_Carbon_Model_Api_Location setLatitude()
 * @method Gaia_Carbon_Model_Api_Location setLongitude()
 * 
 * @method string getStreet()
 * @method string getCity()
 * @method string getState()
 * @method string getPostcode()
 * @method string getCountry()
 * @method string getLatitude()
 * @method string getLongitude()
 * 
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_Model_Location 
    extends Varien_Object
{
    /**
     * Tries to geocode location address
     * 
     * @throws Exception If geocoding fails
     * @return \Gaia_Carbon_Model_Location
     */
    public function geocode()
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
	$geo = Mage::getModel('carbon/geocoder');
	/* @var $geo Gaia_Carbon_Model_Geocoder */
	$address = $this->getAddressAsString();
	$geoData = $geo->setAddress($address)->geocode();
	if (!is_array($geoData)) {
	    Mage::throwException(__METHOD__.': failed to geocode location address ('.$address.')');
	}
	$this->setLatitude($geoData['lat'])->setLongitude($geoData['lng']);
	return $this;
    }
    
    /**
     * Gets location address as string
     * 
     * @return string
     */
    public function getAddressAsString()
    {
	$data = array_filter(array(
	    $this->getStreet(),
	    $this->getCity(),
	    $this->getState(),
	    $this->getPostcode(),
	    $this->getCountry()
	));
	
	return implode(', ', $data);
    }
    
    public function validate()
    {
	return strlen($this->getAddressAsString());
    }
}
