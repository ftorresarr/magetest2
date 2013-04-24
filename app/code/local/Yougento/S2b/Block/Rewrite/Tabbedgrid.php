<?php
class Yougento_S2b_Block_Rewrite_Tabbedgrid extends Mage_Adminhtml_Block_Widget_Grid
{	
    public function __construct()
    {
        parent::__construct();
        $this->setId('productGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        $this->setVarNameFilter('product_filter');

    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
    
    protected function _prepareCollection()
    {
    	$roleId = implode('', Mage::getSingleton('admin/session')->getUser()->getRoles());
		$roleName = Mage::getModel('admin/roles')->load($roleId)->getRoleName();
		
		if($roleName == 'Administrators' && $this->getRequest()->getParam('id')==NULL)
		{
			$store = $this->_getStore();
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('attribute_set_id')
            ->addAttributeToSelect('type_id');

        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $collection->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left');
        }
        if ($store->getId()) {
            //$collection->setStoreId($store->getId());
            $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
            $collection->addStoreFilter($store);
            $collection->joinAttribute('name', 'catalog_product/name', 'entity_id', null, 'inner', $adminStore);
            $collection->joinAttribute('custom_name', 'catalog_product/name', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left', $store->getId());
        }
        else {
            $collection->addAttributeToSelect('price');
            $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
        }

        $this->setCollection($collection);

        parent::_prepareCollection();
        $this->getCollection()->addWebsiteNamesToResult();
        return $this;
		}else
		{	
			$username = Mage::getSingleton('admin/session')->getUser()->getUsername();
			$resource = Mage::getSingleton('core/resource');
			$readConnection = $resource->getConnection('core_read');
			$query = "SELECT entity_id FROM customer_entity WHERE email='{$username}'";
			$results = $readConnection->fetchAll($query);
						if($results==NULL){
				$cid=$this->getRequest()->getParam('id');
			}else{
			$cid = $results[0]['entity_id'];
			}
			$collection = Mage::getModel('catalog/product')->getCollection()
			->addAttributeToSelect('sku')
			->addAttributeToSelect('name')
			->addAttributeToSelect('attribute_set_id')			
			->addAttributeToSelect('type_id')
			->addAttributeToFilter('creator',$cid)
			->addAttributeToFilter(array(
			array('attribute'=> 'status','eq' => 1),
			array('attribute'=> 'status','eq' => 2),
			array('attribute'=> 'status','eq' => 3),
			));
			if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
				$collection->joinField('qty',
						'cataloginventory/stock_item',
						'qty',
						'product_id=entity_id',
						'{{table}}.stock_id=1',
						'left');
			}
			$store = $this->_getStore();
			if ($store->getId()) {
				//$collection->setStoreId($store->getId());
				$adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
				$collection->addStoreFilter($store);
				$collection->joinAttribute('name', 'catalog_product/name', 'entity_id', null, 'inner', $adminStore);
				$collection->joinAttribute('custom_name', 'catalog_product/name', 'entity_id', null, 'inner', $store->getId());
				$collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner', $store->getId());
				$collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner', $store->getId());
				$collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left', $store->getId());
			}
			else {
				$collection->addAttributeToSelect('price');
				$collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner');
				$collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner');
			}
			
			$this->setCollection($collection);
			
			$this->getCollection()->addWebsiteNamesToResult();
			return $this;
		}
    	
    }
    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            if ($column->getId() == 'websites') {
                $this->getCollection()->joinField('websites',
                    'catalog/product_website',
                    'website_id',
                    'product_id=entity_id',
                    null,
                    'left');
            }
        }
        return parent::_addColumnFilterToCollection($column);
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id',
            array(
                'header'=> Mage::helper('catalog')->__('ID'),
                'width' => '50px',
                'type'  => 'number',
                'index' => 'entity_id',
        ));
        $this->addColumn('name',
            array(
                'header'=> Mage::helper('catalog')->__('Name'),
                'index' => 'name',
        ));

        $store = $this->_getStore();
        if ($store->getId()) {
            $this->addColumn('custom_name',
                array(
                    'header'=> Mage::helper('catalog')->__('Name in %s', $store->getName()),
                    'index' => 'custom_name',
            ));
        }

        $this->addColumn('type',
            array(
                'header'=> Mage::helper('catalog')->__('Type'),
                'width' => '60px',
                'index' => 'type_id',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
        ));

        $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
            ->load()
            ->toOptionHash();

        $this->addColumn('set_name',
            array(
                'header'=> Mage::helper('catalog')->__('Attrib. Set Name'),
                'width' => '100px',
                'index' => 'attribute_set_id',
                'type'  => 'options',
                'options' => $sets,
        ));

        $this->addColumn('sku',
            array(
                'header'=> Mage::helper('catalog')->__('SKU'),
                'width' => '80px',
                'index' => 'sku',
        ));

        $store = $this->_getStore();
        $this->addColumn('price',
            array(
                'header'=> Mage::helper('catalog')->__('Price'),
                'type'  => 'price',
                'currency_code' => $store->getBaseCurrency()->getCode(),
                'index' => 'price',
        ));

        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            $this->addColumn('qty',
                array(
                    'header'=> Mage::helper('catalog')->__('Qty'),
                    'width' => '100px',
                    'type'  => 'number',
                    'index' => 'qty',
            ));
        }

        $this->addColumn('visibility',
            array(
                'header'=> Mage::helper('catalog')->__('Visibility'),
                'width' => '70px',
                'index' => 'visibility',
                'type'  => 'options',
                'options' => Mage::getModel('catalog/product_visibility')->getOptionArray(),
        ));

    	$this->addColumn('status',
            array(
            'header'=> Mage::helper('catalog')->__('Status'),
            'width' => '70px',
            'index' => 'status',
            'type'  => 'options',
            'options'	=> array(
            	1    => Mage::helper('catalog')->__('Enabled'),
            	2   => Mage::helper('catalog')->__('Disabled'),
            	3   => Mage::helper('catalog')->__('Awaiting Approval')
        	)
    	));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('websites',
                array(
                    'header'=> Mage::helper('catalog')->__('Websites'),
                    'width' => '100px',
                    'sortable'  => false,
                    'index'     => 'websites',
                    'type'      => 'options',
                    'options'   => Mage::getModel('core/website')->getCollection()->toOptionHash(),
            ));
        }

        $this->addColumn('action',
            array(
                'header'    => Mage::helper('catalog')->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('catalog')->__('Edit'),
                        'url'     => array(
                            'base'=>'*/*/edit',
                            'params'=>array('store'=>$this->getRequest()->getParam('store'))
                        ),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
        ));


        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
     
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    public function getRowUrl($row)
    {
    	return $url=Mage::helper('adminhtml')->getUrl().'admin/catalog_product/edit/id/'.$row->getId();
      /*  return $url., array(
            'store'=>$this->getRequest()->getParam('store'),
            'id'=>$row->getId())
        );*/
    }
}
