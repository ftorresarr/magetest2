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

class MageMods_SupportSuite_Model_Ticket_Note
    extends Mage_Core_Model_Abstract
{
    protected $_ticket;

    protected function _construct()
    {
        $this->_init('supportsuite/ticket_note');
    }

    protected function _beforeSave()
    {
        if (!$this->getTicketId() && $this->getTicket()) {
            $this->setTicketId($this->getTicket()->getId());
        }

        return parent::_beforeSave();
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
}
