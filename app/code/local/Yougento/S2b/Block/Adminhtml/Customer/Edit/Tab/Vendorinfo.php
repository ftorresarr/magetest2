<?php

/**
 * Adminhtml customer action tab
 *
 */
class Yougento_S2b_Block_Adminhtml_Customer_Edit_Tab_Vendorinfo 
 extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{



    public function __construct()
    {
       
       parent::__construct();
    }




  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldsetcp1 = $form->addFieldset('custom_form_cp1', array('legend'=>Mage::helper('customer')->__('General Details')));

      $fieldsetcp1->addField('cpname', 'text', array(
          'label'     => Mage::helper('customer')->__('Name'),
          'name'      => 'cpname',
           'required'  => false,
          
      ));

      $fieldsetcp1->addField('cpphone', 'text', array(
          'label'     => Mage::helper('customer')->__('Phone'),
          'name'      => 'cpphone',
           'required'  => false,
        
      ));

      $fieldsetcp1->addField('cpemail', 'text', array(
          'label'     => Mage::helper('customer')->__('Email'),
           'name'      => 'cpemail',
           'required'  => false,
    
      ));

      $fieldsetcp1->addField('cplogo', 'image', array(
          'label'     => Mage::helper('customer')->__('Company logo'),
          'name'      => 'cplogo',
          'required'  => false,
      ));

     $fieldsetcp1->addField('cppicture', 'image', array(
          'label'     => Mage::helper('customer')->__('Your Picture'),
          'required'  => false,
          'name'      => 'cppicture',
     ));


     $fieldsetcp2 =$form->addFieldset('custom_form_cp2', array('legend'=>Mage::helper('customer')->__('Location Information')));

     $fieldsetcp2->addField('cpaddress', 'text', array(
          'label'     => Mage::helper('customer')->__('Address'),
          'required'  => false,
          'name'      => 'cpaddress',
      ));
     
     $fieldsetcp2->addField('cpcity', 'text', array(
          'label'     => Mage::helper('customer')->__('City'),
          'required'  => false,
          'name'      => 'cpcity',
      ));
     
     $fieldsetcp2->addField('cpcountry', 'text', array(
          'label'     => Mage::helper('customer')->__('Country'),
          'required'  => false,
          'name'      => 'cpcountry',
      ));
     
     $fieldsetcp2->addField('cpzip', 'text', array(
          'label'     => Mage::helper('customer')->__('Zip'),
          'required'  => false,
          'name'      => 'cpzip',
      ));
    
     $customer = Mage::registry('current_customer');

     $form->setValues(Mage::registry('current_customer')->getData());

      return parent::_prepareForm();
  }



  
    

    /**
     * Return Tab label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Vendor Information');
    }

    /**
     * Return Tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Vendor Information');
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        $customer = Mage::registry('current_customer');
        return (bool)$customer->getId();
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Defines after which tab, this tab should be rendered
     *
     * @return string
     */
    public function getAfter()
    {
        return 'tags';
    }


  
}