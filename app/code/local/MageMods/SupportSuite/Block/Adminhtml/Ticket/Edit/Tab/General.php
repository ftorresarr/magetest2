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

class MageMods_SupportSuite_Block_Adminhtml_Ticket_Edit_Tab_General
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _prepareForm()
    {
        $ticket = Mage::registry('current_ticket');

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('ticket_');

        $fieldset = $form->addFieldset('general_fieldset', array(
            'legend' => Mage::helper('supportsuite')->__('Ticket Options')
        ));

        if ($ticket->getId()) {
            $fieldset->addField('ticket_id', 'hidden', array(
                'name'  => 'ticket_id',
                'value' => $ticket->getId()
            ));
        } else {
            if (!Mage::app()->isSingleStoreMode()) {
                $fieldset->addField('store_id', 'select', array(
                    'name'     => 'store_id',
                    'label'    => Mage::helper('supportsuite')->__('Store View'),
                    'title'    => Mage::helper('supportsuite')->__('Store View'),
                    'required' => true,
                    'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, false),
                    'value'    => $ticket->getStoreId()
                ));
            } else {
                $fieldset->addField('store_id', 'hidden', array(
                    'name'  => 'store_id',
                    'value' => Mage::app()->getStore(true)->getId()
                ));
            }
        }

        $fieldset->addField('subject', 'text', array(
            'name'     => 'subject',
            'label'    => Mage::helper('supportsuite')->__('Subject'),
            'title'    => Mage::helper('supportsuite')->__('Subject'),
            'required' => true,
            'value'    => $ticket->getSubject()
        ));

        $fieldset->addField('name', 'text', array(
            'name'     => 'name',
            'label'    => Mage::helper('supportsuite')->__('Customer Name'),
            'title'    => Mage::helper('supportsuite')->__('Customer Name'),
            'required' => true,
            'value'    => $ticket->getName()
        ));

        $fieldset->addField('email', 'text', array(
            'name'     => 'email',
            'label'    => Mage::helper('supportsuite')->__('Customer Email'),
            'title'    => Mage::helper('supportsuite')->__('Customer Email'),
            'required' => true,
            'value'    => $ticket->getEmail()
        ));

        $fieldset->addField('priority', 'select', array(
            'name'     => 'priority',
            'label'    => Mage::helper('supportsuite')->__('Priority'),
            'title'    => Mage::helper('supportsuite')->__('Priority'),
            'values'   => Mage::getSingleton('supportsuite/ticket_priority')->toOptionArray(),
            'required' => true,
            'value'    => $ticket->getPriority()
        ));

        $fieldset->addField('open', 'select', array(
            'name'     => 'open',
            'label'    => Mage::helper('supportsuite')->__('Open'),
            'title'    => Mage::helper('supportsuite')->__('Open'),
            'values'   => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
            'required' => true,
            'value'    => $ticket->getOpen()
        ));

        $fieldset->addField('order_increment_id', 'text', array(
            'name'     => 'order_increment_id',
            'label'    => Mage::helper('supportsuite')->__('Order #'),
            'title'    => Mage::helper('supportsuite')->__('Order #'),
            'value'    => $ticket->getOrderIncrementId()
        ));

        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getTabLabel()
    {
        return Mage::helper('supportsuite')->__('Ticket Information');
    }

    public function getTabTitle()
    {
        return Mage::helper('supportsuite')->__('Ticket Information');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
}
