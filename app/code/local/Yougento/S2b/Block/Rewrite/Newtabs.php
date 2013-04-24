<?php

class Yougento_S2b_Block_Rewrite_Newtabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('customer_info_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('customer')->__('Customer Information'));
    }

    protected function _beforeToHtml()
    {
    		$this->addTab('account', array(
            'label'     => Mage::helper('customer')->__('Account Information'),
            'content'   => $this->getLayout()->createBlock('adminhtml/customer_edit_tab_account')->initForm()->toHtml(),
            'active'    => Mage::registry('current_customer')->getId() ? false : true
        ));

        $this->addTab('addresses', array(
            'label'     => Mage::helper('customer')->__('Addresses'),
            'content'   => $this->getLayout()->createBlock('adminhtml/customer_edit_tab_addresses')->initForm()->toHtml(),
        ));


        // load: Orders, Shopping Cart, Wishlist, Product Reviews, Product Tags - with ajax

        if (Mage::registry('current_customer')->getId()) {

            if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
                $this->addTab('orders', array(
                    'label'     => Mage::helper('customer')->__('Orders'),
                    'class'     => 'ajax',
                    'url'       => $this->getUrl('*/*/orders', array('_current' => true)),
                 ));
            }

            $this->addTab('cart', array(
                'label'     => Mage::helper('customer')->__('Shopping Cart'),
                'class'     => 'ajax',
                'url'       => $this->getUrl('*/*/carts', array('_current' => true)),
            ));

            $this->addTab('wishlist', array(
                'label'     => Mage::helper('customer')->__('Wishlist'),
                'class'     => 'ajax',
                'url'       => $this->getUrl('*/*/wishlist', array('_current' => true)),
            ));

            if (Mage::getSingleton('admin/session')->isAllowed('newsletter/subscriber')) {
                $this->addTab('newsletter', array(
                    'label'     => Mage::helper('customer')->__('Newsletter'),
                    'content'   => $this->getLayout()->createBlock('adminhtml/customer_edit_tab_newsletter')->initForm()->toHtml()
                ));
            }

            if (Mage::getSingleton('admin/session')->isAllowed('catalog/reviews_ratings')) {
                $this->addTab('reviews', array(
                    'label'     => Mage::helper('customer')->__('Product Reviews'),
                    'class'     => 'ajax',
                    'url'       => $this->getUrl('*/*/productReviews', array('_current' => true)),
                ));
            }

            if (Mage::getSingleton('admin/session')->isAllowed('catalog/tag')) {
                $this->addTab('tags', array(
                    'label'     => Mage::helper('customer')->__('Product Tags'),
                    'class'     => 'ajax',
                    'url'       => $this->getUrl('*/*/productTags', array('_current' => true)),
                ));
            }
        }
			$usrid = $this->getRequest()->getParam('id');
			$loadusr = Mage::getModel("customer/customer")->load($usrid)->getEmail();
			$checkadmn = Mage::getModel("admin/user")->load($loadusr,'username');
			if($checkadmn->getUsername()==$loadusr)
			{

            $this->addTab('vendorproducts', array(
            		'label'     => Mage::helper('customer')->__('Vendor Products'),
            		'class'		=>'ajax',
            		'url'	=> $this->getUrl('s2b/adminhtml_tabs/products', array('_current' => true))
            ));

            $this->addTab('vendormessages', array(
            		'label'     => Mage::helper('customer')->__('Vendor Received Messages'),
            		'content'       => $this->getLayout()->createBlock('s2b/adminhtml_ticket_grid')->toHtml(),
            ));
    /*        $this->addTab('vendorsettings', array(
            		'label'     => Mage::helper('customer')->__('Vendor Settings'),
            		'class'     => 'ajax',
            		'url'       => $this->getUrl('s2b/adminhtml_tabs/settings', array('_current' => true)),
            ));*/
			}
		$this->setAfterElementHtml('<script>
            function modifyTargetElement(checkboxElem){
                if(checkboxElem.checked){
                    $("target_element").disabled=true;
                }
                else{
                    $("target_element").disabled=false;
                }');
        $this->_updateActiveTab();
        Varien_Profiler::stop('customer/tabs');
		parent::_beforeToHtml();
    }

    protected function _updateActiveTab()
    {
        $tabId = $this->getRequest()->getParam('tab');
        if( $tabId ) {
            $tabId = preg_replace("#{$this->getId()}_#", '', $tabId);
            if($tabId) {
                $this->setActiveTab($tabId);
            }
        }
    }
}
	

