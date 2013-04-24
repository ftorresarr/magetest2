<?php
/**
 * MageMods
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MageMods EULA that is bundled with
 * this package in the file LICENSE.txt. It is also available through
 * the world-wide-web at this URL: http://www.magemods.co/LICENSE-1.0.txt
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to sales@magemods.co so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension to
 * newer versions in the future. If you wish to customize the extension for your
 * needs please refer to http://www.magemods.co/ for more information.
 */

class MageMods_SupportSuite_Block_Adminhtml_Sales_Order_View_Tab_Ticket
    extends Mage_Adminhtml_Block_Widget_Grid
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('supportsuite_ticket');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $this->setCollection(
            Mage::getModel('supportsuite/ticket')
                ->getCollection()
                ->addFieldToFilter('order_id', Mage::registry('current_order')->getId())
        );

        return parent::_prepareCollection();
    }

    public function getMainButtonsHtml()
    {
        $button = $this->getLayout()
            ->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label'   => Mage::helper('supportsuite')->__('Create Ticket'),
                'onclick' => "window.location.href='" . $this->getUrl('supportsuiteadmin/adminhtml_ticket/new', array('order_id' => Mage::registry('current_order')->getId())) . "'",
                'class'   => 'task'
            ))->toHtml();

        return $button . parent::getMainButtonsHtml();
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

    public function getGridUrl()
    {
        return $this->getUrl('supportsuiteadmin/adminhtml_ticket/orderTicket', array('order_id' => Mage::registry('current_order')->getId()));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('supportsuiteadmin/adminhtml_ticket/edit', array('ticket_id' => $row->getId()));
    }

    public function getTabLabel()
    {
        return Mage::helper('supportsuite')->__('Tickets');
    }

    public function getTabTitle()
    {
        return Mage::helper('supportsuite')->__('Tickets');
    }

    public function canShowTab()
    {
        return Mage::registry('current_order')->getId();
    }

    public function isHidden()
    {
        return false;
    }
}
