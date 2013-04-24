<?php
    class Yougento_S2b_Block_Adminhtml_Customeredit_Form
 extends Mage_Adminhtml_Block_Widget_Form
{



    public function __construct()
    {
       
       parent::__construct();
    }




  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
	  $form->setMethod('post');
	  $form->setId('infoform');
	  $form->setAction($this->getUrl('*/adminhtml_details/save'));
      $form->setUseContainer(true);
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
    
     $customerId = Mage::helper('s2b')->getVendorCid();
	 $form->setEnctype('multipart/form-data');
	$this->setForm($form);
     $form->setValues(Mage::getModel('customer/customer')->load($customerId)->getData());

      return parent::_prepareForm();
  }
  }


?>