<?php 
class Yougento_S2b_Adminhtml_CustomController extends Mage_Adminhtml_Controller_action
{
	protected function _init()
	{
		$id = $this->getRequest()->getParam('rid');

	}
	public function testModelAction(){
		$configs=Mage::getModel('s2b/configs')->load(1);
		var_dump($configs);
	}
	
	public function vendorAction(){
		$this->_init();
		$this->getResponse()->setBody(
				$this->getLayout()->createBlock('s2b/adminhtml_edit')->toHtml()
				);

	}		
	
	public function saveAction()
	{
		$data = $this->getRequest()->getPost();
		$config = Mage::getModel('s2b/configs');
		if(isset($data['hideattr'])){
			$hideattr = '';
			foreach($data['hideattr'] AS $attr){
				$hideattr .= $attr.',';
			}
			$hideattr = substr($hideattr, 0, -1);
		}else {
			$hideattr = NULL;
		}
		if(isset($data['producttypes'])){
			$prodtypes = '';
			foreach($data['producttypes'] AS $product){
				$prodtypes .= $product.',';
			}
			$prodtypes = substr($prodtypes, 0, -1);
		}else {
			$prodtypes = NULL;
		}
		if(isset($data['hidetabs'])){
			$hidetab = '';
			foreach($data['hidetabs'] AS $tab){
				$hidetab .= $tab.',';
			}
			$hidetab = substr($hidetab, 0, -1);
		}else {
			$hidetab = NULL;
		}
		$dataArr=$config->load($data['roleid'],'vroleid')->getData();
		if($dataArr){		
			$config->load($data['roleid'],'vroleid');
			if($data['autoproduct']!='-1'){
				$config->setAutoprod($data['autoproduct']);
			}
			if($data['disphone']!='-1'){
				$config->setDisphone($data['disphone']);
			}
			if($data['dismail']!='-1'){
				$config->setDismail($data['dismail']);
			}
			if($data['maxmess']!=''){
				$config->setMaxmess($data['maxmess']);
			}
			if($data['maxprod']!=''){
				$config->setMaxprod($data['maxprod']);
			}
			if($data['commission']!=''){
				$config->setCommission($data['commission']);
			}
			if(isset($data['hideattr'])){
				$config->setHideattr($hideattr);
			}
			if(isset($data['producttypes'])){
				$config->setProducttypes($prodtypes);
			}
			if(isset($data['hidetabs'])){
				$config->setHidetabs($hidetab);
			}
			$config->save();
		}else{
			$config->setVroleid($data['roleid']);
			if($data['autovendor']!='-1'){
				$config->setAutovendor($data['autovendor']);
			}
			if($data['autoproduct']!='-1'){
				$config->setAutoprod($data['autoproduct']);
			}
			if($data['disphone']!='-1'){
				$config->setDisphone($data['disphone']);
			}
			if($data['dismail']!='-1'){
				$config->setDismail($data['dismail']);
			}
			if($data['minprod']!=''){
				$config->setMinprod($data['minprod']);
			}
			if($data['maxprod']!=''){
				$config->setMaxprod($data['maxprod']);
			}
			if($data['commission']!=''){
				$config->setCommission($data['commission']);
			}
			if(isset($data['hideattr'])){
				$config->setHideattr($hideattr);
			}
			if(isset($data['producttypes'])){
				$config->setProducttypes($prodtypes);
			}
			if(isset($data['hidetabs'])){
				$config->setHidetabs($hidetab);
			}
			$config->save();
		} 
		Mage::getSingleton('adminhtml/session')->addSuccess("Custom vendor role configuration was saved correctly");
		$this->_redirectReferer(); 
	}

}

?>