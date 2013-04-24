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

class MageMods_SupportSuite_Block_Adminhtml_Ticket_Edit_Tab_Note_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('ticket_');

        $fieldset = $form->addFieldset('note_fieldset', array('class'  => 'fieldset-wide'));

        $fieldset->addField('note', 'textarea', array(
            'label' => Mage::helper('supportsuite')->__('Note'),
            'name'  => 'note',
            'value' => Mage::registry('current_note')->getNote()
        ));

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
