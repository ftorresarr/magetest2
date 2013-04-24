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

class MageMods_SupportSuite_AttachmentController
    extends Mage_Core_Controller_Front_Action
{
    public function downloadAction()
    {
        $ticket = Mage::getModel('supportsuite/ticket')
            ->load($this->getRequest()->getParam('token'), 'token');

        if (!$ticket->getId()) {
            $this->_forward('noRoute');

            return false;
        }

        $attachment = Mage::getModel('supportsuite/ticket_attachment')
            ->load($this->getRequest()->getParam('attachment_id'));

        if (!$attachment->getId() || !is_readable(Mage::helper('supportsuite')->getAttachmentPath() . $attachment->getId())) {
            $this->_forward('noRoute');

            return false;
        }

        $this->getResponse()
             ->setHttpResponseCode(200)
             ->setHeader('Pragma', 'public', true)
             ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
             ->setHeader('Content-Type', $attachment->getType(), true);

        if ($attachment->getSize()) {
            $this->getResponse()
                 ->setHeader('Content-Length', $attachment->getSize());
        }

        if ($attachment->getName()) {
            $this->getResponse()
                 ->setHeader('Content-Disposition', 'attachment; filename=' . $attachment->getName());
        }

        $this->getResponse()->sendHeaders();

        $fp = fopen(Mage::helper('supportsuite')->getAttachmentPath() . $attachment->getId(), 'rb');

        while (!feof($fp)) {
            if ($chunk = fread($fp, 8192)) {
                echo $chunk;
            } else {
                break;
            }
        }

        fclose($fp);

        exit;
    }
}
