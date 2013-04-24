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

class MageMods_SupportSuite_Block_Ticket_List
    extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();

        $session = Mage::getSingleton('customer/session');

        $tickets = Mage::getModel('supportsuite/ticket')
            ->getCollection()
            ->addFieldToFilter('customer_id', $session->getCustomerId())
            ->addOrder('created_at', 'desc');

        $this->setTickets($tickets);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()
            ->createBlock('page/html_pager', 'supportsuite.ticket.list.pager')
            ->setCollection($this->getTickets());

        $this->setChild('pager', $pager);

        return $this;
    }

    public function getBackUrl()
    {
        if ($this->getRefererUrl()) {
            return $this->getRefererUrl();
        }

        return $this->getUrl('customer/account/');
    }

    public function getTicketViewUrl($token)
    {
        return $this->getUrl('supportsuite/ticket/view', array('token' => $token));
    }
}
