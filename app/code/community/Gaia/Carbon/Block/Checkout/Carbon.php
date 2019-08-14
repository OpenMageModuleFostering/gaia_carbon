<?php

/**
 * Description of class...
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_Block_Checkout_Carbon
    extends Mage_Checkout_Block_Onepage_Abstract
{
    const CARBON_URL = 'gaiapartnership.com/services/page7/carbon_offsets.html';
    protected $_carbonCopy = 'The Gaia Partnership\'s CO2counter automatically calculates the carbon emissions from the transportation of your goods. The small cost of offsetting is then shown in within the cart.';
    
    public function isEnabled()
    {
	return Mage::helper('carbon')->isEnabled();
    }
    
    public function isOptional()
    {
	return Mage::helper('carbon')->getApplyMode()
		=== Gaia_Carbon_Model_System_Config_Source_Mode::OPTIONAL;
    }
    
    public function isCarbonSelected()
    {
	return Mage::getSingleton('checkout/session')->getCustomerPaysForCarbon() == true;
    }
    
    public function getCarbonDescription()
    {
	return $this->_carbonCopy;
    }
    
    public function getCarbonAmount()
    {
	if ($amount = $this->getQuote()->getCarbonAmount()) {
	    return $this->formatCurrency($amount);
	}
	return '';
    }
    
    public function getCarbonUrl()
    {
	if (Mage::app()->getStore()->isCurrentlySecure()) {
	    return 'https://'.self::CARBON_URL;
	}
	return 'http://'.self::CARBON_URL;
    }
    
    /**
     * Gets currency code (by order store)
     * 
     * @return string
     */
    public function getStoreCurrencyCode()
    {
	return $this->getQuote()->getStoreCurrencyCode();
    }
    
    /**
     * Gets symbol for store currency code
     * 
     * @return string
     */
    public function getCurrencySymbol()
    {
	return Mage::app()->getLocale()->currency($this->getStoreCurrencyCode())->getSymbol();
    }
    
    /**
     * Format currency
     *  
     * @param float $price
     * @return string
     */
    public function formatCurrency($price)
    {
        return Mage::helper('core')->currency($price);
    }
}
