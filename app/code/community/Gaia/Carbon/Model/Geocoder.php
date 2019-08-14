<?php

/**
 * @method Gaia_Carbon_Model_Geocoder setMethod()
 * @method Gaia_Carbon_Model_Geocoder setAddress()
 * 
 * @method string getMethod()
 * @method string getAddress()
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_Model_Geocoder 
    extends Mage_Core_Model_Abstract
{
    /**
     * The geocoding service URL (google API)
     */
    const SERVICE_URL = "http://maps.googleapis.com/maps/api/geocode/";
    
    /**
     * The supported methods of communication with google API
     */
    const METHOD_JSON = 'json'; //preferred and used by default
    const METHOD_XML  = 'xml'; //not implemented (future use)
    
    /**
     * The HTTP Client
     * 
     * @var Varien_Http_Client
     */
    protected $_client = null;
    
    public function _construct() {
	parent::_construct();
	$this->_initialize();
    }
    
    /**
     * Initialize Geocoder
     * 
     * @return \Gaia_Carbon_Model_Geocoder 
     */
    protected function _initialize()
    {
	return $this->_prepareClient()->setMethod(self::METHOD_JSON);
    }
    
    /**
     * Initializes http client to communicate with Google Api
     * 
     * @return \Gaia_Carbon_Model_Geocoder 
     */
    protected function _prepareClient()
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
	if(!$this->_client) {
	    $this->_client = new Varien_Http_Client();
	    $this->_client->setConfig(array('maxredirects' => 0, 'timeout' => 30));
	}
	return $this;
    }
    
    /**
     * Returns latitude and longitude of geocoded address
     * 
     * @return array or false on failure
     */
    public function geocode()
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
	if (!$this->_validate()) {
	    return false;
	}
	try {
	    $this->_client
		    ->setUri(self::SERVICE_URL.$this->getMethod())		    
		    ->setParameterGet('address', $this->getAddress())
		    ->setParameterGet('sensor', 'false');
	    /**
	     * Getting response via raw body and decoding coz of a bug in Zend yielding
	     * exception when ->getBody() is used to decode and return response
	     */
	    $rawBody = $this->_client->request(Varien_Http_Client::GET)->getRawBody();	    
	    $response = Mage::helper('core')->jsonDecode($rawBody, Zend_Json::TYPE_ARRAY);
	    
	    $lat = $lng = false;
	    if (isset($response['results'][0]['geometry']['location']['lat'])) {
		$lat = (string)$response['results'][0]['geometry']['location']['lat'];
	    }
	    if (isset($response['results'][0]['geometry']['location']['lng'])) {
		$lng = (string)$response['results'][0]['geometry']['location']['lng'];
	    }
	    return array('lat' => $lat, 'lng' => $lng);
	} catch (Exception $e) {
	    Mage::logException($e);	    
	    return false;
	}
    }
    
    /**
     * Validate request before making the call to geocoder service
     * 
     * @return boolean
     */
    protected function _validate()
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
	if (!$this->getAddress()) {
	    Mage::helper('carbon')->log(__METHOD__.': validation failed - missing address.');
	    return false;
	}
	return true;
    }
}
