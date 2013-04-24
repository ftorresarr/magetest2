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

class MageMods_SupportSuite_Helper_Data
    extends Mage_Core_Helper_Abstract
{
    const XML_PATH_MAILBOX_ENABLED    = 'supportsuite/mailbox/enabled';
    const XML_PATH_MAILBOX_PROTOCOL   = 'supportsuite/mailbox/protocol';
    const XML_PATH_MAILBOX_HOST       = 'supportsuite/mailbox/host';
    const XML_PATH_MAILBOX_PORT       = 'supportsuite/mailbox/port';
    const XML_PATH_MAILBOX_SSL        = 'supportsuite/mailbox/ssl';
    const XML_PATH_MAILBOX_USERNAME   = 'supportsuite/mailbox/username';
    const XML_PATH_MAILBOX_PASSWORD   = 'supportsuite/mailbox/password';
    const XML_PATH_MAILBOX_AUTODELETE = 'supportsuite/mailbox/autodelete';

    const XML_PATH_TICKET_UPLOAD_LIMIT         = 'supportsuite/ticket/upload_limit';
    const XML_PATH_TICKET_ATTACHMENT_LIMIT     = 'supportsuite/ticket/attachment_limit';
    const XML_PATH_TICKET_DEFAULT_PRIORITY     = 'supportsuite/ticket/default_priority';
    const XML_PATH_TICKET_FORM_OVERRIDE        = 'supportsuite/ticket/form_override';
    const XML_PATH_TICKET_QUESTION_FORM        = 'supportsuite/ticket/question_form';
    const XML_PATH_TICKET_EMAIL_IDENTITY       = 'supportsuite/ticket/email_identity';
    const XML_PATH_TICKET_NEW_EMAIL_TEMPLATE   = 'supportsuite/ticket/new_email_template';
    const XML_PATH_TICKET_REPLY_EMAIL_TEMPLATE = 'supportsuite/ticket/reply_email_template';

    public function getMailboxEnabled($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_MAILBOX_ENABLED, $store);
    }

    public function getMailboxProtocol($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_MAILBOX_PROTOCOL, $store);
    }

    public function getMailboxHost($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_MAILBOX_HOST, $store);
    }

    public function getMailboxPort($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_MAILBOX_PORT, $store);
    }

    public function getMailboxSsl($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_MAILBOX_SSL, $store);
    }

    public function getMailboxUsername($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_MAILBOX_USERNAME, $store);
    }

    public function getMailboxPassword($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_MAILBOX_PASSWORD, $store);
    }

    public function getMailboxAutodelete($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_MAILBOX_AUTODELETE, $store);
    }

    public function getTicketUploadLimit($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_TICKET_UPLOAD_LIMIT, $store);
    }

    public function getTicketAttachmentLimit($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_TICKET_ATTACHMENT_LIMIT, $store);
    }

    public function getTicketDefaultPriority($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_TICKET_DEFAULT_PRIORITY, $store);
    }

    public function getTicketOverrideForm($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_TICKET_FORM_OVERRIDE, $store);
    }

    public function getTicketEmailIdentity($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_TICKET_EMAIL_IDENTITY, $store);
    }

    public function getTicketNewEmailTemplate($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_TICKET_NEW_EMAIL_TEMPLATE, $store);
    }

    public function getTicketReplyEmailTemplate($store = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_TICKET_REPLY_EMAIL_TEMPLATE, $store);
    }

    public function getAttachmentPath()
    {
        return Mage::getBaseDir('media') . DS . 'supportsuite' . DS;
    }

    public function getTicketQuestionForm($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_TICKET_QUESTION_FORM, $store);
    }
}
