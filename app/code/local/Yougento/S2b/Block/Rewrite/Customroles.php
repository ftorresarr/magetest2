<?php
class Yougento_S2b_Block_Rewrite_Customroles extends Mage_Adminhtml_Block_Permissions_Editroles
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if($this->getRequest()->getParams('id')){
        $this->addTab('Vendor Resources', array(
            'label'     => Mage::helper('s2b')->__('Vendor Resources'),
            'url'       => $this->getUrl('s2b/adminhtml_custom/vendor', array('_current' => true)),
            'class'     => 'ajax',
        ));
		}
        return $this;
    }
}