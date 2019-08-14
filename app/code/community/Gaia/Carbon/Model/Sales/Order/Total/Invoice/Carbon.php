<?php
/**
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_Model_Sales_Order_Total_Invoice_Carbon 
    extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{

    /**
     * Collect invoice total
     *
     * @param Mage_Sales_Model_Order_Invoice $invoice
     * @return Gaia_Carbon_Model_Sales_Order_Total_Invoice_Carbon
     */
    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
	Mage::helper('carbon')->log('ENTERING: '.__METHOD__, true);
	
        $order = $invoice->getOrder();

        $carbonAmountLeft = $order->getCarbonAmount() - $order->getCarbonAmountInvoiced();
        $baseCarbonAmountLeft = $order->getBaseCarbonAmount() - $order->getBaseCarbonAmountInvoiced();

        if (abs($baseCarbonAmountLeft) < $invoice->getBaseGrandTotal()) {
            $invoice->setGrandTotal($invoice->getGrandTotal() + $carbonAmountLeft);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseCarbonAmountLeft);
        } else {
            $carbonAmountLeft = $invoice->getGrandTotal() * -1;
            $baseCarbonAmountLeft = $invoice->getBaseGrandTotal() * -1;

            $invoice->setGrandTotal(0);
            $invoice->setBaseGrandTotal(0);
        }

        $invoice->setCarbonAmount($carbonAmountLeft);
        $invoice->setBaseCarbonAmount($baseCarbonAmountLeft);

        return $this;
    }

}