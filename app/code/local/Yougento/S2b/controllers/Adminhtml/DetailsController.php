<?php 
class Yougento_S2b_Adminhtml_DetailsController extends Mage_Adminhtml_Controller_action
{

	public function saveAction(){
		    $customer = Mage::getModel('customer/customer')->load(Mage::helper('s2b')->getVendorCid());

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
             if (isset($_FILES['cplogo']['name']) && $_FILES['cplogo']['name'] != "") {
                          
                   $uploader = new Varien_File_Uploader("cplogo");
                   $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                   /* $uploader->addValidateCallback('catalog_product_image',
                        Mage::helper('catalog/image'), 'validateUploadFile'); */
                   $uploader->setAllowRenameFiles(false);
                   $uploader->setFilesDispersion(false);
                   $logopath = Mage::getBaseDir("media") . DS  ;
                   $logoName = "CP_logo". $customer->getId() . "." . pathinfo($_FILES['cplogo']['name'], PATHINFO_EXTENSION);
                   $uploader->save($logopath, $logoName);
                   $customer->setcplogo($logoName);
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
                  $customer->setcppicture($picName);
                  $resizepic = new Varien_Image($picpath. $picName);
                  $resizepic->constrainOnly(TRUE);
                  $resizepic->keepAspectRatio(TRUE);
                  $resizepic->resize(300,300);
                  $resizepic->save($picpath.$picName);
                                
                }
			   $postData = Mage::app()->getRequest()->getPost();
                $deletecplogo['cplogo'] = $postData['cplogo'];
                $deletecppicture['cppicture'] = $postData['cppicture'];
                 if($deletecplogo['cplogo']['delete']==1){
                         
                        unlink(Mage::getBaseDir("media") .DS . $deletecplogo['cplogo']['value']);
                        $customer->setcplogo('');
                        Mage::getSingleton('adminhtml/session')->addSuccess('CP logo deleted');
                 }
                  if($deletecppicture['cppicture']['delete']==1){

                        unlink(Mage::getBaseDir("media") .DS . $deletecppicture['cppicture']['value']);
                        $customer->setcppicture('');
                        Mage::getSingleton('adminhtml/session')->addSuccess('CP picture deleted');
                 }
                
                $cpname = $postData['cpname'];
                $cpphone = $postData['cpphone'];
                $cpemail = $postData['cpemail'];
                $cpaddress = $postData['cpaddress'];
                $cpcity = $postData['cpcity'];  
                $cpcountry = $postData['cpcountry'];
                $cpzip = $postData['cpzip'];
                if($cpname!='')
                $customer->setCpname($cpname);
                if($cpphone!='')
                $customer->setCpphone($cpphone);
                if($cpemail!='')
                $customer->setCpemail($cpemail);
                if($cpaddress!='')
                $customer->setCpaddress($cpaddress);
                if($cpcity!='')
                $customer->setCpcity($cpcity);
                if($cpcountry!='')
                $customer->setCpcountry($cpcountry);
                if($cpzip!='')
                $customer->setCpzip($cpzip);
				$customer->save();
				Mage::getSingleton('adminhtml/session')->addSuccess('Your details have been saved');
                $this->_redirect('*/adminhtml_dash/', array('active_tab' => 'details_section'));
             }
             catch (Exception $e) {
		      
		        }
	}


}

?>