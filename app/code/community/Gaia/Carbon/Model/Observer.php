<?php
/**
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */

class Gaia_Carbon_Model_Observer
{

    /**
     * Send sale request to carbon API when an order is placed
     * 
     * @param Varien_Event_Observer $observer
     * @return Gaia_Carbon_Model_Observer
     */
    public function orderPlaceAfter(Varien_Event_Observer $observer)
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
	$order = $observer->getEvent()->getOrder();
	/* @var $order Mage_Sales_Model_Order */
	if ($order->getCarbonAmount()) {
	    $quote = Mage::getModel('sales/quote')->load($order->getQuoteId());
	    /* @var $quote Mage_Sales_Model_Quote */
	    Gaia_Carbon_Model_Carbon::getCarbon($quote->getShippingAddress(), true);
	}
	return $this;
    }
    
    /**
     * Set carbon amount invoiced to the order
     *
     * @param Varien_Event_Observer $observer
     * @return Gaia_Carbon_Model_Observer
     */
    public function invoiceSaveAfter(Varien_Event_Observer $observer)
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
        $invoice = $observer->getEvent()->getInvoice();

        if ($invoice->getBaseCarbonAmount()) {
            $order = $invoice->getOrder();
            $order->setCarbonAmountInvoiced($order->getCarbonAmountInvoiced() + $invoice->getCarbonAmount());
            $order->setBaseCarbonAmountInvoiced($order->getBaseCarbonAmountInvoiced() + $invoice->getBaseCarbonAmount());
        }

        return $this;
    }

    /**
     * Set carbon amount refunded to the order
     *
     * @param Varien_Event_Observer $observer
     * @return Gaia_Carbon_Model_Observer
     */
    public function creditmemoSaveAfter(Varien_Event_Observer $observer)
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
        $creditmemo = $observer->getEvent()->getCreditmemo();

        if ($creditmemo->getCarbonAmount()) {
            $order = $creditmemo->getOrder();
            $order->setCarbonAmountRefunded($order->getCarbonAmountRefunded() + $creditmemo->getCarbonAmount());
            $order->setBaseCarbonAmountRefunded($order->getBaseCarbonAmountRefunded() + $creditmemo->getBaseCarbonAmount());
        }

        return $this;
    }

    /**
     * Update PayPal Total
     *
     * @param Varien_Event_Observer $observer
     * @return Gaia_Carbon_Model_Observer
     */
    public function updatePaypalTotal(Varien_Event_Observer $observer)
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
        $cart = $observer->getEvent()->getPaypalCart();
        $cart->updateTotal(Mage_Paypal_Model_Cart::TOTAL_SUBTOTAL, $cart->getSalesEntity()->getCarbonAmount());
        return $this;
    }

}