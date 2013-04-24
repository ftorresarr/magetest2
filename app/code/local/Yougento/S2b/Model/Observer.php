<?php
class Yougento_S2b_Model_Observer extends Mage_Core_Model_Abstract
{
	public function __contruct()
	{
	
	}
	public function handlecpfileupload($observer)
        {
            
            $customer = $observer->getCustomer();
            $allowedextensions = array('jpg','jpeg','gif','png');
                  
            if(($_FILES['cplogo']['name']!='')&&(!(in_array(pathinfo($_FILES['cplogo']['name'], PATHINFO_EXTENSION),$allowedextensions)))){
                Mage::getSingleton('adminhtml/session')->addError('Upload only image file');
                throw new Exception("cplogo exception"); 
            }      
            if(isset($_FILES['cplogo']['name']) && $_FILES['cplogo']['size'] >= 15728640) {
                 
                Mage::getSingleton('adminhtml/session')->addError('CP logo max file size is 15 MB');
                throw new Exception("cplogo exception"); 
            }                 
                  
            if(($_FILES['cppicture']['name']!='')&&(!(in_array(pathinfo($_FILES['cppicture']['name'], PATHINFO_EXTENSION),$allowedextensions)))){
               Mage::getSingleton('adminhtml/session')->addError('Upload only image file');
               throw new Exception("cppicture exception"); 
            }
                                 
            if(isset($_FILES['cppicture']['name']) && $_FILES['cppicture']['size'] >= 15728640) {
               Mage::getSingleton('adminhtml/session')->addError('CP Picture max file size is 15 MB');
               throw new Exception("cppicture exception"); 
            } 
                 
                 
           try {	
             if (isset($_FILES['cplogo']['name']) && $_FILES['cplogo']['name'] != "") 
             {
                          
                   $uploader = new Varien_File_Uploader("cplogo");
                   $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                   /* $uploader->addValidateCallback('catalog_product_image',
                        Mage::helper('catalog/image'), 'validateUploadFile'); */
                   $uploader->setAllowRenameFiles(false);
                   $uploader->setFilesDispersion(false);
                   $logopath = Mage::getBaseDir("media") . DS  ;
                   $logoName = "CP_logo". $customer->getId() . "." . pathinfo($_FILES['cplogo']['name'], PATHINFO_EXTENSION);
                   $uploader->save($logopath, $logoName);
                   $customer->setData('cplogo',$logoName);
                   $resizelogo = new Varien_Image($logopath. $logoName);
                   $resizelogo->constrainOnly(TRUE);
                   $resizelogo->keepAspectRatio(TRUE);
                   $resizelogo->resize(300,300);
                   $resizelogo->save($logopath.$logoName);
               }

               if (isset($_FILES['cppicture']['name']) && $_FILES['cppicture']['name'] != "") {
                   
                  $uploader = new Varien_File_Uploader("cppicture");
                  $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png', 'pdf'));
                  $uploader->setAllowRenameFiles(false);
                  $uploader->setFilesDispersion(false);
                  $picpath = Mage::getBaseDir("media") . DS  ;
                  $picName = "CP_Picture". $customer->getId() . "." . pathinfo($_FILES['cppicture']['name'], PATHINFO_EXTENSION);
                  $uploader->save($picpath, $picName);
                  $customer->setData('cppicture',$picName);
                  $resizepic = new Varien_Image($picpath. $picName);
                  $resizepic->constrainOnly(TRUE);
                  $resizepic->keepAspectRatio(TRUE);
                  $resizepic->resize(300,300);
                  $resizepic->save($picpath.$picName);
                                
                }
                $deletecplogo = $observer->getEvent()->getRequest()->getParams('cplogo');
                $deletecppicture = $observer->getEvent()->getRequest()->getParams('cppicture');
                 if($deletecplogo['cplogo']['delete']==1){
                         
                        unlink(Mage::getBaseDir("media") .DS . $deletecplogo['cplogo']['value']);
                        $customer->setData("cplogo",'');
                        Mage::getSingleton('adminhtml/session')->addSuccess('CP logo deleted');
                 }
                  if($deletecppicture['cppicture']['delete']==1){

                        unlink(Mage::getBaseDir("media") .DS . $deletecppicture['cppicture']['value']);
                        $customer->setData("cppicture",'');
                        Mage::getSingleton('adminhtml/session')->addSuccess('CP picture deleted');
                 }
                
                $cpname = $observer->getEvent()->getRequest()->getParam('cpname');
                $cpphone = $observer->getEvent()->getRequest()->getParam('cpphone');
                $cpemail = $observer->getEvent()->getRequest()->getParam('cpemail');
                $cpaddress = $observer->getEvent()->getRequest()->getParam('cpaddress');
                $cpcity = $observer->getEvent()->getRequest()->getParam('cpcity');  
                $cpcountry = $observer->getEvent()->getRequest()->getParam('cpcountry');
                $cpzip = $observer->getEvent()->getRequest()->getParam('cpzip');
                if($cpname!='')
                $customer->setData('cpname',$cpname);
                if($cpphone!='')
                $customer->setData('cpphone',$cpphone);
                if($cpemail!='')
                $customer->setData('cpemail',$cpemail);
                if($cpaddress!='')
                $customer->setData('cpaddress',$cpaddress);
                if($cpcity!='')
                $customer->setData('cpcity',$cpcity);
                if($cpcountry!='')
                $customer->setData('cpcountry',$cpcountry);
                if($cpzip!='')
                $customer->setData('cpzip',$cpzip);
                
             }
             catch (Exception $e) {
		      
		        }
         
         
      
     
   } 
	
