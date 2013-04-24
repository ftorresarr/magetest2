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

class MageMods_SupportSuite_Block_Adminhtml_Ticket_Edit_Tab_Message_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('ticket_');

        $fieldset = $form->addFieldset('message_fieldset', array('class'  => 'fieldset-wide'));

        $fieldset->addField('template', 'select', array(
            'label'  => Mage::helper('supportsuite')->__('Template'),
            'name'   => 'template',
            'style'  => 'display:inline',
            'after_element_html' => '<button type="button" onclick="javascript:loadTemplate()">' . Mage::helper('supportsuite')->__('Load') . '</button>',
            'values' => Mage::getModel('supportsuite/template')->getCollection()->toOptionArray()
        ));

        $fieldset->addField('message', 'textarea', array(
            'label'    => Mage::helper('supportsuite')->__('Message'),
            'name'     => 'message',
            'required' => Mage::registry('current_ticket')->getId() ? false : true,
            'value'    => Mage::registry('current_message')->getMessage()
        ));

        $fieldset->addField('attachment0', 'file', array(
            'label' => Mage::helper('supportsuite')->__('Attachment'),
            'name'  => 'attachment[]',
        ));

        $fieldset->addField('button', 'label', array(
            'after_element_html' => '<button type="button" onclick="javascript:addAttachment(this)">' . Mage::helper('supportsuite')->__('Add Attachment') . '</button>'
        ));

        $fieldset->addField('emailcopy', 'checkbox', array(
            'label'    => Mage::helper('supportsuite')->__('Email Copy of Response'),
            'name'     => 'emailcopy',
            'value'    => 1,
            'checked'  => 1
        ));

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
