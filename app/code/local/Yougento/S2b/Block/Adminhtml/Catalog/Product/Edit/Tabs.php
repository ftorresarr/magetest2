<?php
class Yougento_S2b_Block_Adminhtml_Catalog_Product_Edit_Tabs extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs
{
	 
	protected function _prepareLayout(){
		
		parent::_prepareLayout();
		$roleId = implode('', Mage::getSingleton('admin/session')->getUser()->getRoles());
		$customConfig = Mage::getModel('s2b/configs')->load($roleId,'vroleid');
		$hideTabs = $customConfig->getHidetabs();
		if($hideTabs){
			$hideTabs = $customConfig->getHidetabs();
		}
		foreach ((array)explode(",", $hideTabs) as $tab){
			$this->removeTab($tab);
		}
		$roleName = Mage::getModel('admin/roles')->load($roleId)->getRoleName();
        	if($roleName != 'Administrators'){
        		if(Mage::app()->getRequest()->getParam('id')!=NULL){
        			$creator = Mage::getModel('catalog/product')->load(Mage::app()->getRequest()->getParam('id'))->getCreator();
					if($creator!=mage::helper('s2b')->getVendorCid()){
						Mage::getSingleton('core/session')->addError('You are not the creator of this product');
						Mage::app()->getFrontController()->getResponse()->setRedirect($this->getUrl('/catalog_product/')); 
					}
        		}
        	}
		return $this;
	}


}