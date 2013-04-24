<?php
class Yougento_S2b_Block_Adminhtml_Ticket
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_ticket';
        $this->_blockGroup = 's2b';
        $this->_headerText = Mage::helper('adminhtml')->__('Manage Tickets');

        parent::__construct();
    }
}