	public function saveCustomerData(Varien_Event_Observer $observer) 
	{
            try {
            	$postData = Mage::app()->getRequest()->getPost();
 				$acctype = $postData['account']['acctype1'];
 				$customer = Mage::getModel('customer/customer')->load($postData['customer_id'])->getData();
 				$loadusr = $customer['email'];
				$customer['newemail']=$postData['email'];
 				$checkadmn = Mage::getModel("admin/user")->load($loadusr,'username');
 				if($postData['account']['oldemail']!=$postData['account']['email'])
 				{
 					$acct = '0';
 				}else
 				{
 					if($postData['account']['oldemail']==$postData['account']['email']){
 						$acct='1';
 					}else{
 					$acct = '3';
					}
 				}
                if (isset($acctype) && $acctype == '2' && $acct=='1') {
                      try
      					{
      					
				          $user = Mage::getModel("admin/user")
				                  ->setUsername($customer['email'])
				                  ->setFirstname($customer['firstname'])
				                  ->setLastname($customer['lastname'])
				                  ->setEmail($customer['email'])
				                  ->save();
				          $resource = Mage::getSingleton('core/resource');
				          $writeConnection = $resource->getConnection('core_write');
				          $table = $resource->getTableName('admin/user');
				          $query = "UPDATE {$table} SET password = '{$customer['password_hash']}' 
				          			WHERE user_id = '{$user->getId()}'";
				          $writeConnection->query($query);
				          $role = Mage::getModel("admin/role");
				          $role->setParent_id(3);
				          $role->setTree_level(2);
				          $role->setRole_type('U');
				          $role->setUser_id($user->getId());
				          $role->save();
						  $userId = $user->getId();
						  $query1 = "UPDATE admin_role SET parent_id = '3' WHERE user_id = '{$userId}'";
						  if($writeConnection->query($query1)){
				          Mage::getSingleton('adminhtml/session')->addSuccess('Customer converted to vendor');
				          }
				      }
				      catch (Exception $e)
				      {
				      	Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				      }
                	
                }elseif(($postData['account']['oldemail']!=$postData['account']['email']) && isset($acctype) && $acctype == '2'){
                	  try
      {
      					
						Mage::getModel("admin/user")->load($postData['account']['oldemail'],'username')
						->setUsername($postData['account']['email'])
						->setEmail($postData['account']['email'])->save();
				          Mage::getSingleton('adminhtml/session')->addSuccess('Customer edit to vendor');
				          
				      }
				     	catch (Exception $e)
				      {
				      	Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				      }
                	
                }
				if(isset($acctype) && $acctype == '2' && (Mage::getModel("admin/user")->load($postData['account']['oldemail'],'username')
						->getFirstname($postData['account']['email']))){
							
						}
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        
        public function saveProductCreator(Varien_Event_Observer $observer) 
        {
        	$roleId = implode('', Mage::getSingleton('admin/session')->getUser()->getRoles());
        	$roleName = Mage::getModel('admin/roles')->load($roleId)->getRoleName();        	 
        	$type = Mage::app()->getRequest()->getParam('type');
        	if($roleName != 'Administrators')
        	{

	        	if(isset($type))
	        	{
			   		try 
			   		{   
			   			$username = Mage::getSingleton('admin/session')->getUser()->getUsername();
						$resource = Mage::getSingleton('core/resource');
						$readConnection = $resource->getConnection('core_read');
						$query = "SELECT entity_id FROM customer_entity WHERE email='{$username}'";
						$results = $readConnection->fetchAll($query);
						$CustomerId = $results[0]['entity_id'];
						$product = $observer->getEvent()->getProduct();
			    		$product->lockAttribute('creator');
			   		}	catch (Exception $e)
						      {
						      	Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
						      	
						      }
	        	}
        	}
        }
        
        public function saveProductCreatorData(Varien_Event_Observer $observer) {
        	$roleId = implode('', Mage::getSingleton('admin/session')->getUser()->getRoles());
        	$roleName = Mage::getModel('admin/roles')->load($roleId)->getRoleName();
        	$type = Mage::app()->getRequest()->getParam('type');
        	if($roleName != 'Administrators')
        	{
        		if(isset($type))
        		{
        			try
        			{
        				$username = Mage::getSingleton('admin/session')->getUser()->getUsername();
        				$resource = Mage::getSingleton('core/resource');
        				$readConnection = $resource->getConnection('core_read');
        				$query = "SELECT entity_id FROM customer_entity WHERE email='{$username}'";
        				$results = $readConnection->fetchAll($query);
        				$CustomerId = $results[0]['entity_id'];
        				$product = $observer->getEvent()->getProduct();
        				$product->unlockAttribute('creator');
        				$product->setCreator($CustomerId);
        				$product->save();
        				
        			}	catch (Exception $e)
        			{
        				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        			}
        		}
        	}
        }
        
        public function detectMaxProd(Varien_Event_Observer $observer)
        {
        	$roleId = implode('', Mage::getSingleton('admin/session')->getUser()->getRoles());
        	$roleName = Mage::getModel('admin/roles')->load($roleId)->getRoleName();
        	$type = Mage::app()->getRequest()->getParam('type');
        	if($roleName != 'Administrators')
        	{
        		$product = $observer->getEvent()->getProduct();
        		$product->setStatus(3);
        		$roleId = implode('', Mage::getSingleton('admin/session')->getUser()->getRoles());
        		$customConfig = Mage::getModel('s2b/configs')->load($roleId,'vroleid');
        		$autoprod = $customConfig->getAutoprod();
        			if($autoprod == 1){
        				$product->setStatus(1);
        			}
        		
        		$creator= mage::helper('s2b')->getVendorCid();
        		$collection = Mage::getModel('catalog/product')->getCollection();
        		$collection->addAttributeToSelect('creator')->addAttributeToSelect('name');
        	
        		//filter for products who name is equal (eq) to Widget A, or equal (eq) to Widget B
        		$collection->addFieldToFilter(array(
        				array('attribute'=>'creator','eq'=>$creator),
        		));
        		$i=0;
        		$collection->addFieldToFilter(array(
        				array('attribute'=>'name','neq'=>NULL),
        		));
        	
        		foreach ($collection as $product) {
        			$i++;
        		}
        	/*
        		$roleId = implode('', Mage::getSingleton('admin/session')->getUser()->getRoles());
        		$customConfig = Mage::getModel('s2b/configs')->load($roleId,'vroleid');
        		$limit = $customConfig->getMaxprod();
        		if($limit){
        			$limit = $customConfig->getMaxprod();
        		}else{
        	
        			$limit = NULL;
        		}
        		if ((int)$limit > 0) {
        			$compare=  Mage::app()->getRequest()->getParams('id');
					
						if ($limit <= $i && $compare!=NULL) {
        				//add the error message
        			//	Mage::getSingleton('adminhtml/session')->addError('Max products reached');
        				Mage::throwException(Mage::helper('adminhtml')->__('You reached your maximum number of products'));
        				
        			
					}
				}*/
        	}
        }
        
        public function saveVendorInfo(Varien_Event_Observer $observer) {

        }
        public function lockCreator(Varien_Event_Observer $observer)
        {
        	$product = $observer->getEvent()->getProduct();
        	$product->lockAttribute('creator');
        }
        public function syncGroupRole()
        {
        	try {
	        		$postData = Mage::app()->getRequest()->getPost();
	        		$group = $postData['account']['group_id'];
	        		$customer = Mage::getModel('customer/customer')->load($postData['customer_id'])->getData();
	        		$username = $customer['email'];
	        		$role = Mage::getModel("customer/group")->load($group,'customer_group_id')->getGroupRole();
        	        if($role!=0)
                	{
                		if ($postData['account']['acctype1']==2 && $group==1);{
                			$role = 3;
                		}
	                	$vendorID= Mage::helper('s2b')->getVendorAid($username);
	                	if($vendorID>0){
	                		$resource = Mage::getSingleton('core/resource');
							$writeConnection = $resource->getConnection('core_write');
							$query = "UPDATE admin_role SET parent_id = '{$role}' WHERE user_id = "
	             					. $vendorID;
							$writeConnection->query($query);
		                	}
		                }
						elseif($role=0){
				  
	                	$vendorID= Mage::helper('s2b')->getVendorAid($username);
	                	if($vendorID>0){
	                		$resource = Mage::getSingleton('core/resource');
							$writeConnection = $resource->getConnection('core_write');
							$query = "UPDATE admin_role SET parent_id ='0' WHERE user_id = "
	             					. $vendorID;
							$writeConnection->query($query);
		}          }
	                }               
	        		
	        		catch (Exception $e) {
	        			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
	        	}
        }
	
}
