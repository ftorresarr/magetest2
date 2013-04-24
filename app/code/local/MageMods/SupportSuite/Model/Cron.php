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

class MageMods_SupportSuite_Model_Cron
{
    public function run()
    {
        foreach (Mage::app()->getStores() as $store) {
            try {
                if (Mage::helper('supportsuite')->getMailboxEnabled($store)) {
                    self::fetchMail($store);
                }
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }

    public static function fetchMail($store)
    {
        $config = array(
            'host'     => Mage::helper('supportsuite')->getMailboxHost($store),
            'port'     => Mage::helper('supportsuite')->getMailboxPort($store),
            'ssl'      => Mage::helper('supportsuite')->getMailboxSsl($store),
            'user'     => Mage::helper('supportsuite')->getMailboxUsername($store),
            'password' => Mage::helper('supportsuite')->getMailboxPassword($store),
        );

        switch (Mage::helper('supportsuite')->getMailboxProtocol($store)) {
            case 'POP3':
                $mailbox = new Zend_Mail_Storage_Pop3($config);
                break;

            case 'IMAP':
                $mailbox = new Zend_Mail_Storage_Imap($config);
                break;

            default:
                throw new Mage_Core_Exception('Undefined protocol selected!');
        }

        $oldIds = Mage::getModel('supportsuite/email')
            ->getCollection()
            ->loadUniqueIds();

        $newIds = array_diff($mailbox->getUniqueId(), $oldIds);

        foreach ($newIds as $id) {
            try {
                $transaction = Mage::getModel('core/resource_transaction');

                $mail = $mailbox->getMessage($mailbox->getNumberByUniqueId($id));

                $to = Mage::helper('supportsuite/mail')->decodeHeader($mail->headerExists('to') ? $mail->getHeader('to') : '');

                $from = Mage::helper('supportsuite/mail')->decodeHeader($mail->headerExists('from') ? $mail->getHeader('from') : '');

                if ($from == "" || strpos($to, Mage::getStoreConfig('trans_email/ident_'.Mage::helper('supportsuite')->getTicketEmailIdentity($store).'/email', $store)) === FALSE) {
                    continue;
                }

                $subject = Mage::helper('supportsuite/mail')->decodeHeader($mail->headerExists('subject') ? $mail->getHeader('subject') : '');

                $email = $name = "";

                if (preg_match('~(?<name>.+)<(?<email>[a-z0-9.\-_]+@[a-z0-9.\-_]+)>~i', $from, $matches)) {
                    $name  = $matches['name'];
                    $email = $matches['email'];
                } elseif (preg_match('~(?<email>[a-z0-9.\-_]+@[a-z0-9.\-_]+)~i', $from, $matches)) {
                    $name = $email = $matches['email'];
                }

                if (!$email) {
                    continue;
                }

                $ticket = Mage::getModel('supportsuite/ticket');

                if (preg_match('~\[#([0-9]+)\]~', $subject, $matches)) {
                    $ticket->load($matches[1], 'increment_id');
                }

                if (!$ticket->getId() || $ticket->getEmail() != $email || !$ticket->getOpen()) {
                    $ticket = Mage::getModel('supportsuite/ticket')
                        ->setStoreId($store->getId())
                        ->setSubject($subject)
                        ->setOpen(1)
                        ->setQueryAt(Mage::getSingleton('core/date')->gmtDate())
                        ->setEmail($email)
                        ->setName($name)
                        ->setPriority(Mage::helper('supportsuite')->getTicketDefaultPriority($store));

                    $customer = Mage::getModel('customer/customer')
                        ->setWebsiteId($store->getWebsiteId())
                        ->loadByEmail($ticket->getEmail());

                    if ($customer->getId()) {
                        $ticket->setCustomerId($customer->getId());
                    }

                    $newTicketEmail = true;
                }

                $transaction->addObject($ticket);

                $textPart       = null;
                $attachmentPart = array();

                if ($mail->isMultipart()) {
                    foreach (new RecursiveIteratorIterator($mail) as $part) {
                        try {
                            if (!$textPart && $part->getHeaderField('content-type') == 'text/plain') {
                                $textPart = $part;
                            } else {
                                $attachmentPart[] = $part;
                            }
                        } catch (Zend_Mail_Exception $e) {
                            $attachmentPart[] = $part;
                        }
                    }
                } else {
                    $textPart = $mail;
                }

                $text = null;

                if ($textPart) {
                    $text = $textPart->getContent();

                    if ($textPart->headerExists('content-transfer-encoding')
                    && $encoding = $textPart->getHeader('content-transfer-encoding')) {
                        switch ($encoding) {
                            case 'base64':           $text = base64_decode($text); break;
                            case 'quoted-printable': $text = quoted_printable_decode($text); break;
                        }
                    }

                    if ($textPart->headerExists('content-type')
                    && $charset = $textPart->getHeaderField('content-type', 'charset')) {
                        $text = iconv($charset, 'UTF-8//TRANSLIT', $text);
                    }
                }

                $transaction->addObject(
                    $message = Mage::getModel('supportsuite/ticket_message')
                        ->setTicket($ticket)
                        ->setMessage($text)
                );

                $transaction->addObject(
                    Mage::getModel('supportsuite/email')->setUniqueId($id)
                );

                foreach ($attachmentPart as $attachment) {
                    $type = $name = $content = null;

                    if ($attachment->headerExists('content-type')) {
                        $type = $attachment->getHeaderField('content-type');
                    }

                    if ($attachment->headerExists('content-disposition')) {
                        $name = $attachment->getHeaderField('content-disposition', 'filename');
                    }

                    $content = $attachment->getContent();

                    if ($attachment->headerExists('content-transfer-encoding')
                    && $encoding = $attachment->getHeader('content-transfer-encoding')) {
                        switch ($encoding) {
                            case 'base64':           $content = base64_decode($content); break;
                            case 'quoted-printable': $content = quoted_printable_decode($content); break;
                        }
                    }

                    $size = strlen($content);

                    if (Mage::helper('supportsuite')->getTicketAttachmentLimit()
                    &&  $size > Mage::helper('supportsuite')->getTicketUploadLimit()) {
                        throw new Mage_Core_Exception('Attachment size too large');
                    }

                    $transaction->addObject(
                        Mage::getModel('supportsuite/ticket_attachment')
                            ->setMessage($message)
                            ->setType($type)
                            ->setName($name)
                            ->setSize($size)
                            ->setContent($content)
                    );
                }

                $transaction->save();

                if (Mage::helper('supportsuite')->getMailboxAutodelete($store)) {
                    $mailbox->removeMessage($id);
                }

                if (isset($newTicketEmail)) {
                    $ticket->sendNewTicketEmail();
                }
            } catch (Mage_Core_Exception $e) {
                Mage::logException($e);
            }
        }
    }
}
