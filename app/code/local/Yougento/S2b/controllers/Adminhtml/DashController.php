<?php
class Yougento_S2b_Adminhtml_DashController extends Mage_Adminhtml_Controller_action
{
	public function indexAction() {
		
		$this->loadLayout();
		$this
                ->_addLeft($this->getLayout()->createBlock('s2b/adminhtml_dashtabs'));
		$this->_setActiveMenu('s2b_menu/first_page');
		$this->renderLayout();
	}
	public function dashboardAction() {
		$this->getResponse()->setBody(
				$this->getLayout()->createBlock('s2b/adminhtml_dashboard')->toHtml()
		);
	}
	public function detailsAction(){
		$this->loadLayout();
		$this->_addContent($this->getLayout()->createBlock('s2b/adminhtml_tabs_edit'))
		->_addLeft($this->getLayout()->createBlock('s2b/adminhtml_dashtabs'));
		//$this->getResponse()->setBody($this->getLayout()->createBlock('s2b/adminhtml_tabs_edit')->toHtml());
		$this->_setActiveMenu('s2b_menu/first_page');

		$this->renderLayout();
	}
}
