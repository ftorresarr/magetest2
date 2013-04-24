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

class MageMods_SupportSuite_Block_Adminhtml_Template_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId   = 'template_id';
        $this->_controller = 'adminhtml_template';
        $this->_blockGroup = 'supportsuite';

        parent::__construct();

        $this->_addButton('saveandcontinue', array(
            'label'   => Mage::helper('supportsuite')->__('Save and Continue Edit'),
            'onclick' => 'editForm.submit($(\'edit_form\').action+\'back/edit/\')',
            'class'   => 'save',
        ), -100);
    }

    public function getHeaderText()
    {
        if (Mage::registry('current_template')->getId()) {
            return Mage::helper('supportsuite')->__('Edit template: \'%s\'', $this->htmlEscape(Mage::registry('current_template')->getTitle()));
        } else {
            return Mage::helper('supportsuite')->__('New template');
        }
    }
}
