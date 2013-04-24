<?php
class Yougento_S2b_Adminhtml_TabsController extends Mage_Adminhtml_Controller_action
{
	protected function _init()
	{
		$id = $this->getRequest()->getParam('id');
	
	}
	
	public function productsAction()
	{
		
		$this->getResponse()->setBody(
				$this->getLayout()->createBlock('s2b/rewrite_tabbedgrid')->toHtml()
		);;
	}
	public function messagesAction()
	{
		$this->getResponse()->setBody(
				$this->getLayout()->createBlock('supportsuite/adminhtml_ticket_grid')->toHtml()
		);;
	}
	public function settingsAction()
	{
		$this->getResponse()->setBody(
				$this->getLayout()->createBlock('customvendor/adminhtml_edit')->toHtml()
		);;
	}
	public function saveAction()
	{
		$postData = $this->getRequest()->getPost();
		$vendor=Mage::getModel('s2b/information')->load($postData['cid'],'cid');
		if(isset($_FILES['logo']['name']) and (file_exists($_FILES['logo']['tmp_name']))) {
			try {
				$uploader = new Varien_File_Uploader('logo');
				$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything
		
		
				$uploader->setAllowRenameFiles(false);
		
				// setAllowRenameFiles(true) -> move your file in a folder the magento way
				// setAllowRenameFiles(true) -> move your file directly in the $path folder
				$uploader->setFilesDispersion(false);
				 
				$path =  Mage::getBaseDir('media') . DS . 'vendor' . DS . $postData['cid'];
				 
				$uploader->save($path, $_FILES['logo']['name']);
		
				$postData['logo'] = $_FILES['logo']['name'];
			}catch(Exception $e) {
		
			}
		}    else {       
     
        if(isset($postData['logo']['delete']) && $postData['logo']['delete'] == 1){
            $postData['image_main'] = '';
            $vendor->setCompanylogo(NULL);
        }
        else
            unset($postData['logo']);
    }
		if(isset($_FILES['photo']['name']) and (file_exists($_FILES['photo']['tmp_name']))) {
			try {
				$uploader = new Varien_File_Uploader('photo');
				$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything
		
		
				$uploader->setAllowRenameFiles(false);
		
				// setAllowRenameFiles(true) -> move your file in a folder the magento way
				// setAllowRenameFiles(true) -> move your file directly in the $path folder
				$uploader->setFilesDispersion(false);
				 
				$path = Mage::getBaseDir('media') . DS . 'vendor' . DS . $postData['cid'];
				 
				$uploader->save($path, $_FILES['photo']['name']);
		
				$data['photo'] = $_FILES['photo']['name'];
			}catch(Exception $e) {
		
			}
		} else {       
     
        if(isset($postData['photo']['delete']) && $postData['photo']['delete'] == 1)
        {
            $postData['image_main'] = '';
            $vendor->setYourpic(NULL);
        }
        else{
            unset($postData['photo']);
        }
    }
		
		
		if($vendor->getinfoid()!=NULL){
			$vendor->setCompanyname($postData['coname'])
			->setCompanyemail($postData['comail'])->setCompanyphone($postData['cophone'])
			->setCompanylogo(str_replace(" ","_",$_FILES['logo']['name']))->setYourpic(str_replace(" ","_",$_FILES['photo']['name']))
			->setAddress($postData['address'])->setCity($postData['city'])
			->setCountry($postData["country"])->setZipcode($postData['zip'])->save();
		}else{
			$vendor->setCid($postData['cid'])->setCompanyname($postData['coname'])
			->setCompanyemail($postData['comail'])->setCompanyphone($postData['cophone'])
			->setCompanylogo(str_replace(" ","_",$_FILES['logo']['name']))->setYourpic(str_replace(" ","_",$_FILES['photo']['name']))
			->setAddress($postData['address'])->setCity($postData['city'])
			->setCountry($postData["country"])->setZipcode($postData['zip'])->save();
		}
		Mage::getSingleton('adminhtml/session')->addSuccess('Vendor Info Saved');
		$roleId = implode('', Mage::getSingleton('admin/session')->getUser()->getRoles());
		$roleName = Mage::getModel('admin/roles')->load($roleId)->getRoleName();
		if($roleName!='Administrators'){
			$this->_redirect('s2b/adminhtml_dash/details', array('_current'=>true));
		}else{
		
		Mage::app()->getResponse()->setRedirect(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)."index.php/admin/customer/edit/".'id/'.$postData['cid']); 
}
	}
}
