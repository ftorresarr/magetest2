<?php
class Yougento_S2b_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getVendorCid() {
			$username = Mage::getSingleton('admin/session')->getUser()->getUsername();
        	$resource = Mage::getSingleton('core/resource');
        	$readConnection = $resource->getConnection('core_read');
        	$query = "SELECT entity_id FROM customer_entity WHERE email='{$username}'";
        	$results = $readConnection->fetchAll($query);
        	$CustomerId = $results[0]['entity_id'];
        	return $CustomerId;
	}
	public function getVendorAid($username){
		$resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		$query = "SELECT user_id FROM admin_user WHERE username='{$username}'";
		$results = $readConnection->fetchAll($query);
		$CustomerId = $results[0]['user_id'];
		return $CustomerId;
	}
	
	public function changeRoles()
	{
		
	}
}