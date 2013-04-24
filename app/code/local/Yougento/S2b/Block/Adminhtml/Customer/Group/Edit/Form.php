<?php
class Yougento_S2b_Block_Adminhtml_Customer_Group_Edit_Form extends Mage_Adminhtml_Block_Customer_Group_Edit_Form
{
	protected function _prepareLayout()
	{
	        parent::_prepareLayout();
        $form = new Varien_Data_Form();
        $customerGroup = Mage::registry('current_group');

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('customer')->__('Group Information')));

        $validateClass = sprintf('required-entry validate-length maximum-length-%d',
            Mage_Customer_Model_Group::GROUP_CODE_MAX_LENGTH);
        $name = $fieldset->addField('customer_group_code', 'text',
            array(
                'name'  => 'code',
                'label' => Mage::helper('customer')->__('Group Name'),
                'title' => Mage::helper('customer')->__('Group Name'),
                'note'  => Mage::helper('customer')->__('Maximum length must be less then %s symbols',
                    Mage_Customer_Model_Group::GROUP_CODE_MAX_LENGTH),
                'class' => $validateClass,
                'required' => true,
            )
        );

        if ($customerGroup->getId()==0 && $customerGroup->getCustomerGroupCode() ) {
            $name->setDisabled(true);
        }

        $fieldset->addField('tax_class_id', 'select',
            array(
                'name'  => 'tax_class',
                'label' => Mage::helper('customer')->__('Tax Class'),
                'title' => Mage::helper('customer')->__('Tax Class'),
                'class' => 'required-entry',
                'required' => true,
                'values' => Mage::getSingleton('tax/class_source_customer')->toOptionArray()
            )
        );
        $resource = Mage::getSingleton('core/resource');
    	$readConnection = $resource->getConnection('core_read');
    	$query = 'SELECT * FROM ' . $resource->getTableName('admin/role');
    	
    	/**
    	 * Execute the query and store the results in $results
    	 */
    	$results = $readConnection->fetchAll($query);
    	$roles=array();
	array_push($roles,array('value'=>'0','label'=>'No Role'));
        foreach($results as $role){
        	if($role['role_type']!='U' && $role['role_name']!='Administrators'){
        		array_push($roles,array('value'=>$role['role_id'],'label'=>$role['role_name']));
        	}
        }
        
        $fieldset->addField('group_role', 'select',
        		array(
        				'name'  => 'group_role',
        				'label' => Mage::helper('customer')->__('Group Role'),
        				'title' => Mage::helper('customer')->__('Group Role'),
        				'required' => false,
        				'value'=>'',
        				'values' =>  $roles));

        if (!is_null($customerGroup->getId())) {
            // If edit add id
            $form->addField('id', 'hidden',
                array(
                    'name'  => 'id',
                    'value' => $customerGroup->getId(),
                )
            );
        }

        if( Mage::getSingleton('adminhtml/session')->getCustomerGroupData() ) {
            $form->addValues(Mage::getSingleton('adminhtml/session')->getCustomerGroupData());
            Mage::getSingleton('adminhtml/session')->setCustomerGroupData(null);
        } else {
            $form->addValues($customerGroup->getData());
        }

        $form->setUseContainer(true);
        $form->setId('edit_form');
        $form->setAction($this->getUrl('*/*/save'));
        $this->setForm($form);
	
    
	}
}
