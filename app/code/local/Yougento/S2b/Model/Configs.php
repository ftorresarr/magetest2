<?php
class Yougento_S2b_Model_Configs extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('s2b/configs');
	}
}