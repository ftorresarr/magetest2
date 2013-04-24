<?php 
	class Yougento_S2b_Block_Rewrite_Editcustomer extends Mage_Adminhtml_Block_Customer_Edit_Tab_Account
	{
	    public function __construct()
    {
        parent::__construct();
    }

    public function initForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('_account');
        $form->setFieldNameSuffix('account');
				$form->setAfterElementHtml('<script>
            function modifyTargetElement(checkboxElem){
                if(checkboxElem.checked){
                    $("target_element").disabled=true;
                }
                else{
                    $("target_element").disabled=false;
                }');
        $customer = Mage::registry('current_customer');

        /* @var $customerForm Mage_Customer_Model_Form */
        $customerForm = Mage::getModel('customer/form');
        $customerForm->setEntity($customer)
            ->setFormCode('adminhtml_customer')
            ->initDefaultValues();

        $fieldset = $form->addFieldset('base_fieldset',
            array('legend'=>Mage::helper('customer')->__('Account Information'))
        );

        $attributes = $customerForm->getAttributes();
        foreach ($attributes as $attribute) {
            $attribute->unsIsVisible();
        }
        unset($attributes['suffix']);
        unset($attributes['prefix']);
        unset($attributes['lastname']);
        unset($attributes['firstname']);
        unset($attributes['email']);
        unset($attributes['taxvat']);
        unset($attributes['gender']);
        unset($attributes['middlename']);
        unset($attributes['dob']);
        $this->_setFieldset($attributes, $fieldset);
 /*       $fieldset->removeField('suffix');
        $fieldset->removeField('prefix');
        $fieldset->removeField('middlename');*/
        
        if ($customer->getId()) {
            $form->getElement('website_id')->setDisabled('disabled');
            $form->getElement('created_in')->setDisabled('disabled');
        } else {
            $fieldset->removeField('created_in');
        }
		$fieldset->removeField('disable_auto_group_change');
//        if (Mage::app()->isSingleStoreMode()) {
//            $fieldset->removeField('website_id');
//            $fieldset->addField('website_id', 'hidden', array(
//                'name'      => 'website_id'
//            ));
//            $customer->setWebsiteId(Mage::app()->getStore(true)->getWebsiteId());
//        }

        $customerStoreId = null;
        if ($customer->getId()) {
            $customerStoreId = Mage::app()->getWebsite($customer->getWebsiteId())->getDefaultStore()->getId();
        }

        $customerData = $form->addFieldset('customer_fieldset',
        		array('legend'=>Mage::helper('customer')->__('Customer Details'))
        );
        
        $attributes = $customerForm->getAttributes();
        foreach ($attributes as $attribute) {
        	$attribute->unsIsVisible();
        }
        unset($attributes['suffix']);
        unset($attributes['prefix']);
        unset($attributes['created_at']);
        unset($attributes['website_id']);
        unset($attributes['created_in']);
        unset($attributes['group_id']);
        unset($attributes['middlename']);
        
        $this->_setFieldset($attributes, $customerData);
        
        if ($customer->getId()) {
            if (!$customer->isReadonly()) {
                // add password management fieldset
                $newFieldset = $form->addFieldset(
                    'password_fieldset',
                    array('legend'=>Mage::helper('customer')->__('Password Management'))
                );
                // New customer password
                $field = $newFieldset->addField('new_password', 'text',
                    array(
                        'label' => Mage::helper('customer')->__('New Password'),
                        'name'  => 'new_password',
                        'class' => 'validate-new-password'
                    )
                );
                $field->setRenderer($this->getLayout()->createBlock('adminhtml/customer_edit_renderer_newpass'));

                // prepare customer confirmation control (only for existing customers)
                $confirmationKey = $customer->getConfirmation();
                if ($confirmationKey || $customer->isConfirmationRequired()) {
                    $confirmationAttribute = $customer->getAttribute('confirmation');
                    if (!$confirmationKey) {
                        $confirmationKey = $customer->getRandomConfirmationKey();
                    }
                    $element = $fieldset->addField('confirmation', 'select', array(
                        'name'  => 'confirmation',
                        'label' => Mage::helper('customer')->__($confirmationAttribute->getFrontendLabel()),
                    ))->setEntityAttribute($confirmationAttribute)
                        ->setValues(array('' => 'Confirmed', $confirmationKey => 'Not confirmed'));

                    // prepare send welcome email checkbox, if customer is not confirmed
                    // no need to add it, if website id is empty
                    if ($customer->getConfirmation() && $customer->getWebsiteId()) {
                        $fieldset->addField('sendemail', 'checkbox', array(
                            'name'  => 'sendemail',
                            'label' => Mage::helper('customer')->__('Send Welcome Email after Confirmation')
                        ));
                        $customer->setData('sendemail', '1');
                    }
                }
            }
        } else {
            $newFieldset = $form->addFieldset(
                'password_fieldset',
                array('legend'=>Mage::helper('customer')->__('Password Management'))
            );
            $field = $newFieldset->addField('password', 'text',
                array(
                    'label' => Mage::helper('customer')->__('Password'),
                    'class' => 'input-text required-entry validate-password',
                    'name'  => 'password',
                    'required' => true
                )
            );
            $field->setRenderer($this->getLayout()->createBlock('adminhtml/customer_edit_renderer_newpass'));

            // prepare send welcome email checkbox
            $fieldset->addField('sendemail', 'checkbox', array(
                'label' => Mage::helper('customer')->__('Send Welcome Email'),
                'name'  => 'sendemail',
                'id'    => 'sendemail',
            ));
            $customer->setData('sendemail', '1');
            if (!Mage::app()->isSingleStoreMode()) {
                $fieldset->addField('sendemail_store_id', 'select', array(
                    'label' => $this->helper('customer')->__('Send From'),
                    'name' => 'sendemail_store_id',
                    'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm()
                ));
            }
        }

        // make sendemail and sendmail_store_id disabled, if website_id has empty value
        $isSingleMode = Mage::app()->isSingleStoreMode();
        $sendEmailId = $isSingleMode ? 'sendemail' : 'sendemail_store_id';
        $sendEmail = $form->getElement($sendEmailId);

        $prefix = $form->getHtmlIdPrefix();
        if ($sendEmail) {
            $_disableStoreField = '';
            if (!$isSingleMode) {
                $_disableStoreField = "$('{$prefix}sendemail_store_id').disabled=(''==this.value || '0'==this.value);";
            }
            $sendEmail->setAfterElementHtml(
                '<script type="text/javascript">'
                . "
                $('{$prefix}website_id').disableSendemail = function() {
                    $('{$prefix}sendemail').disabled = ('' == this.value || '0' == this.value);".
                    $_disableStoreField
                ."}.bind($('{$prefix}website_id'));
                Event.observe('{$prefix}website_id', 'change', $('{$prefix}website_id').disableSendemail);
                $('{$prefix}website_id').disableSendemail();
                "
                . '</script>'
            );
        }

        if ($customer->isReadonly()) {
            foreach ($customer->getAttributes() as $attribute) {
                $element = $form->getElement($attribute->getAttributeCode());
                if ($element) {
                    $element->setReadonly(true, true);
                }
            }
        }


        $form->setValues($customer->getData());
        $usrid = $this->getRequest()->getParam('id');
        $loadusr = Mage::getModel("customer/customer")->load($usrid)->getEmail();
        $checkadmn = Mage::getModel("admin/user")->load($loadusr,'username');
        if($checkadmn->getUsername()==$loadusr)
        {
        	$acct = '2';
        }else
        {
        	$acct = '1';
        }
        
        $fieldset = $form->addFieldset('new_fieldset',
        		array('legend'=>Mage::helper('customer')->__('Vendor options'))
        );
       $fieldset->addField('acctype1','select',
				array(
					'label' => Mage::helper('customer')->__('Account type'),
					'name' 	=> 'acctype1',
					'value'	=> $acct,
					'values'=> array('-1'=>'Please Select..','1' => 'Simple Customer','2' => 'Vendor Customer'),
        			'required' => false
				)
		);

    /*    $fieldset->addField('acctype', 'select',
        		array(
        				'label' => Mage::helper('customer')->__('Account type'),
        				'name'  => 'acctype',
        				'value' => $acct,
        				'values' => array('-1'=>'Please Select..','1' => 'Simple Customer','2' => 'Vendor Customer'),
        				'required' => false
        		)
        );*/
		if ($customer->getId()){
        $fieldset->addField('oldemail', 'hidden',
        		array(
        				'label' => Mage::helper('customer')->__('Account type'),
        				'class' => 'input-text',
        				'name'  => 'acctype',
        				'value' => $customer->getEmail(),
        				'required' => false
        		)
        );
		}
        $this->setForm($form);
        return $this;
    }

    /**
     * Return predefined additional element types
     *
     * @return array
     */
    protected function _getAdditionalElementTypes()
    {
        return array(
            'file'      => Mage::getConfig()->getBlockClassName('adminhtml/customer_form_element_file'),
            'image'     => Mage::getConfig()->getBlockClassName('adminhtml/customer_form_element_image'),
            'boolean'   => Mage::getConfig()->getBlockClassName('adminhtml/customer_form_element_boolean'),
        );
    }
}
		
?>
