<?php 

class Yougento_S2b_IndexController extends Mage_Adminhtml_Controller_Action {
	public function createAttrAction(){
		try{
				echo 'hiiii';
$installer=new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();
$installer->getConnection()->addColumn(
    'config_customvendor2222',//table name
    'group_role',//column name
    "int(10)"//definition
);;
$installer->endSetup();
	/*	$installer=new Mage_Eav_Model_Entity_Setup('core_setup');
		$installer->startSetup();
		    $installer->run("
    		INSERT INTO `admin_rule` (role_id,resource_id,assert_id,role_type,permission)
					VALUES (23,'all',0,'G','deny')
		");
		 /*   $installer->addAttribute('customer','cpname', array(
                        'label'              => 'Vendor Name',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 80,
    ));
   $installer->addAttribute('customer','cpphone', array(
                        'label'              => 'Vendor Phone',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 81,
   ));
   $installer->addAttribute('customer','cpemail', array(
                        'label'              => 'Vendor Email',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 82,
    ));
   $installer->addAttribute('customer','cplogo', array(
                        'label'              => 'Vendor Logo',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 83,
    ));
   $installer->addAttribute('customer','cppicture', array(
                        'label'              => 'Vendor Picture',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 84,
    ));
   
   $installer->addAttribute('customer','cpaddress', array(
                        'label'              => 'Vendor Address',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 85,
    ));
   $installer->addAttribute('customer','cpcity', array(
                        'label'              => 'Vendor City',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 86,
    ));
   $installer->addAttribute('customer','cpcountry', array(
                        'label'              => 'Vendor Country',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 87,
    ));
   $installer->addAttribute('customer','cpzip', array(
                        'label'              => 'Vendor Zipcode',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 88,
    ));*/
	$installer->endSetup();
	echo 'hiiii';
	}            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
	}

	public function testModelAction() {
		        $customerId = Mage::helper('s2b')->getVendorCid();
			echo $customerId;
    		 var_dump(Mage::getModel('customer/customer')->load($customerId)->getData());
	}
}


?>
