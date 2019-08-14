<?php

/**
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_Adminhtml_SignupController 
    extends Mage_Adminhtml_Controller_Action 
{
    public function indexAction()
    {
	$this->loadLayout()->renderLayout();
    }
    
    public function signupAction()
    {
	if ($data = $this->getRequest()->getPost()) {
	    Mage::register('carbon_signup_form_data', $data, true);
	    try {
		$signup = Mage::getModel('carbon/signup');
		$signup->setSignupData($data)->signup();
		$this->_getSession()->addSuccess($this->__('You have successfully signed up. Please check your nominated email address for further details.'));
	    } catch (Exception $e) {
		Mage::helper('carbon')->log($e->getMessage());
		$this->_getSession()->addError($this->__($e->getMessage()));
		$this->_redirect('*/*/'); 
		return;
	    }
	} else {
	    $this->_getSession()->addError($this->__('Please fill in all required fields.'));
	    $this->_redirect('*/*/'); 
	    return;
	}
	$this->_redirectUrl(Mage::helper('adminhtml')->getUrl('adminhtml/system_config/edit/section/carbon'));
    }
}
