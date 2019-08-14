<?php

/**
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_Model_Checkout_Type_Onepage
    extends Mage_Checkout_Model_Type_Onepage
{
    /**
     * Specify quote shipping method
     *
     * @param   string $shippingMethod
     * @return  array
     */
    public function saveShippingMethod($shippingMethod)
    {
	$this->saveCarbonOffset();
	return parent::saveShippingMethod($shippingMethod);
    }
    
    /**
     * Save carbon offset customer selection
     * 
     * @return \Gaia_Carbon_Model_Checkout_Type_Onepage
     */
    public function saveCarbonOffset()
    {
	$carbon = Mage::app()->getRequest()->getParam('add-carbon', null);
	if ($carbon) {
	    $this->_checkoutSession->setCustomerPaysForCarbon(true);
	} else {
	    $this->_checkoutSession->unsCustomerPaysForCarbon();
	}
	return $this;
    }
}
