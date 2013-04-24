<?php 
class Yougento_S2b_Block_Adminhtml_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		parent::__construct();

		$this->_objectId = 'customvendor';
		$this->_mode = 'edit';
		$this->_blockGroup = 's2b';
		$this->_controller = 'adminhtml';
		$this->_updateButton('save',Mage::helper('s2b')->__('Save vendor resources'));
		$this->_removeButton('back');
		$this->_removeButton('reset');
	}
    public function getHeaderText()
    {
        return Mage::helper('s2b')->__('Vendor Resources');
    }

} 