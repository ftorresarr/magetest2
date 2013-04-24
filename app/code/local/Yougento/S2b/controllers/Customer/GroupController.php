<?php
include_once('Mage/Adminhtml/controllers/Customer/GroupController.php');
class Yougento_S2b_Customer_GroupController extends Mage_Adminhtml_Customer_GroupController
{
    public function saveAction()
    {
        $customerGroup = Mage::getModel('customer/group');
        $id = $this->getRequest()->getParam('id');
        if (!is_null($id)) {
            $customerGroup->load($id);
        }
        if($id==0){
        	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('customer')->__('You cannot modify unlogged group'));
        	
		Mage::app()->getFrontController()
           	->getResponse()
           	->setRedirect($this->getUrl('*/customer_group'));
	//		Mage::throwException(Mage::helper('adminhtml')->__('Not Logged in group is locked, please go back'));
        }

        if ($taxClass = $this->getRequest()->getParam('tax_class')) {
            try {
            	$role= $this->getRequest()->getParam('group_role');
				if($id!=0){
                $customerGroup->setCode($this->getRequest()->getParam('code'))
                    ->setTaxClassId($taxClass)
					->setGroupRole($role)
                    ->save();
				}
		$group_id=$this->getRequest()->getParam('id');
                 if($role!='No Role' && $group_id!=NULL)
                {

	                $customers=Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('group_id',$group_id);             	
			foreach($customers as $customer)
	                {
				if($customer->getEmail()){
	                	$username= $customer->getEmail();
	                	$vendorID= Mage::helper('s2b')->getVendorAid($username);
	                	if($vendorID>0){
					$roleModel=Mage::getModel('admin/role')->load($vendorID,'user_id')->setParentId($role)->save();
	                		$resource = Mage::getSingleton('core/resource');
							$writeConnection = $resource->getConnection('core_write');
							$query = "UPDATE admin_role SET parent_id = '{$role}' WHERE user_id = "
	             					. $vendorID;
							$writeConnection->query($query);
	                	}
	                }}
                } else{
				if($customer!=NULL){
				                	$username= $customer->getEmail();
	                	$vendorID= Mage::helper('s2b')->getVendorAid($username);
				
	                	if($vendorID>0){
	                		$resource = Mage::getSingleton('core/resource');
							$writeConnection = $resource->getConnection('core_write');
							$query = "UPDATE admin_role SET parent_id = '0' WHERE user_id = "
	             					. $vendorID;
							$writeConnection->query($query);
				}
		}              
		}
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('customer')->__('The customer group has been saved.'));
                Mage::app()->getFrontController()
           	->getResponse()
           	->setRedirect($this->getUrl('*/customer_group'));
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setCustomerGroupData($customerGroup->getData());
                $this->getResponse()->setRedirect($this->getUrl('*/customer_group/edit', array('id' => $id)));
                return;
            }
        } else {
            $this->_forward('new');
        }

    }
}
