<?php
class Yougento_S2b_Block_Rewrite_Productgridcontainer extends  Mage_Adminhtml_Block_Widget_Container
{
	public function detectMaxProd(){
		        	$roleId = implode('', Mage::getSingleton('admin/session')->getUser()->getRoles());
        	$roleName = Mage::getModel('admin/roles')->load($roleId)->getRoleName();
        	$type = Mage::app()->getRequest()->getParam('type');
        	if($roleName != 'Administrators')
        	{
        		$creator= mage::helper('s2b')->getVendorCid();
        		$collection = Mage::getModel('catalog/product')->getCollection();
        		$collection->addAttributeToSelect('creator')->addAttributeToSelect('name');
        	
        		//filter for products who name is equal (eq) to Widget A, or equal (eq) to Widget B
        		$collection->addFieldToFilter(array(
        				array('attribute'=>'creator','eq'=>$creator),
        		));
        		$i=0;
        		$collection->addFieldToFilter(array(
        				array('attribute'=>'name','neq'=>NULL),
        		));
        	
        		foreach ($collection as $product) {
        			$i++;
        		}
        	
        		$roleId = implode('', Mage::getSingleton('admin/session')->getUser()->getRoles());
        		$customConfig = Mage::getModel('s2b/configs')->load($roleId,'vroleid');
        		$limit = $customConfig->getMaxprod();
        		if($limit){
        			$limit = $customConfig->getMaxprod();
        		}else{
        	
        			$limit = NULL;
        		}
        		if ((int)$limit > 0) {
        			$compare=  Mage::app()->getRequest()->getParams('id');
					
						if ($limit <= $i && $compare!=NULL) {
        				//add the error message
        				Mage::getSingleton('adminhtml/session')->addError('Max products reached');
						
						return 1;
					}
				}
        	}
			return 0;
	}
    /**
     * Set template
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('catalog/product.phtml');
    }

    /**
     * Prepare button and grid
     *
     * @return Mage_Adminhtml_Block_Catalog_Product
     */
    protected function _prepareLayout()
    {
    	if($this->detectMaxProd()==0){
        $this->_addButton('add_new', array(
            'label'   => Mage::helper('catalog')->__('Add Product'),
            'onclick' => "setLocation('{$this->getUrl('*/*/new')}')",
            'class'   => 'add'
        ));
		}
        $this->setChild('grid', $this->getLayout()->createBlock('adminhtml/catalog_product_grid', 'product.grid'));
        return parent::_prepareLayout();
    }

    /**
     * Deprecated since 1.3.2
     *
     * @return string
     */
    public function getAddNewButtonHtml()
    {
        return $this->getChildHtml('add_new_button');
    }

    /**
     * Render grid
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

    /**
     * Check whether it is single store mode
     *
     * @return bool
     */
    public function isSingleStoreMode()
    {
        if (!Mage::app()->isSingleStoreMode()) {
               return false;
        }
        return true;
    }
}

?>