<?php
class Yougento_S2b_Block_Adminhtml_Dashtabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		
		parent::__construct();
		$this->setName('vendor_dash_tabs');
		$this->setId('dash_tabs');
		$this->setTitle(Mage::helper('s2b')->__('Profile Information'));
	}
	
	protected function _beforeToHtml()
	{
//		$this->_setActiveMenu('s2b_menu/first_page');
		
		$this->addTab('dashboard_section', array(
				'label'     => Mage::helper('s2b')->__('Vendor Dashboard'),
				'content'       => $this->getLayout()->createBlock('s2b/adminhtml_dashboard')->toHtml(),
		));
		$this->addTab('details_section', array(
				'label'     => Mage::helper('s2b')->__('My Vendor details'),
            	'content'       => $this->getLayout()->createBlock('s2b/adminhtml_customeredit')->toHtml(),
		));/*
		$this->addTab('statistics_section', array(
				'label'     => Mage::helper('s2b')->__('vendor Statistics'),
				'class'     => 'ajax',
            	'url'       => $this->getUrl('s2b/adminhtml_tabs/info', array('_current' => true)),
		));*/

	
		return parent::_beforeToHtml();
	}
}