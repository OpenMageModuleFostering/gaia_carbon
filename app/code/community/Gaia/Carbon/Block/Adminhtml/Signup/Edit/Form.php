<?php
/**
 * 
 * @category    Gaia
 * @package     Gaia_Carbon
 * @author      CZ Digital Team <martin.novak@czdigital.com.au>
 */
class Gaia_Carbon_Block_Adminhtml_Signup_Edit_Form 
    extends Mage_Adminhtml_Block_Widget_Form
{
    
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/signup'),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            )
        );

        $this->setForm($form);
        $form_data = Mage::registry('carbon_signup_form_data');
	
        $fieldset = $form->addFieldset('login', array(
            'legend'    => Mage::helper('carbon')->__('Name & Contact Details')
        ));
	
	$fieldset->addField('title', 'text', array(
            'name'      => 'title',
            'label'     => Mage::helper('carbon')->__('Title'),
            'required'  => false,
        ));
	
	$fieldset->addField('name', 'text', array(
            'name'      => 'name',
            'label'     => Mage::helper('carbon')->__('Full Name'),
            'required'  => true,
        ));
	
	$fieldset->addField('email', 'text', array(
            'name'      => 'email',
            'label'     => Mage::helper('carbon')->__('Email Address'),
            'required'  => true,
	    'class'	=> 'validate-email'
        ));
	
	$fieldset->addField('phone', 'text', array(
            'name'      => 'phone',
            'label'     => Mage::helper('carbon')->__('Phone'),
            'required'  => false,
	    'class'	=> 'validate-digits'
        ));
	
	$fieldset->addField('mobile', 'text', array(
            'name'      => 'mobile',
            'label'     => Mage::helper('carbon')->__('Mobile'),
            'required'  => false,
	    'class'	=> 'validate-digits'
        ));
	
	$fieldset->addField('company', 'text', array(
            'name'      => 'company',
            'label'     => Mage::helper('carbon')->__('Company Name'),
            'required'  => false
        ));

        if ($form_data) {
            $form->setValues($form_data);
        }
        $form->setUseContainer(true);
        return parent::_prepareForm();
    }
    
}