<?php
/**
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_Model_Sales_Quote_Address_Total_Carbon 
    extends Mage_Sales_Model_Quote_Address_Total_Abstract
{

    protected $_code = 'carbon';

    /**
     * Collect carbon amount for address
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @return Gaia_Carbon_Model_Sales_Quote_Address_Total_Carbon
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
        parent::collect($address);

        $this->_setAmount(0);
        $this->_setBaseAmount(0);

        $items = $this->_getAddressItems($address);
        if (!count($items)) {
            return $this;
        }

        $quote = $address->getQuote();

        if (Gaia_Carbon_Model_Carbon::canApply($address)) {
            $exist_amount = $quote->getCarbonAmount();
            $carbon = Gaia_Carbon_Model_Carbon::getCarbon($address);
            $balance = $carbon - $exist_amount;

            $address->setCarbonAmount($balance);
            $address->setBaseCarbonAmount($balance);

            $quote->setCarbonAmount($balance);

            $address->setGrandTotal($address->getGrandTotal() + $address->getCarbonAmount());
            $address->setBaseGrandTotal($address->getBaseGrandTotal() + $address->getBaseCarbonAmount());
        }

        return $this;
    }

    /**
     * Add carbon information to address
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @return Gaia_Carbon_Model_Sales_Quote_Address_Total_Carbon
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
	if (Gaia_Carbon_Model_Carbon::canApply($address)) {
	    $amount = $address->getCarbonAmount();
	    if (!$amount) {
		return $this;
	    }
	    $address->addTotal(array(
		'code' => $this->getCode(),
		'title' => $this->_getTitle(),
		'value' => $amount
	    ));
	}
        return $this;
    }
    
    protected function _getTitle()
    {	
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	return Mage::helper('carbon')->__('Carbon Offset');	
    }

}