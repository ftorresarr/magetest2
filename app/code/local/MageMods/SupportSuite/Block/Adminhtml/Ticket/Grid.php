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

class MageMods_SupportSuite_Block_Adminhtml_Ticket_Grid
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
		$roleId = implode('', Mage::getSingleton('admin/session')->getUser()->getRoles());
		$roleName = Mage::getModel('admin/roles')->load($roleId)->getRoleName();
		
		if($roleName == 'Administrators')
		{
				$this->setCollection(Mage::getModel('supportsuite/ticket')->getCollection());
		}else{
    		$customerId=$this->getRequest()->getParams();
				$this->setCollection(Mage::getModel('supportsuite/ticket')->getCollection()
				->addFieldToFilter('vendorid',array('eq'=>$customerId['id'])));
		}
        return parent::_prepareCollection();
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

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('ticket_id');
        $this->getMassactionBlock()->setFormFieldName('ticket_ids');
        $this->getMassactionBlock()->setUseSelectAll(false);

        $this->getMassactionBlock()->addItem('delete', array(
             'label'=> Mage::helper('adminhtml')->__('Delete'),
             'url'  => $this->getUrl('*/*/massDelete'),
        ));

        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('ticket_id' => $row->getId()));
    }
}
