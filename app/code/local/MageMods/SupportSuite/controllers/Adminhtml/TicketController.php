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

class MageMods_SupportSuite_Adminhtml_TicketController
    extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('supportsuite/ticket');

        $this->_title($this->__('SupportSuite'))
             ->_title($this->__('Manage Tickets'));

        return $this;
    }

    public function customerTicketAction()
    {
        Mage::register('current_customer',
            Mage::getModel('customer/customer')->load(
                $this->getRequest()->getParam('customer_id')
            )
        );

        $this->getResponse()->setBody(
            $this->getLayout()
                 ->createBlock('supportsuite/adminhtml_customer_edit_tab_ticket')
                 ->toHtml()
        );
    }

    public function orderTicketAction()
    {
        Mage::register('current_order',
            Mage::getModel('sales/order')->load(
                $this->getRequest()->getParam('order_id')
            )
        );

        $this->getResponse()->setBody(
            $this->getLayout()
                 ->createBlock('supportsuite/adminhtml_sales_order_view_tab_ticket')
                 ->toHtml()
        );
    }

    public function indexAction()
    {
        $this->_initAction()
             ->_addContent($this->getLayout()->createBlock('supportsuite/adminhtml_ticket'))
             ->renderLayout();
    }

    public function quoteAction()
    {
        $message = Mage::getModel('supportsuite/ticket_message');

        if ($this->getRequest()->getParam('message_id')) {
            $message->load($this->getRequest()->getParam('message_id'));

            if (!$message->getId()) {
                $this->_getSession()->addError($this->__('This message no longer exists.'));

                $this->_redirect('*/*/');

                return;
            }

            $lines = array();

            foreach (explode("\n", str_replace(array("\r\n", "\r"), "\n", $message->getMessage())) as $line) {
                $lines[] = '> ' . $line;
            }

            Mage::app()->getResponse()->setBody(join("\n", $lines));
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function deleteAction()
    {
        $ticket = Mage::getModel('supportsuite/ticket');

        if ($this->getRequest()->getParam('ticket_id')) {
            $ticket->load($this->getRequest()->getParam('ticket_id'));
        }

        if (!$ticket->getId()) {
            $this->_getSession()->addError($this->__('This ticket no longer exists.'));

            $this->_redirect('*/*/');

            return;
        }

        try {
            $ticket->delete();

            $this->_getSession()->addSuccess(
                $this->__('The ticket has been deleted.')
            );
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/');
    }

    public function editAction()
    {
        $ticket  = Mage::getModel('supportsuite/ticket');
        $message = Mage::getModel('supportsuite/ticket_message');
        $note    = Mage::getModel('supportsuite/ticket_note');

        Mage::register('current_ticket', $ticket);
        Mage::register('current_message', $message);
        Mage::register('current_note', $note);

        if ($this->getRequest()->getParam('ticket_id')) {
            $ticket->load($this->getRequest()->getParam('ticket_id'));

            if (!$ticket->getId()) {
                $this->_getSession()->addError($this->__('This ticket no longer exists.'));

                $this->_redirect('*/*/');

                return;
            }
        } elseif ($this->getRequest()->getParam('customer_id')) {
            $customer = Mage::getModel('customer/customer')->load(
                $this->getRequest()->getParam('customer_id')
            );

            if ($customer->getId()) {
                if ($customer->getStoreId()) {
                    $storeId = $customer->getStoreId();
                } else {
                    $storeId = Mage::getModel('core/website')
                        ->load($customer->getWebsiteId())
                        ->getDefaultStore()
                        ->getId();
                }

                $ticket->setEmail($customer->getEmail())
                       ->setName($customer->getName())
                       ->setStoreId($storeId);
            }
        } elseif ($this->getRequest()->getParam('order_id')) {
            $order = Mage::getModel('sales/order')->load(
                $this->getRequest()->getParam('order_id')
            );

            if ($order->getId()) {
                $ticket->setEmail($order->getCustomerEmail())
                       ->setName($order->getCustomerName())
                       ->setOrderId($order->getId())
                       ->setOrderIncrementId($order->getIncrementId())
                       ->setStoreId($order->getStoreId());
            }
        }

        $this->_title($ticket->getId() ? $ticket->getSubject() : $this->__('New ticket'));

        $data = $this->_getSession()->getFormData(true);

        if (!empty($data)) {
            $ticket->setStoreId($data->getStoreId())
                   ->setSubject($data->getSubject())
                   ->setName($data->getName())
                   ->setEmail($data->getEmail())
                   ->setPriority($data->getPriority())
                   ->setOpen($data->getOpen())
                   ->setOrderIncrementId($data->getOrderIncrementId());

            $message->setMessage($data->getMessage());

            $note->setNote($data->getNote());
        } elseif (!$ticket->getId()) {
            $ticket->setOpen(1)
                   ->setPriority(Mage::helper('supportsuite')->getTicketDefaultPriority());
        }

        $this->_initAction()
             ->_addContent($this->getLayout()->createBlock('supportsuite/adminhtml_ticket_edit'))
             ->_addLeft(
                $this->getLayout()
                     ->createBlock('supportsuite/adminhtml_ticket_edit_tabs')
                     ->addTab('general', $this->getLayout()->createBlock('supportsuite/adminhtml_ticket_edit_tab_general'))
                     ->addTab('message', $this->getLayout()->createBlock('supportsuite/adminhtml_ticket_edit_tab_message'))
                     ->addTab('note', $this->getLayout()->createBlock('supportsuite/adminhtml_ticket_edit_tab_note'))
                )
             ->renderLayout();
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $ticket = Mage::getModel('supportsuite/ticket');

            if ($this->getRequest()->getParam('ticket_id')) {
                $ticket->load($this->getRequest()->getParam('ticket_id'));
            }

            try {
                $files = array();

                foreach ($_FILES['attachment']['name'] as $key => $value) {
                    if ($_FILES['attachment']['error'][$key] != UPLOAD_ERR_OK) {
                        if ($_FILES['attachment']['error'][$key] != UPLOAD_ERR_NO_FILE) {
                            throw new Mage_Core_Exception($this->__('Upload error.'));
                        }
                    } elseif ($_FILES['attachment']['size'][$key] > Mage::helper('supportsuite')->getTicketUploadLimit()) {
                        throw new Mage_Core_Exception($this->__('Uploaded file is too large.'));
                    } elseif (!is_uploaded_file($_FILES['attachment']['tmp_name'][$key])) {
                        throw new Mage_Core_Exception($this->__('Upload error.'));
                    } else {
                        $files[] = array(
                            'name'     => $_FILES['attachment']['name'][$key],
                            'type'     => $_FILES['attachment']['type'][$key],
                            'size'     => $_FILES['attachment']['size'][$key],
                            'tmp_name' => $_FILES['attachment']['tmp_name'][$key]
                        );
                    }
                }

                if (!Zend_Validate::is($this->getRequest()->getPost('email'), 'EmailAddress')) {
                    throw new Mage_Core_Exception($this->__('Invalid email address.'));
                }

                if (!Zend_Validate::is(trim($this->getRequest()->getPost('name')), 'NotEmpty')) {
                    throw new Mage_Core_Exception($this->__('The name cannot be empty.'));
                }

                if (!Zend_Validate::is(trim($this->getRequest()->getPost('subject')), 'NotEmpty')) {
                    throw new Mage_Core_Exception($this->__('The subject cannot be empty.'));
                }

                $transaction = Mage::getModel('core/resource_transaction');

                if (!$ticket->getId()) {
                    if (!Zend_Validate::is(trim($this->getRequest()->getPost('store_id')), 'NotEmpty')) {
                        throw new Mage_Core_Exception($this->__('The store cannot be empty.'));
                    }

                    if (!Zend_Validate::is(trim($this->getRequest()->getPost('message')), 'NotEmpty')) {
                        throw new Mage_Core_Exception($this->__('The message cannot be empty.'));
                    }

                    if (!Mage::getModel('core/store')->load($data['store_id'])->getId()) {
                        throw new Mage_Core_Exception($this->__('This store no longer exists.'));
                    }

                    $ticket->setStoreId($data['store_id']);

                    $customer = Mage::getModel('customer/customer')
                        ->setWebsiteId(Mage::app()->getStore($this->getRequest()->getPost('store_id'))->getWebsiteId())
                        ->loadByEmail($data['email']);

                    $ticket->setCustomerId($customer->getId());
                }

                if ($data['order_increment_id']) {
                    $order = Mage::getModel('sales/order')->loadByIncrementId($data['order_increment_id']);

                    if (!$order->getId()) {
                        throw new Mage_Core_Exception($this->__('This order no longer exists.'));
                    }

                    $ticket->setOrderId($order->getId())
                           ->setOrderIncrementId($order->getIncrementId());
                }

                $transaction->addObject(
                    $ticket->setOpen($data['open'])
                        ->setName($data['name'])
                        ->setEmail($data['email'])
                        ->setSubject($data['subject'])
                        ->setPriority($data['priority'])
                );

                if (isset($data['note']) && !empty($data['note']))
                {
                    $transaction->addObject(
                        Mage::getModel('supportsuite/ticket_note')
                            ->setTicket($ticket)
                            ->setNote($data['note'])
                    );
                }

                if (isset($data['message']) && !empty($data['message']))
                {
                    $ticket->setReplyAt(Mage::getSingleton('core/date')->gmtDate());

                    $transaction->addObject(
                        $message = Mage::getModel('supportsuite/ticket_message')
                            ->setTicket($ticket)
                            ->setMessage($data['message'])
                    );

                    foreach ($files as $file) {
                        $transaction->addObject(
                            Mage::getModel('supportsuite/ticket_attachment')
                                ->setMessage($message)
                                ->setType($file['type'])
                                ->setSize($file['size'])
                                ->setName($file['name'])
                                ->setContent(file_get_contents($file['tmp_name']))
                        );
                    }
                }

                $transaction->save();

                $this->_getSession()
                     ->addSuccess($this->__('The ticket has been saved.'));

                $this->_getSession()
                     ->setFormData(false);

                if ($this->getRequest()->getPost('emailcopy') && isset($message)) {
                    $message->sendNewReplyEmail();
                }

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('ticket_id' => $ticket->getId()));

                    return;
                }

                $this->_redirect('*/*/');

                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()
                     ->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()
                     ->addException($e, $this->__('An error occurred while saving the ticket.'));
            }

            $this->_getSession()
                 ->setFormData(new Varien_Object($this->getRequest()->getPost()));

            $this->_redirect('*/*/edit', array('ticket_id' => $this->getRequest()->getParam('ticket_id')));

            return;
        }

        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $ticketIds = $this->getRequest()->getParam('ticket_ids');
        if (!is_array($ticketIds)) {
            $this->_getSession()->addError($this->__('Please select ticket(s).'));
        } else {
            if (!empty($ticketIds)) {
                try {
                    foreach ($ticketIds as $ticketId) {
                        $ticket = Mage::getSingleton('supportsuite/ticket')->load($ticketId);

                        $ticket->delete();
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) have been deleted.', count($ticketIds))
                    );
                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }
        $this->_redirect('*/*/index');
    }

    public function deleteMessageAction()
    {
        $message = Mage::getModel('supportsuite/ticket_message');

        if ($this->getRequest()->getParam('message_id')) {
            $message->load($this->getRequest()->getParam('message_id'));
        }

        if (!$message->getId()) {
            $this->_getSession()->addError($this->__('This message no longer exists.'));

            $this->_redirect('*/*/');

            return;
        }

        try {
            $message->delete();

            $this->_getSession()->addSuccess(
                $this->__('The message has been deleted.')
            );
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/edit', array('ticket_id' => $message->getTicketId()));
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('supportsuite/ticket');
    }
}
