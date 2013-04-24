<?php
class Yougento_S2b_Block_Adminhtml_Customer_Group_Grid extends Mage_Adminhtml_Block_Customer_Group_Grid
{
	protected function _prepareColumns()
	{
		$this->addColumn('time', array(
				'header' => Mage::helper('customer')->__('ID'),
				'width' => '50px',
				'align' => 'right',
				'index' => 'customer_group_id',
		));
	
		$this->addColumn('type', array(
				'header' => Mage::helper('customer')->__('Group Name'),
				'index' => 'customer_group_code',
		));
		$resource = Mage::getSingleton('core/resource');
    	$readConnection = $resource->getConnection('core_read');
    	$query = 'SELECT * FROM ' . $resource->getTableName('admin/role');
    	$results = $readConnection->fetchAll($query);
    	$roles=array();
		$roles[0]='No Role';
        foreach($results as $role){
        	if($role['role_type']!='U' && $role['role_name']!='Administrators'){
        		$roles[$role['role_id']] =$role['role_name'];
        	}
        }
		$this->addColumn('role', array(
				'header' => Mage::helper('customer')->__('Group Role'),
				'index' => 'group_role',
				'type'      => 'options',
				'options'=> $roles,
		));
		$this->addColumn('class_name', array(
				'header' => Mage::helper('customer')->__('Tax Class'),
				'index' => 'class_name',
				'width' => '200px'
		));
	
		return parent::_prepareColumns();
	}
}