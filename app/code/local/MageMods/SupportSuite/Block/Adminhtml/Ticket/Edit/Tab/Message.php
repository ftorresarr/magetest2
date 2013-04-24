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

class MageMods_SupportSuite_Block_Adminhtml_Ticket_Edit_Tab_Message
    extends Mage_Adminhtml_Block_Widget_Accordion
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function getTabLabel()
    {
        return Mage::helper('supportsuite')->__('Messages');
    }

    public function getTabTitle()
    {
        return Mage::helper('supportsuite')->__('Messages');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    protected function _beforeToHtml()
    {
        $ticket = Mage::registry('current_ticket');

        foreach ($ticket->getThread() as $message) {
            $this->addItem('Message'.$message->getId(), array(
                'title'   => $this->formatDate($message->getCreatedAt(), 'short', true),
                'content' => $this->getLayout()
                                  ->createBlock('supportsuite/adminhtml_ticket_edit_tab_message_view')
                                  ->setTicket($ticket)
                                  ->setMessage($message)
                                  ->toHtml(),
                'open'    => true
            ));
        }

        $this->addItem('Reply', array(
            'title'    => Mage::helper('supportsuite')->__('Reply'),
            'content'  => $this->getLayout()->createBlock('supportsuite/adminhtml_ticket_edit_tab_message_form')->toHtml(),
            'open'     => true
        ));
    }
}