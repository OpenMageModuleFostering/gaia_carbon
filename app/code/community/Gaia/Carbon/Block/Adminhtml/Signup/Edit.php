<?php
/**
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_Block_Adminhtml_Signup_Edit 
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    
    protected $_blockGroup = 'carbon';
    protected $_controller = 'adminhtml_signup';
    
    public function __construct()
    {
        parent::__construct();
        $this->removeButton('delete');
	$this->removeButton('back');
	$this->removeButton('reset');
	$this->_updateButton('save', 'label', Mage::helper('carbon')->__('Sign Up'));
    }

    /**
     * Getter for form header text
     *
     * @return string
     */
    public function getHeaderText()
    {
	return Mage::helper('carbon')->__('Sign Up');
    }
    
    /**
     * Get form action URL
     *
     * @return string
     */
    public function getFormActionUrl()
    {
        if ($this->hasFormActionUrl()) {
            return $this->getData('form_action_url');
        }
        return $this->getUrl('*/' . $this->_controller . '/signup');
    }
    
}
