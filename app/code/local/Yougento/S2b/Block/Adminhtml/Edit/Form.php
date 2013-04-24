<?php 
class Yougento_S2b_Block_Adminhtml_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
	
	protected function _prepareForm()
    {
    	
    	$config = Mage::getModel('s2b/configs')->load($this->getRequest()->getParam('rid'),'vroleid');
    	$autoproduct = $config->getAutoprod();
    	if($autoproduct){
    		$autoproduct = $config->getAutoprod();
    	}else{
    		$autoproduct = NULL;
    	}
    	$autofirst=$config->getAutofirst();
    	if ($autofirst) {
    		$autofirst=$config->getAutofirst();
    	}else{
    		$autofirst = NULL;
    	}
    	$maxmess = $config->getMaxmess();
    	if($maxmess){
    		$maxmess =$config->getMaxmess();
    	}else {
    		$maxmess = NULL;
    	}
    	$maxprod = $config->getMaxprod();
    	if($maxprod){
    		$maxprod =$config->getMaxprod();
    	}else {
    		$maxprod = NULL;
    	}
    	$comission = $config->getCommission();
    	if($comission){
    		$comission =$config->getCommission();
    	}else {
    		$comission = NULL;
    	}
    	$prodtypes = $config->getProducttypes();
    	if($prodtypes){
    		$prodtypes =$config->getProducttypes();
    	}else {
    		$prodtypes = NULL;
    	}
    	$hidetabs = $config->getHidetabs();
    	if($hidetabs){
    		$hidetabs =$config->getHidetabs();
    	}else {
    		$hidetabs = NULL;
    	}
    	$hideattr=$config->getHideattr();
		if($hideattr){
			$hideattr=$config->getHideattr();
		}else{
			$hideattr=NULL;
		}
        $form = new Varien_Data_Form();
 
        $fieldset = $form->addFieldset('vendor_resources', array('legend' => Mage::helper('s2b')->__('Modify individual vendor role settings')));
 		
        $fieldset->addField('roleid', 'hidden', array(
        		'label'     => Mage::helper('s2b')->__('Role ID'),
        		'name'      => 'roleid',
        		'value'  	=> $this->getRequest()->getParam('rid'),
        		'disabled' 	=> false,
        		'readonly' 	=> true,
        ));

		
		
		$fieldset->addField('autoproduct', 'select', array(
				'label'     => Mage::helper('s2b')->__('Approve Products Automatically'),
				'required'  => false,
				'name'      => 'autoproduct',
				'onclick' => "",
				'onchange' => "",
				'value'=> $autoproduct,
				'values' => array(
						'-1'=>'Please Select..',
						'1' => array(
								'value'=> array(array('value'=>'1' , 'label' => 'Yes') , array('value'=>'0' , 'label' =>'No') ),
								'label' => ''
						),
		
				),
				'disabled' => false,
				'readonly' => false,
		));
				$fieldset->addField('dismail', 'select', array(
				'label'     => Mage::helper('s2b')->__('Display vendor email on product'),
				'required'  => false,
				'name'      => 'autoproduct',
				'onclick' => "",
				'onchange' => "",
				'value'=> $autoproduct,
				'values' => array(
						'-1'=>'Please Select..',
						'1' => array(
								'value'=> array(array('value'=>'1' , 'label' => 'Yes') , array('value'=>'0' , 'label' =>'No') ),
								'label' => ''
						),
		
				),
				'disabled' => false,
				'readonly' => false,
		));
        				$fieldset->addField('disphone', 'select', array(
				'label'     => Mage::helper('s2b')->__('Display vendor phone on product'),
				'required'  => false,
				'name'      => 'autoproduct',
				'onclick' => "",
				'onchange' => "",
				'value'=> $autoproduct,
				'values' => array(
						'-1'=>'Please Select..',
						'1' => array(
								'value'=> array(array('value'=>'1' , 'label' => 'Yes') , array('value'=>'0' , 'label' =>'No') ),
								'label' => ''
						),
		
				),
				'disabled' => false,
				'readonly' => false,
		));
  /*      $fieldset->addField('maxmess', 'text', array(
            'name'      => 'maxmess',
            'title'     => Mage::helper('s2b')->__('maxmess'),
            'label'     => Mage::helper('s2b')->__('Vendor Maximum Allowed Messages'),
            'maxlength' => '250',
        	'value'=> $maxmess,
            'required'  => false,
        	'after_element_html' => '<small>0 - no minimum limits</small>',
        ));*/
 
        $fieldset->addField('maxprod', 'text', array(
            'name'      => 'maxprod',
            'title'     => Mage::helper('s2b')->__('maxprod'),
            'label'     => Mage::helper('s2b')->__('Vendor Maximum Products'),
            'maxlength' => '250',
        	'value'=> $maxprod,
            'required'  => false,
        	'after_element_html' => '<br><small>0 - no maximum limits</small>',
        ));
        
      /*  $fieldset->addField('commission', 'text', array(
        		'name'      => 'commission',
        		'title'     => Mage::helper('s2b')->__('commission'),
        		'label'     => Mage::helper('s2b')->__('Default Store Comission'),
        		'maxlength' => '250',
        		'value'=> $comission,
        		'required'  => false,
        ));*/
        $entityType = Mage::getModel('catalog/product')->getResource()->getEntityType();
        $collection = Mage::getResourceModel('eav/entity_attribute_set_collection')
        ->setEntityTypeFilter($entityType->getId());
        
        $result = array();
		array_push($result,array('value'=>0,'label'=>"None"));
        foreach ($collection as $attributeSet) {

        	array_push($result,array('value'=>$attributeSet->getId(),'label'=>$attributeSet->getAttributeSetName()));

        }
        $fieldset->addField('hideattr', 'multiselect', array(
        		'label'     => Mage::helper('s2b')->__('Hide Attribute Set'),
        		'required'  => false,
        		'name'      => 'hideattr',
        		'onclick' => "return false;",
        		'value'=> $hideattr,
        		'onchange' => "return false;",
        		'values' => array(
        				'1' => array(
        						'value'=> $result, 'label' => ''
        
        				)),
        		'disabled' => false,
        		'readonly' => false,
        ));
        
        $fieldset->addField('producttypes', 'multiselect', array(
        		'label'     => Mage::helper('s2b')->__('Available Product Types'),
        		'required'  => false,
        		'name'      => 'producttypes',
        		'onclick' => "return false;",
        		'value'=> $prodtypes,
        		'onchange' => "return false;",
        		'values' => array(
        				'1' => array(
        						'value'=> array(array('value'=>'NULL' , 'label' => 'All') , array('value'=>'simple' , 'label' =>'Simple Product'),
        								array('value'=>'grouped' , 'label' => 'Grouped Product' ), array('value'=>'configurable' , 'label' => 'Configurable Product' ),
        								array('value'=>'virtual' , 'label' => 'Virtual Product' ), array('value'=>'bundle' , 'label' => 'Bundle Product' ),
        								array('value'=>'downloadable' , 'label' => 'Downloadable Product' )), 'label' => ''
        
        				)),
        		'disabled' => false,
        		'readonly' => false,
        		'after_element_html' => '<br><small>At least one product type should be selected<small>',
        ));
		$arr	= array();
		$groupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')
                ->setAttributeSetFilter(4)
                ->setSortOrder()
                ->load();
		foreach ($groupCollection as $group) {
			array_push($arr,array('value'=>'group_' . $group->getId(),'label'=> Mage::helper('catalog')->__($group->getAttributeGroupName())));
		}   
		array_push($arr,array('value'=>'inventory', 'label' => Mage::helper('catalog')->__('Inventory')));
		array_push($arr,array('value'=>'websites', 'label' => Mage::helper('catalog')->__('Websites')));
		array_push($arr,array('value'=>'categories', 'label' => Mage::helper('catalog')->__('Categories')));
		array_push($arr, array('value'=>'related', 'label' => Mage::helper('catalog')->__('Related Products')));
		array_push($arr, array('value'=>'upsell', 'label' => Mage::helper('catalog')->__('Up-sells')));
		array_push($arr,array('value'=>'crosssell', 'label' => Mage::helper('catalog')->__('Cross-sells')));
		array_push($arr,array('value'=>'productalert', 'label' => Mage::helper('catalog')->__('Product Alerts')));
		array_push($arr,array('value'=>'reviews', 'label' => Mage::helper('catalog')->__('Product Reviews')));
		array_push($arr,array('value'=>'tags', 'label' => Mage::helper('catalog')->__('Product Tags')));
		array_push($arr,array('value'=>'customers_tags', 'label' => Mage::helper('catalog')->__('Customers Tagged Product')));
		array_push($arr,array('value'=>'customer_options', 'label' => Mage::helper('catalog')->__('Custom Options')));
        $fieldset->addField('hidetabs', 'multiselect', array(
          'label'     => Mage::helper('s2b')->__('Hide Tabs'),
          'required'  => false,
          'name'      => 'hidetabs',
          'onclick' => "return false;",
          'onchange' => "return false;",
        		'value'=> $hidetabs,
          'values' => $arr,
          'disabled' => false,
          'readonly' => false,
        ));
 		$form->setMethod('post');
        $form->setUseContainer(true);
        $form->setId('edit_form');
        $form->setAction($this->getUrl('*/*/save'));
 
        $this->setForm($form);
        $id = $this->getRequest()->getParam('rid');
    }

	
	protected function _prepareLayout()
	{
		$role = Mage::registry('current_role');

	
		return parent::_prepareLayout();
	}
}
?>
