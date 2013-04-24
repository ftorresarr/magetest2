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

class MageMods_SupportSuite_Adminhtml_TemplateController
    extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('supportsuite/template');

        $this->_title($this->__('SupportSuite'))
             ->_title($this->__('Manage Templates'));

        return $this;
    }

    public function loadAction()
    {
        $template = Mage::getModel('supportsuite/template');

        if ($this->getRequest()->getParam('template_id')) {
            $template->load($this->getRequest()->getParam('template_id'));
        }

        $this->getResponse()->setBody($template->getContent());
    }

    public function indexAction()
    {
        $this->_initAction()
             ->_addContent($this->getLayout()->createBlock('supportsuite/adminhtml_template'))
             ->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $template = Mage::getModel('supportsuite/template');

        Mage::register('current_template', $template);

        if ($this->getRequest()->getParam('template_id')) {
            $template->load($this->getRequest()->getParam('template_id'));

            if (!$template->getId()) {
                $this->_getSession()
                     ->addError($this->__('This template no longer exists.'));

                $this->_redirect('*/*/');

                return;
            }
        }

        $this->_title($template->getId() ? $template->getTitle() : $this->__('New template'));

        $data = $this->_getSession()->getFormData(true);

        if (!empty($data)) {
            $template->setTitle($data->getTitle())
                     ->setContent($data->getContent());
        }

        $this->_initAction()
             ->_addContent($this->getLayout()->createBlock('supportsuite/adminhtml_template_edit'))
             ->renderLayout();
    }

    public function saveAction()
    {
        if ($this->getRequest()->isPost()) {
            $template = Mage::getModel('supportsuite/template');

            if ($this->getRequest()->getParam('template_id')) {
                $template->load($this->getRequest()->getParam('template_id'));
            }

            try {
                if (!Zend_Validate::is(trim($this->getRequest()->getPost('title')), 'NotEmpty')) {
                    throw new Mage_Core_Exception($this->__('The title cannot be empty.'));
                }

                if (!Zend_Validate::is(trim($this->getRequest()->getPost('content')), 'NotEmpty')) {
                    throw new Mage_Core_Exception($this->__('The content cannot be empty.'));
                }

                $template->setTitle($this->getRequest()->getParam('title'))
                         ->setContent($this->getRequest()->getParam('content'))
                         ->save();

                $this->_getSession()
                     ->addSuccess($this->__('The template has been saved.'));

                $this->_getSession()
                     ->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('template_id' => $template->getId()));

                    return;
                }

                $this->_redirect('*/*/');

                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()
                     ->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()
                     ->addException($e, $this->__('An error occurred while saving the template.'));
            }

            $this->_getSession()
                 ->setFormData(new Varien_Object($this->getRequest()->getPost()));

            $this->_redirect('*/*/edit', array('template_id' => $this->getRequest()->getParam('template_id')));

            return;
        }

        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('template_id')) {
            $template = Mage::getModel('supportsuite/template')
                ->load($this->getRequest()->getParam('template_id'));

            if ($template->getId()) {
                try {
                    $template->delete();

                    $this->_getSession()
                         ->addSuccess($this->__('The template has been deleted.'));

                    $this->_redirect('*/*/');

                    return;
                } catch (Mage_Core_Exception $e) {
                    $this->_getSession()
                         ->addError($e->getMessage());
                } catch (Exception $e) {
                    $this->_getSession()
                         ->addException($e, $this->__('An error occurred while deleting the template.'));
                }
            }
        }

        $this->_getSession()
             ->addError($this->__('Unable to find a template to delete.'));

        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        if (is_array($this->getRequest()->getParam('template_ids'))) {
            try {
                $template = Mage::getModel('supportsuite/template');
                $count    = 0;

                foreach ($this->getRequest()->getParam('template_ids') as $templateId) {
                    $template->load($templateId);

                    if ($template->getId()) {
                        $template->delete();

                        $count++;
                    }
                }

                $this->_getSession()
                     ->addSuccess($this->__('Total of %d record(s) were deleted.', $count));
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()
                     ->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()
                     ->addException($e, $this->__('An error occurred while deleting the template.'));
            }
        }

        $this->_redirect('*/*/');
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('supportsuite/template');
    }
}
