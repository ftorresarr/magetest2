<?php 

    $installer = $this;
    
    $installer->startSetup();

    $installer->addAttribute('customer','cpname', array(
                        'label'              => 'Vendor Name',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 80,
    ));
   $installer->addAttribute('customer','cpphone', array(
                        'label'              => 'Vendor Phone',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 81,
   ));
   $installer->addAttribute('customer','cpemail', array(
                        'label'              => 'Vendor Email',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 82,
    ));
   $installer->addAttribute('customer','cplogo', array(
                        'label'              => 'Vendor Logo',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 83,
    ));
   $installer->addAttribute('customer','cppicture', array(
                        'label'              => 'Vendor Picture',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 84,
    ));
   
   $installer->addAttribute('customer','cpaddress', array(
                        'label'              => 'Vendor Address',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 85,
    ));
   $installer->addAttribute('customer','cpcity', array(
                        'label'              => 'Vendor City',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 86,
    ));
   $installer->addAttribute('customer','cpcountry', array(
                        'label'              => 'Vendor Country',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 87,
    ));
   $installer->addAttribute('customer','cpzip', array(
                        'label'              => 'Vendor Zipcode',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 88,
    ));
	   $installer->addAttribute('catalog_product','creator', array(
                        'label'              => 'Product Creator',
                        'visible'            => 1,
                        'required'           => 0,
                        'position'           => 1,
                        'sort_order'         => 88,
                        "input" => "text",
        				"global" => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,						
    ));
    $installer->endSetup();
