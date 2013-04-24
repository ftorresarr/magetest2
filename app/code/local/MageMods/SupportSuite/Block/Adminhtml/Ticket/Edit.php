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

class MageMods_SupportSuite_Block_Adminhtml_Ticket_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'ticket_id';
        $this->_controller = 'adminhtml_ticket';
        $this->_blockGroup = 'supportsuite';

        parent::__construct();

        //$this->_removeButton('delete');

        $this->_addButton('saveandcontinue', array(
            'label'   => Mage::helper('supportsuite')->__('Save and Continue Edit'),
            'onclick' => 'editForm.submit($(\'edit_form\').action+\'back/edit/\')',
            'class'   => 'save',
        ), -100);

        $quoteUrl = Mage::getSingleton('adminhtml/url')->getUrl('*/adminhtml_ticket/quote');

        $templateUrl = Mage::getSingleton('adminhtml/url')->getUrl('*/adminhtml_template/load');

        $attachmentLabel = Mage::helper('supportsuite')->__('Attachment');

        $templateAlert = Mage::helper('supportsuite')->__('First, select a template!');

        $this->_formScripts[] = <<<TEXT
function quoteMessage(messageId) {
    new Ajax.Request('$quoteUrl', {
        method: 'get',
        parameters: { message_id: messageId },
        onComplete: function(transport) {
            $('ticket_message').setValue($('ticket_message').getValue() + transport.responseText + "\\n");
        }
    });
}

var attachmentCount = 1;

function addAttachment(element) {
    var html = '<tr><td class="label"><label for="ticket_attachment' + attachmentCount + '">$attachmentLabel</label></td>' +
    '<td class="value"><input id="ticket_attachment' + attachmentCount + '" type="file" name="attachment[]" /></td></tr>';

    element.parentNode.parentNode.insert({before: html});
    attachmentCount++;
}

function loadTemplate() {
    if ($('ticket_template').value) {
        new Ajax.Request('$templateUrl', {
            method: 'get',
            parameters: { template_id: $('ticket_template').value },
            onComplete: function(transport) {
                $('ticket_message').setValue($('ticket_message').getValue() + transport.responseText + "\\n");
            }
        });
    } else {
        alert('$templateAlert');
    }
}
TEXT;
    }

    public function getHeaderText()
    {
        if (Mage::registry('current_ticket')->getId()) {
            return Mage::helper('supportsuite')->__("Edit ticket #%s: '%s'", $this->htmlEscape(Mage::registry('current_ticket')->getIncrementId()), $this->htmlEscape(Mage::registry('current_ticket')->getSubject()));
        } else {
            return Mage::helper('supportsuite')->__('New ticket');
        }
    }
}
