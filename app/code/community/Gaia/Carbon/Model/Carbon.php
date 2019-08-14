<?php
/**
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_Model_Carbon extends Varien_Object
{

    /**
     * Retrieve Carbon Amount
     *
     * @static
     * @param Mage_Sales_Model_Quote_Address $address
     * @param boolean $sale
     * @return float
     */
    public static function getCarbon($address, $sale = false)
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
	try {
	    $originLocation = Mage::getModel('carbon/location');
	    /* @var $originLocation Gaia_Carbon_Model_Location */
	    $originLocation
		    ->setStreet(Mage::helper('carbon')->getOriginStreet())
		    ->setCity(Mage::helper('carbon')->getOriginCity())
		    ->setState(Mage::helper('carbon')->getOriginState())
		    ->setPostcode(Mage::helper('carbon')->getOriginPostcode())
		    ->setCountry(Mage::helper('carbon')->getOriginCountryId());
	    $originLocation->geocode();
	    
	    $destinationLocation = Mage::getModel('carbon/location');
	    $destinationLocation
		    ->setStreet($address->getStreetFull())
		    ->setCity($address->getCity())
		    ->setState($address->getRegion())
		    ->setPostcode($address->getPostcode())
		    ->setCountry($address->getCountryId());
	    $destinationLocation->geocode();
	    
	    //get carbon charges for trip
	    $request = Mage::getModel('carbon/api_request');
	    /* @var $request Gaia_Carbon_Model_Api_Request */
	    $response = $request
		->setOrigin($originLocation)
		->setDestination($destinationLocation)
		->setItems($address->getAllVisibleItems())
		->setCustNotes($address->getName())
		->setCustEmail($address->getEmail())
		->setCurrency($address->getQuote()->getStoreCurrencyCode())
		->setIsSale($sale)
		->calculate();
	    if ($response && isset($response['co2counter']['offset_post_gst'])) {
		return (float)$response['co2counter']['offset_post_gst'];
	    }
	    
	} catch (Exception $e) {
	    Mage::logException($e);
	}
    }

    /**
     * Check if carbon can be applied
     *
     * @static
     * @param Mage_Sales_Model_Quote_Address $address
     * @return bool
     */
    public static function canApply($address)
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
        if (!Mage::helper('carbon')->isEnabled()) {
	    return false; //module is disabled
	}
	switch (Mage::helper('carbon')->getApplyMode()) {
	    case Gaia_Carbon_Model_System_Config_Source_Mode::MANDATORY:
		return true; // module enabled and carbon set to mandatory
	    case Gaia_Carbon_Model_System_Config_Source_Mode::OPTIONAL:
		if (Mage::getSingleton('checkout/session')->getCustomerPaysForCarbon() == true) {
		    return true; // module enabled and customer choose to pay for carbon
		}
		break;
	}
	return false;
    }

}