<?php
class Yougento_S2b_Block_Adminhtml_Customeredit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function __construct()
	{
		parent::__construct();

		$this->_objectId = 'infoform';
		$this->_mode = 'customeredit';
		$this->_blockGroup = 's2b';
		$this->_controller = 'adminhtml';
		$this->_removeButton('save');
		$this->_removeButton('back');
		$this->_removeButton('reset');
		$this->_addButton('savebutton', array(
            'label'     => Mage::helper('adminhtml')->__('Save my details'),
            'onclick'   => "document.forms['infoform'].submit();",
            'class'     => 'save',
        ),-1,5);
	}
    public function getHeaderText()
    {
        return Mage::helper('s2b')->__('Vendor Details');
    }

} 
?>