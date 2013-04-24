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

class MageMods_SupportSuite_Block_Adminhtml_Template_Edit_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('template_form');

        $this->setTitle(Mage::helper('supportsuite')->__('Template Information'));
    }

    protected function _prepareForm()
    {
        $template = Mage::registry('current_template');

        $form = new Varien_Data_Form(array(
            'id'      => 'edit_form',
            'action'  => $this->getData('action'),
            'method'  => 'post',
        ));

        $form->setHtmlIdPrefix('template_');

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('supportsuite')->__('Template Information'),
            'class'  => 'fieldset-wide'
        ));

        if ($template->getId()) {
            $fieldset->addField('template_id', 'hidden', array(
                'name'  => 'template_id',
                'value' => $template->getId()
            ));
        }

        $fieldset->addField('title', 'text', array(
            'name'     => 'title',
            'label'    => Mage::helper('supportsuite')->__('Title'),
            'title'    => Mage::helper('supportsuite')->__('Title'),
            'required' => true,
            'value'    => $template->getTitle()
        ));

        $fieldset->addField('content', 'textarea', array(
            'name'     => 'content',
            'label'    => Mage::helper('supportsuite')->__('Content'),
            'title'    => Mage::helper('supportsuite')->__('Content'),
            'required' => true,
            'value'    => $template->getContent()
        ));

        $form->setUseContainer(true);

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
