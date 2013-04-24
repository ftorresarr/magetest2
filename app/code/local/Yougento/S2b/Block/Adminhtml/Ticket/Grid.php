<?php

class Yougento_S2b_Block_Adminhtml_Ticket_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('ticket_grid');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
    }

    protected function _prepareCollection()
    {

    		$customerId=$this->getRequest()->getParams();
			if (isset($customerId['id'])) {
				$this->setCollection(Mage::getModel('supportsuite/ticket')->getCollection()
				->addFieldToFilter('vendorid',array('eq'=>$customerId['id'])));

        return parent::_prepareCollection();
			}

    }

    protected function _prepareColumns()
    {
        $this->addColumn('increment_id', array(
            'header' => Mage::helper('supportsuite')->__('Ticket #'),
            'width'  => '80px',
            'type'   => 'text',
            'index'  => 'increment_id',
        ));

        $this->addColumn('priority', array(
            'header'  => Mage::helper('supportsuite')->__('Priority'),
            'align'   => 'left',
            'index'   => 'priority',
            'type'    => 'options',
            'options' => Mage::getSingleton('supportsuite/ticket_priority')->toOptionHash()
        ));

        $this->addColumn('open', array(
            'header'  => Mage::helper('supportsuite')->__('Open'),
            'align'   => 'left',
            'index'   => 'open',
            'type'    => 'options',
            'options' => array(0 => Mage::helper('supportsuite')->__('No'), 1 => Mage::helper('supportsuite')->__('Yes'))
        ));

        $this->addColumn('subject', array(
            'header' => Mage::helper('supportsuite')->__('Subject'),
            'align'  => 'left',
            'index'  => 'subject',
        ));

        $this->addColumn('email', array(
            'header' => Mage::helper('supportsuite')->__('Email'),
            'align'  => 'left',
            'index'  => 'email',
        ));

        $this->addColumn('created_at', array(
            'header' => Mage::helper('supportsuite')->__('Submitted On'),
            'index'  => 'created_at',
            'type'   => 'datetime',
            'width'  => '150px',
        ));

        $this->addColumn('query_at', array(
            'header' => Mage::helper('supportsuite')->__('Last Customer Query'),
            'index'  => 'query_at',
            'type'   => 'datetime',
            'width'  => '150px',
        ));

        $this->addColumn('reply_at', array(
            'header' => Mage::helper('supportsuite')->__('Last Staff Reply'),
            'index'  => 'reply_at',
            'type'   => 'datetime',
            'width'  => '150px',
        ));
    }


    public function getRowUrl($row)
    {
        return $this->getUrl('supportsuiteadmin/adminhtml_ticket/edit', array('ticket_id' => $row->getId()));
    }
}
