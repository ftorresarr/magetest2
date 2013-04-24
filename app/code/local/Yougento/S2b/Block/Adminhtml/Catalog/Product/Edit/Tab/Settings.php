<?php
class Yougento_S2b_Block_Adminhtml_Catalog_Product_Edit_Tab_Settings extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Settings
{
	protected function _prepareLayout()
	{
		$this->setChild('continue_button',
				$this->getLayout()->createBlock('adminhtml/widget_button')
				->setData(array(
						'label'     => Mage::helper('catalog')->__('Continue'),
						'onclick'   => "setSettings('".$this->getContinueUrl()."','attribute_set_id','product_type')",
						'class'     => 'save'
				))
		);
		return parent::_prepareLayout();
	}

	protected function _prepareForm()
	{
		$entityType = Mage::registry('product')->getResource()->getEntityType();
		$roleId = implode('', Mage::getSingleton('admin/session')->getUser()->getRoles());
		$roleName = Mage::getModel('admin/roles')->load($roleId)->getRoleName();
		if ($roleName != 'Administrators'){
			//we remove the disabled options
			$customConfig = Mage::getModel('s2b/configs')->load($roleId,'vroleid');
			$attrToHide = $customConfig->gethideattr();
			$attrToHide = explode(',', $attrToHide);
			$filteredAttr = Mage::getResourceModel('eav/entity_attribute_set_collection')
			->setEntityTypeFilter($entityType->getId());
			foreach($attrToHide as $attr)
			{
				$filteredAttr->addFieldToFilter('attribute_set_id',array( 'neq'=> $attr));
			}
			$hiddenAttr=$filteredAttr
			->load()
			->toOptionArray();
		}else{
			$hiddenAttr=Mage::getResourceModel('eav/entity_attribute_set_collection')
			->setEntityTypeFilter($entityType->getId())
			->load()
			->toOptionArray();;
		}
		$form = new Varien_Data_Form();
		$fieldset = $form->addFieldset('settings', array('legend'=>Mage::helper('catalog')->__('Create Product Settings')));		
		$fieldset->addField('attribute_set_id', 'select', array(
				'label' => Mage::helper('catalog')->__('Attribute Set'),
				'title' => Mage::helper('catalog')->__('Attribute Set'),
				'name'  => 'set',
				'value' => $entityType->getDefaultAttributeSetId(),
				'values'=> $hiddenAttr
		));

		$fieldset->addField('product_type', 'select', array(
				'label' => Mage::helper('catalog')->__('Product Type'),
				'title' => Mage::helper('catalog')->__('Product Type'),
				'name'  => 'type',
				'value' => '',
				'values'=> Mage::getModel('catalog/product_type')->getOptionArray()
		));

		$fieldset->addField('continue_button', 'note', array(
				'text' => $this->getChildHtml('continue_button'),
		));

		$this->setForm($form);
	}

	public function getContinueUrl()
	{
		return $this->getUrl('*/*/new', array(
				'_current'  => true,
				'set'       => '{{attribute_set}}',
				'type'      => '{{type}}'
		));
	}
}