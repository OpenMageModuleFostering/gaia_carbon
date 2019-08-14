<?php

/**
 * @method Gaia_Carbon_Model_Signup setSignupData()
 * @method array getSignupData()
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_Model_Signup 
    extends Varien_Object
{
    /**
     * Customer sign up action via the API
     * 
     * @return array|boolean on failure
     * @throws exception
     */
    public function signup()
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
	if (!$this->_validate()) {
	    Mage::throwException('Not a valid request. Please check entered data and try again.');
	}
	$request = $this->_prepareRequest();	    
	$api = Mage::getModel('carbon/api_client');
	/* @var $api Gaia_Carbon_Model_Api_Client */
	$api->signup($request);	    	    
    }
    
    /**
     * Prepares API request array from available data
     * 
     * @return array
     */
    protected function _prepareRequest()
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	$data = $this->getSignupData();
	return array(
	    'key' => Mage::helper('carbon')->getSigApiKey(),
	    'hash' => Mage::helper('carbon')->getSigApiHash(),
	    'name' => trim($data['name']),
	    'email' => trim($data['email']),
	    'output' => 'full',
	    'title' => trim($data['title']),
	    'phoneOffice' => trim($data['phone']),
	    'mobile' => trim($data['mobile']),
	    'companyName' => trim($data['company'])
	);
    }
    
    /**
     * Validate request before making the call to carbon signup service
     * 
     * @return boolean
     */
    protected function _validate()
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	$data = $this->getSignupData();
	
	return 
	    !empty($data) && is_array($data) &&
	    array_key_exists('name', $data) && strlen(trim($data['name'])) &&
	    array_key_exists('email', $data) && strlen(trim($data['email']));
    }
}
