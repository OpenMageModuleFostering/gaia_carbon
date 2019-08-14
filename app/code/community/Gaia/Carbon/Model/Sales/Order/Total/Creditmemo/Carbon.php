<?php

/**
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_Model_Sales_Order_Total_Creditmemo_Carbon 
    extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract 
{

    /**
     * Collect credit memo total
     *
     * @param Mage_Sales_Model_Order_Creditmemo $creditmemo
     * @return Gaia_Carbon_Model_Sales_Order_Total_Creditmemo_Carbon
     */
    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo) 
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
	$order = $creditmemo->getOrder();

	if ($order->getCarbonAmountInvoiced() > 0) {

	    $carbonAmountLeft = $order->getCarbonAmountInvoiced() - $order->getCarbonAmountRefunded();
	    $baseCarbonAmountLeft = $order->getBaseCarbonAmountInvoiced() - $order->getBaseCarbonAmountRefunded();

	    if ($baseCarbonAmountLeft > 0) {
		$creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $carbonAmountLeft);
		$creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseCarbonAmountLeft);
		$creditmemo->setCarbonAmount($carbonAmountLeft);
		$creditmemo->setBaseCarbonAmount($baseCarbonAmountLeft);
	    }
	} else {

	    $carbonAmount = $order->getCarbonAmountInvoiced();
	    $basecarbonAmount = $order->getBaseCarbonAmountInvoiced();

	    $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $carbonAmount);
	    $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $basecarbonAmount);
	    $creditmemo->setCarbonAmount($carbonAmount);
	    $creditmemo->setBaseCarbonAmount($basecarbonAmount);
	}

	return $this;
    }

}
