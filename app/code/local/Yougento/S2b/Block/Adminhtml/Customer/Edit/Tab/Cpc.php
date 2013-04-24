<?php

/**
 * Adminhtml customer action tab
 *
 */
class Yougento_S2b_Block_Adminhtml_Customer_Edit_Tab_Cpc
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
      $fieldsetcpa = $form->addFieldset('custom_form_cpc', array('legend'=>Mage::helper('customer')->__('CPC')));
 
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
        return $this->__('CPC');
    }

    /**
     * Return Tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('CPC');
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