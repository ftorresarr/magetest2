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

class MageMods_SupportSuite_Block_Adminhtml_Template_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('template_grid');
        $this->setDefaultSort('template_id');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection()
    {
        $this->setCollection(Mage::getModel('supportsuite/template')->getCollection());

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('template_id', array(
            'header' => Mage::helper('supportsuite')->__('Template #'),
            'width'  => '80px',
            'type'   => 'number',
            'index'  => 'template_id',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('supportsuite')->__('Title'),
            'align'  => 'left',
            'index'  => 'title'
        ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('supportsuite')->__('Action'),
                'width'     => '100px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('supportsuite')->__('Edit'),
                        'url'     => array('base' => '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('template_id');

        $this->getMassactionBlock()->setFormFieldName('template_ids');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'   => Mage::helper('supportsuite')->__('Delete'),
             'url'     => $this->getUrl('*/*/massDelete'),
             'confirm' => Mage::helper('supportsuite')->__('Are you sure?')
        ));

        return parent::_prepareMassaction();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('template_id' => $row->getId()));
    }
}
