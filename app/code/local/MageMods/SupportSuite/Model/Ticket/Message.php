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

class MageMods_SupportSuite_Model_Ticket_Message
    extends Mage_Core_Model_Abstract
{
    protected $_ticket;

    protected function _construct()
    {
        $this->_init('supportsuite/ticket_message');
    }

    protected function _beforeSave()
    {
        if (!$this->getTicketId() && $this->getTicket()) {
            $this->setTicketId($this->getTicket()->getId());
        }

        return parent::_beforeSave();
    }

    protected function _beforeDelete()
    {
        foreach ($this->getAttachments() as $attachment) {
            $attachment->delete();
        }

        return parent::_beforeDelete();
    }

    public function setTicket($ticket)
    {
        $this->_ticket = $ticket;

        return $this;
    }

    public function getTicket()
    {
        if (is_null($this->_ticket)) {
            $this->_ticket = Mage::getModel('supportsuite/ticket')->load($this->getTicketId());
        }

        return $this->_ticket;
    }

    public function getAttachments()
    {
        return Mage::getModel('supportsuite/ticket_attachment')
            ->getCollection()
            ->addFieldToFilter('message_id', $this->getId());
    }

    public function sendNewReplyEmail()
    {
//        if (!Mage::helper('supportsuite')->canSendTicketUpdateEmail($this->getStoreId())) {
//            return $this;
//        }

        $translate = Mage::getSingleton('core/translate');

        $translate->setTranslateInline(false);

        $template = Mage::getModel('core/email_template');

        foreach ($this->getAttachments() as $attachment) {
            if (!is_readable(Mage::helper('supportsuite')->getAttachmentPath() . $attachment->getId())) {
                continue;
            }

            $template->getMail()
                ->createAttachment(
                    file_get_contents(Mage::helper('supportsuite')->getAttachmentPath() . $attachment->getId()),
                    $attachment->getType(),
                    Zend_Mime::DISPOSITION_ATTACHMENT,
                    Zend_Mime::ENCODING_BASE64,
                    $attachment->getName()
                );
        }

        $template->setDesignConfig(array('area' => 'frontend', 'store' => $this->getTicket()->getStoreId()))
            ->sendTransactional(
                Mage::helper('supportsuite')->getTicketReplyEmailTemplate($this->getTicket()->getStoreId()),
                Mage::helper('supportsuite')->getTicketEmailIdentity($this->getTicket()->getStoreId()),
                $this->getTicket()->getEmail(),
                $this->getTicket()->getName(),
                array(
                    'ticket'  => $this->getTicket(),
                    'message' => $this,
                    'store'   => Mage::app()->getStore($this->getTicket()->getStoreId())
                )
            );

        $translate->setTranslateInline(true);

        return $this;
    }
}
