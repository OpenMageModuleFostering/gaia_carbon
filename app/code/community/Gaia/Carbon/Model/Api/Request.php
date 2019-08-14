<?php

/**
 * @method Gaia_Carbon_Model_Api_Request setMethod()
 * @method Gaia_Carbon_Model_Api_Request setIsSale()
 * 
 * @method string getMethod()
 * @method boolean getIsSale()
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_Model_Api_Request
    extends Mage_Core_Model_Abstract
{
    /**
     * The origin (from) location
     * 
     * @var Gaia_Carbon_Model_Location
     */
    protected $_origin;
    
    /**
     * The destination (to) location
     * 
     * @var Gaia_Carbon_Model_Location
     */
    protected $_destination;
    
    /**
     * The request items (all products in cart)
     */
    protected $_items;
    
    
    public function _construct() {
	parent::_construct();
	$this->_origin = Mage::getModel('carbon/location');
	$this->_destination = Mage::getModel('carbon/location');
    }
    
    /**
     * Set order items
     * 
     * @param mixed $items
     * @return \Gaia_Carbon_Model_Api_Request
     */
    public function setItems($items)
    {
	$this->_items = $items;
	return $this;
    }
    
    /**
     * Set origin location for request
     * 
     * @param Gaia_Carbon_Model_Location $origin
     * @return \Gaia_Carbon_Model_Api_Request
     */
    public function setOrigin(Gaia_Carbon_Model_Location $origin)
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
	$this->_origin = $origin;
	return $this;
    }
    
    /**
     * Set destination location for request
     * 
     * @param Gaia_Carbon_Model_Location $destination
     * @return \Gaia_Carbon_Model_Api_Request
     */
    public function setDestination(Gaia_Carbon_Model_Location $destination)
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
	$this->_destination = $destination;
	return $this;
    }
    
    /**
     * Calculate carbon offset charges for given request
     * 
     * @return array|boolean false on failure
     */
    public function calculate()
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
	if (!$this->_validate()) {
	    Mage::helper('carbon')->log('Not a valid API request.');
	    return false;
	}
	try {
	    $request = $this->_prepareRequest();	    
	    $api = Mage::getModel('carbon/api_client');
	    /* @var $api Gaia_Carbon_Model_Api_Client */
	    return $api->calculate($request);	    	    
	} catch (Exception $e) {
	    Mage::logException($e);	    
	}
	return false;
    }
    
    protected function _prepareRequest()
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
	return array(
	    'key' => Mage::helper('carbon')->getApiKey(),
	    'hash' => Mage::helper('carbon')->getApiHash(),
	    'orn' => '3333',
	    'mass_kg' => Mage::helper('carbon')
		->getWeightInKilograms($this->_getCombinedProductWeight()),
	    'sale' => $this->getIsSale() ? true : false,
	    'currency' => $this->getCurrency(),
	    'cust_notes' => $this->getCustNotes(),
	    'cust_email' => $this->getCustEmail(),
	    'output' => 'full',
	    'hops' => array( 0 => array(
		'type' => 'latlong',
		'lat1' => $this->_origin->getLatitude(),
		'lon1' => $this->_origin->getLongitude(),
		'lat2' => $this->_destination->getLatitude(),
		'lon2' => $this->_destination->getLongitude(),
		'freight_type' => 'GeneralRoad',
		'seq' => 0)
	    )
	);
    }
    
    /**
     * Returns combined weight of items on order
     * 
     * @return float
     */
    protected function _getCombinedProductWeight()
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
	$weight = 0;
	foreach ($this->_items as $item) {
	    if ($item->getParentItem() || $item->getIsVirtual()) {
		continue;
	    }
	    if ($item->getProduct() && $item->getProduct()->isVirtual()) {
		continue;
	    }
	    $weight += $item->getWeight() * $item->getQty();
	}
	return $weight;
    }
    
    /**
     * Validate request before making the call to carbon service
     * 
     * @return boolean
     */
    protected function _validate()
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
	return 
	    $this->_origin->validate() && 
	    $this->_destination->validate();
    }
}