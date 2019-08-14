<?php

/**
 * @method Gaia_Carbon_Model_Api_Client setMethod()
 * @method string getMethod()
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_Model_Api_Client
    extends Mage_Core_Model_Abstract
{
    /**
     * The Carbon Offset API URL
     */
    const SERVICE_URL_SANDBOX = "http://test.co2counter.com.au/api/api3.php";
    
    const SERVICE_URL_PRODUCTION = "http://co2counter.com.au/api/api3.php";
    const SIGNUP_URL_PRODUCTION  = "http://co2counter.com.au/api/api3.register.php";
    
    /**
     * The supported methods of communication with Gaia API
     */
    const METHOD_JSON = 'json';
    
    /**
     * The API connector http client
     * 
     * @var Varien_Http_Client
     */
    protected $_client = null;
    
    
    public function _construct() {
	parent::_construct();
	$this->_initialize();
    }
    
    /**
     * Prepare client for use and set default properties
     * 
     * @return \Gaia_Carbon_Model_Api_Client
     */
    protected function _initialize()
    {
	return $this->_prepareClient()->setMethod(self::METHOD_JSON);
    }
     
    /**
     * 
     * @return \Gaia_Carbon_Model_Api_Client
     */
    protected function _prepareClient()
    {
	if (!$this->_client) {
	    $this->_client = new Varien_Http_Client();
	    $this->_client->setConfig(array('maxredirects' => 0, 'timeout' => 30));
	}
	return $this;
    }
    
    /**
     * Calls API to calculate carbon offset charges for given request
     * 
     * @param array $request
     * @return array
     */
    public function calculate($request)
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
	if (!$this->_client) {
	    return false;
	}
	try {
	    
	    $this->_client
		    ->setUri($this->_prepareServiceUrl())
		    ->setParameterGet('json', Mage::helper('core')->jsonEncode($request));
	    /**
	     * Getting response via raw body and decoding coz of a bug in Zend yielding
	     * exception when ->getBody() is used to decode and return response
	     */
	    Mage::helper('carbon')->log('API Request/Response', true);
	    Mage::helper('carbon')->log(Mage::helper('core')->jsonEncode($request), true);
	    $rawBody = $this->_client->request(Varien_Http_Client::POST)->getRawBody();
	    Mage::helper('carbon')->log($rawBody, true);
	    return Mage::helper('core')->jsonDecode($rawBody, Zend_Json::TYPE_ARRAY);
	} catch (Exception $e) {
	    Mage::logException($e);
	    return false;
	}
    }
    
    /**
     * Signup - new client via API
     * 
     * @param array $data
     * @return boolean
     */
    public function signup($data)
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
	if (!$this->_client) {
	    Mage::throwException('Something went wrong - missing API client class.');
	}
	$this->_client
		->setUri($this->_prepareSignupUrl())
		->setParameterGet('json', Mage::helper('core')->jsonEncode($data));
	/**
	 * Getting response via raw body and decoding coz of a bug in Zend yielding
	 * exception when ->getBody() is used to decode and return response
	 */
	Mage::helper('carbon')->log('API Request/Response', true);
	Mage::helper('carbon')->log(Mage::helper('core')->jsonEncode($data), true);
	$rawBody = $this->_client->request(Varien_Http_Client::POST)->getRawBody();
	Mage::helper('carbon')->log($rawBody, true);
	$response = Mage::helper('core')->jsonDecode($rawBody, Zend_Json::TYPE_ARRAY);
	if (isset($response['co2counter']['status'])) {
	    if ($response['co2counter']['status'] == 1) {
		Mage::throwException('Signup failed: '.reset($response['co2counter']));
	    }
	}
    }
    
    /**
     * Gets service URL for current environment
     * 
     * @return string
     */
    protected function _prepareServiceUrl()
    {
	if (Mage::helper('carbon')->isSandbox()) {
	    return self::SERVICE_URL_SANDBOX;
	}
	return self::SERVICE_URL_PRODUCTION;
    }
    
    /**
     * Gets production signup URL
     * 
     * @return string
     */
    protected function _prepareSignupUrl()
    {
	return self::SIGNUP_URL_PRODUCTION;
    }
}