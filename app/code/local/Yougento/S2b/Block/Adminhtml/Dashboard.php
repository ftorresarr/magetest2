<?php
class Yougento_S2b_Block_Adminhtml_Dashboard extends Mage_Core_Block_Template
{
	protected function _prepareLayout()
	{
		parent::__construct();
		$this->setTemplate('s2b/dashboard.phtml');
	}
}