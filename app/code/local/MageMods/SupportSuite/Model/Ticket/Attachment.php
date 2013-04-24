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

class MageMods_SupportSuite_Model_Ticket_Attachment
    extends Mage_Core_Model_Abstract
{
    protected $_message;

    protected function _construct()
    {
        $this->_init('supportsuite/ticket_attachment');
    }

    public function setMessage($message)
    {
        $this->_message = $message;

        return $this;
    }

    public function getMessage()
    {
        if (is_null($this->_message)) {
            $this->_message = Mage::getModel('supportsuite/ticket_message')->load($this->getMessageId());
        }

        return $this->_message;
    }

    public function getName()
    {
        if ($this->getData('name')) {
            return $this->getData('name');
        } else {
            switch ($this->getType()) {
                case 'text/plain': $ext = 'txt'; break;
                case 'text/html': $ext = 'html'; break;
                default: $ext = 'dat'; break;
            }

            return 'attachment.' . $ext;
        }
    }

    protected function _beforeSave()
    {
        if (!$this->getMessageId() && $this->getMessage()) {
            $this->setMessageId($this->getMessage()->getId());
        }

        return parent::_beforeSave();
    }

    protected function _afterSave()
    {
        if ($this->getContent()) {
            if (!file_put_contents(
                Mage::helper('supportsuite')->getAttachmentPath() . $this->getId(),
                $this->getContent()
            )) {
                throw new Mage_Core_Exception('Unable to save attachment content to filesystem.');
            }
        }

        return parent::_afterSave();
    }

    protected function _afterDelete()
    {
        unlink(Mage::helper('supportsuite')->getAttachmentPath() . $this->getId());

        return parent::_afterDelete();
    }
}
