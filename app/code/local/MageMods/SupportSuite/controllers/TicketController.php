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

class MageMods_SupportSuite_TicketController
    extends Mage_Core_Controller_Front_Action
{
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    public function preDispatch()
    {
        parent::preDispatch();

        $action = $this->getRequest()->getActionName();

        if ($action == 'list') {
            $loginUrl = Mage::helper('customer')->getLoginUrl();

            if (!$this->_getSession()->authenticate($this, $loginUrl)) {
                $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            }
        }
    }

    public function indexAction()
    {
        $this->_redirect('*/*/form', array('_secure' => true));

        return;
    }

    public function listAction()
    {
        $this->loadLayout();

        if ($block = $this->getLayout()->getBlock('supportsuite_ticket_list')) {
            $block->setRefererUrl($this->_getRefererUrl());
        }

        $this->renderLayout();
    }

    public function viewAction()
    {
        $this->loadLayout($this->_getSession()->isLoggedIn() ? array('default', 'customer_account') : null);

        $ticket = Mage::getModel('supportsuite/ticket')
            ->load($this->getRequest()->getParam('token'), 'token');

        if (!$ticket->getId()) {
            $this->_forward('noRoute');

            return false;
        }

        Mage::register('current_ticket', $ticket);

        if ($this->_getSession()->isLoggedIn()) {
            $this->loadLayout(array('default', 'customer_account'));

            $this->getLayout()->getBlock('my.account.wrapper')->append(
                $this->getLayout()->createBlock('supportsuite/ticket_view')
            );
        } else {
            $this->loadLayout();

            $this->getLayout()->getBlock('content')->append(
                $this->getLayout()->createBlock('supportsuite/ticket_view')
            );
        }

        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');

        if ($navigationBlock = $this->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('supportsuite/ticket/list');
        }

        $data = $this->_getSession()->getReplyFormData(true);

        if ($block = $this->getLayout()->getBlock('supportsuite_ticket_view')) {
            $block->setRefererUrl($this->_getRefererUrl())
                  ->setMessage($data->getMessage());
        }

        $this->renderLayout();
    }

    public function formAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->loadLayout(array('default', 'customer_account'));

            $this->getLayout()->getBlock('my.account.wrapper')->append(
                $block = $this->getLayout()->createBlock('supportsuite/ticket_form')
            );
        } else {
            $this->loadLayout();

            $this->getLayout()->getBlock('content')->append(
                $block = $this->getLayout()->createBlock('supportsuite/ticket_form')
            );
        }

        $data = $this->_getSession()->getTicketFormData(true);

        if ($data) {
            $block->setSubject($data->getSubject())
                  ->setName($data->getName())
                  ->setRealemail($data->getRealemail())
                  ->setMessage($data->getMessage())
                  ->setOrderIncrementId($data->getOrderIncrementId());
        } else if ($this->_getSession()->isLoggedIn()) {
            $block->setName($this->_getSession()->getCustomer()->getName())
                  ->setRealemail($this->_getSession()->getCustomer()->getEmail());

            if ($orderId = $this->getRequest()->getParam('order_id')) {
                $order = Mage::getModel('sales/order')->load($orderId);

                if ($order->getId()) {
                    $block->setOrderIncrementId($order->getIncrementId());
                }
            }
        }

        $block->setRefererUrl($this->_getRefererUrl());

        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');

        $this->renderLayout();
    }

    public function formPostAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/form');
        }

        if ($this->getRequest()->isPost()) {
            try{
            	$CreatorId = $this->getRequest()->getPost('creatorid');
            	$ticketCount = Mage::getModel('supportsuite/ticket')->getCollection()->addFieldToFilter('vendorid',array('eq' => $CreatorId))->count();
            	$loadusr = Mage::getModel("customer/customer")->load($CreatorId)->getEmail();
            	$adminID = Mage::getModel("admin/user")->load($loadusr,'username')->getData();
            	$adminID= $adminID['user_id'];
            	$roleId = Mage::getModel('admin/roles')->load($adminID,'user_id')->getData();
            	$roleId=$roleId['parent_id'];
            	$customConfig = Mage::getModel('s2b/configs')->load($roleId,'vroleid')->getMaxmess();
            	$customConfig=intval($customConfig);
            	if($customConfig>0){
            		if($customConfig>=$ticketCount){
            			throw new Mage_Core_Exception($this->__('Vendor has reached maximum number of messages'));
            		}
            	}
                $ticket = Mage::getModel('supportsuite/ticket');

                if (!Zend_Validate::is($this->getRequest()->getPost('realemail'), 'EmailAddress')) {
                    throw new Mage_Core_Exception($this->__('Invalid email address.'));
                }

                if (!Zend_Validate::is(trim($this->getRequest()->getPost('name')), 'NotEmpty')) {
                    throw new Mage_Core_Exception($this->__('The name cannot be empty.'));
                }

                if (!Zend_Validate::is(trim($this->getRequest()->getPost('subject')), 'NotEmpty')) {
                    throw new Mage_Core_Exception($this->__('The subject cannot be empty.'));
                }

                if (!Zend_Validate::is(trim($this->getRequest()->getPost('message')), 'NotEmpty')) {
                    throw new Mage_Core_Exception($this->__('The message cannot be empty.'));
                }

                if ($this->getRequest()->getPost('url') != "http://" || $this->getRequest()->getPost('email') != "") {
                    throw new Mage_Core_Exception($this->__('The message cannot be empty.'));
                }

                if (!$this->_getSession()->isLoggedIn()) {
                    $customer = Mage::getModel('customer/customer')
                        ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                        ->loadByEmail($this->getRequest()->getPost('realemail'));
                } else {
                    $customer = $this->_getSession()->getCustomer();
                }

                if ($orderIncrementId = $this->getRequest()->getPost('order_increment_id')) {
                    $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);

                    if (!$order->getId()) {
                        throw new Mage_Core_Exception($this->__('This order no longer exists.'));
                    }

                    if ($this->_getSession()->isLoggedIn()) {
                        if ($order->getCustomerId() != $this->_getSession()->getCustomerId()) {
                            throw new Mage_Core_Exception($this->__('This order is assigned to another customer.'));
                        }
                    } else {
                        if ($order->getCustomerEmail() != $this->getRequest()->getPost('realemail')) {
                            throw new Mage_Core_Exception($this->__('This order is assigned to another email.'));
                        }
                    }

                    $ticket->setOrderId($order->getId())
                           ->setOrderIncrementId($order->getIncrementId());
                } elseif ($this->getRequest()->getPost('product_id')) {
                    $product = Mage::getModel('catalog/product')->load($this->getRequest()->getPost('product_id'));

                    if (!$product->getId()) {
                        $this->_forward('noRoute');
                    }
                }

                $files = array();
				if(isset($_FILES['attachment'])){
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
				}
                $transaction = Mage::getModel('core/resource_transaction');
                
                $transaction->addObject(
                    $ticket->setStoreId(Mage::app()->getStore()->getId())
                        ->setOpen(1)
                        ->setQueryAt(Mage::getSingleton('core/date')->gmtDate())
                        ->setPriority(Mage::helper('supportsuite')->getTicketDefaultPriority(Mage::app()->getStore()))
                        ->setCustomerId($customer->getId())
                        ->setName($this->getRequest()->getPost('name'))
                        ->setEmail($this->getRequest()->getPost('realemail'))
                        ->setSubject($this->getRequest()->getParam('subject'))
                        ->setVendorid($this->getRequest()->getPost('creatorid'))        //set vendorID on the database table
                );

                $transaction->addObject(
                    $message = Mage::getModel('supportsuite/ticket_message')
                        ->setTicket($ticket)
                        ->setMessage($this->getRequest()->getParam('message'))
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

                $transaction->save();

                $ticket->sendNewTicketEmail();

//                if ($this->getRequest()->getPost('product_id')) {
//                    Mage::getSingleton('catalog/session')
//                        ->addSuccess($this->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.'));

//                    $this->_redirect('catalog/product/view', array('id' => $this->getRequest()->getPost('product_id')));
//                } else {
                    $this->_getSession()
                         ->addSuccess($this->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.'));

                    $this->_redirect('*/*/view', array('token' => $ticket->getToken(), '_secure' => true));
//                }

                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()
                     ->setTicketFormData(new Varien_Object($this->getRequest()->getPost()))
                     ->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()
                     ->setTicketFormData(new Varien_Object($this->getRequest()->getPost()))
                     ->addException($e, $this->__('Unable to submit your request. Please, try again later.'));
            }
        
    }
        $this->_redirect('*/*/form');
    }

    public function replyPostAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/form');
        }

        if ($this->getRequest()->isPost()) {
            try {
                $ticket = Mage::getModel('supportsuite/ticket')
                    ->load($this->getRequest()->getParam('token'), 'token');

                if (!$ticket->getId()) {
                    $this->_forward('noRoute');

                    return false;
                }

                if (!$ticket->getOpen()) {
                    throw new Mage_Core_Exception($this->__('This ticket is closed.'));
                }

                if (!Zend_Validate::is(trim($this->getRequest()->getPost('message')), 'NotEmpty')) {
                    throw new Mage_Core_Exception($this->__('The message cannot be empty.'));
                }

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

                $transaction = Mage::getModel('core/resource_transaction');

                $transaction->addObject(
                    $ticket->setQueryAt(Mage::getSingleton('core/date')->gmtDate())
                );

                $transaction->addObject(
                    $message = Mage::getModel('supportsuite/ticket_message')
                        ->setTicket($ticket)
                        ->setMessage($this->getRequest()->getParam('message'))
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

                $transaction->save();

                $this->_getSession()
                     ->addSuccess($this->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.'));

                $this->_redirect('*/*/view', array('token' => $ticket->getToken(), '_secure' => true));

                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()
                     ->setReplyFormData(new Varien_Object($this->getRequest()->getPost()))
                     ->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()
                     ->setReplyFormData(new Varien_Object($this->getRequest()->getPost()))
                     ->addException($e, $this->__('Unable to submit your request. Please, try again later.'));
            }
        }

        $this->_redirect('*/*/view', array('token' => $this->getRequest()->getParam('token'), '_secure' => true));
    }
}
