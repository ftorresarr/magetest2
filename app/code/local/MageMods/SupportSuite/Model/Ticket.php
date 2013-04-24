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

class MageMods_SupportSuite_Model_Ticket
    extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('supportsuite/ticket');
    }

    public function getThread()
    {
        return Mage::getModel('supportsuite/ticket_message')
            ->getCollection()
            ->addFieldToFilter('ticket_id', $this->getId())
            ->addOrder('created_at', 'asc');
    }

    public function getNotes()
    {
        return Mage::getModel('supportsuite/ticket_note')
            ->getCollection()
            ->addFieldToFilter('ticket_id', $this->getId())
            ->addOrder('created_at', 'asc');
    }

    public function sendNewTicketEmail()
    {
        $translate = Mage::getSingleton('core/translate');

        $translate->setTranslateInline(false);

        Mage::getModel('core/email_template')
            ->setDesignConfig(array('area' => 'frontend', 'store' => $this->getStoreId()))
            ->sendTransactional(
                Mage::helper('supportsuite')->getTicketNewEmailTemplate($this->getStoreId()),
                Mage::helper('supportsuite')->getTicketEmailIdentity($this->getStoreId()),
                $this->getEmail(),
                $this->getName(),
                array('ticket' => $this, 'store' => Mage::app()->getStore($this->getStoreId()))
            );

        $translate->setTranslateInline(true);

        return $this;
    }

    public function getFrontendUrl()
    {
        return Mage::getModel('core/url')
            ->setStore($this->getStoreId())
            ->getUrl('supportsuite/ticket/view', array('token' => $this->getToken()));
    }

    protected function _beforeDelete()
    {
        $this->_protectFromNonAdmin();

        foreach ($this->getThread() as $message) {
            $message->delete();
        }

        foreach ($this->getNotes() as $note) {
            $note->delete();
        }

        return parent::_beforeDelete();
    }
}
