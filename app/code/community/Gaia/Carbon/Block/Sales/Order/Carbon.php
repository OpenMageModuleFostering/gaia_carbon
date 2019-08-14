<?php
/**
 * Description of class...
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */

class Gaia_Carbon_Block_Sales_Order_Carbon 
    extends Mage_Core_Block_Template
{

    /**
     * Get order store object
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    /**
     * Get totals source object
     *
     * @return Mage_Sales_Model_Order
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * Initialize fee totals
     *
     * @return Gaia_Carbon_Block_Sales_Order_Carbon
     */
    public function initTotals()
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
        if ((float) $this->getOrder()->getBaseCarbonAmount()) {
            $source = $this->getSource();
            $value  = $source->getCarbonAmount();

            $this->getParentBlock()->addTotal(new Varien_Object(array(
                'code'   => 'carbon',
                'strong' => false,
                'label'  => Mage::helper('carbon')->__('Carbon Offset'),
                'value'  => $value
            )));
        }

        return $this;
    }
}