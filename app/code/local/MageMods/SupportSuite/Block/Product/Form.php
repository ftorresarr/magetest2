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

class MageMods_SupportSuite_Block_Product_Form
    extends Mage_Core_Block_Template
{
    public function __construct()
    {
        if (Mage::helper('supportsuite')->getTicketQuestionForm()) {
            $this->setTemplate('supportsuite/product/form.phtml');
        }

        parent::__construct();
    }

    public function getProductId()
    {
        return Mage::registry('current_product')->getId();
    }

    public function getSubject()
    {
        if ($data = Mage::getSingleton('customer/session')->getTicketFormData(true)) {
            return $data->getSubject();
        }

        return $this->__('Product Question - %s', Mage::registry('current_product')->getName());
    }

    public function getName()
    {
        if ($data = Mage::getSingleton('customer/session')->getTicketFormData(true)) {
            return $data->getName();
        } elseif (Mage::getSingleton('customer/session')->isLoggedIn()) {
            return Mage::getSingleton('customer/session')->getCustomer()->getName();
        }

        return '';
    }

    public function getRealemail()
    {
        if ($data = Mage::getSingleton('customer/session')->getTicketFormData(true)) {
            return $data->getRealemail();
        } elseif (Mage::getSingleton('customer/session')->isLoggedIn()) {
            return Mage::getSingleton('customer/session')->getCustomer()->getEmail();
        }

        return '';
    }

    public function getMessage()
    {
        if ($data = Mage::getSingleton('customer/session')->getTicketFormData(true)) {
            return $data->getMessage();
        }

        return '';
    }
}
