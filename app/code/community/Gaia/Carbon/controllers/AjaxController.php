<?php
/**
 * Description of class...
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_AjaxController
    extends Mage_Core_Controller_Front_Action
{
    public function addcarbonAction()
    {
	try {
	    $status = (boolean)$this->getRequest()->getPost('status');
	    Mage::getSingleton('checkout/session')->setCustomerPaysForCarbon($status);
	    Mage::getSingleton('checkout/session')->getQuote()
		    ->setTotalsCollectedFlag(false)
		    ->collectTotals();
		    
	    $block = Mage::app()->getLayout()
		->createBlock('checkout/cart_totals', 'checkout.cart.totals')
		->setTemplate('checkout/cart/totals.phtml');
	    
	    echo $block->toHtml();
	} catch (Exception $e) {
	    
	}
    }
}
