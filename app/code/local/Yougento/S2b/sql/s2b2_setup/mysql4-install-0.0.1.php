<?php 

$installer = $this;

    $installer->startSetup();
    $installer->run("
ALTER TABLE `customer_group` ADD `group_role` int(10);
    DROP TABLE IF EXISTS `config_customvendor`;
    CREATE TABLE `config_customvendor` (
`configid` int( 11 ) NOT NULL AUTO_INCREMENT ,
`vroleid` int( 11 ) NOT NULL ,
`autovendor` int( 11 ) NOT NULL ,
`autoprod` int( 11 ) NOT NULL ,
`commission` int( 11 ) NOT NULL ,
`producttypes` text NOT NULL ,
`hidetabs` text NOT NULL ,
`maxmess` int( 11 ) NOT NULL ,
`maxprod` int( 11 ) NOT NULL ,
`hideattr` text NOT NULL ,
`disphone` int( 11 ) NOT NULL ,
`dismail` int( 11 ) NOT NULL ,
PRIMARY KEY ( `configid` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
    			DELETE FROM `admin_role` WHERE role_id=3;
				INSERT INTO `admin_role` VALUES (3,0,1,0,'G',0,'VendorDefault');
				INSERT INTO `admin_rule` (role_id,resource_id,assert_id,role_type,permission)
					VALUES (3,'admin/catalog',0,'G','allow');
				INSERT INTO `admin_rule` (role_id,resource_id,assert_id,role_type,permission)
					VALUES (3,'admin/catalog/products',0,'G','allow');
				INSERT INTO `admin_rule` (role_id,resource_id,assert_id,role_type,permission)
					VALUES (3,'admin/s2b_menu',0,'G','allow');
				INSERT INTO `admin_rule` (role_id,resource_id,assert_id,role_type,permission)
					VALUES (3,'admin/s2b_menu/first_page',0,'G','allow');
				INSERT INTO `admin_rule` (role_id,resource_id,assert_id,role_type,permission)
					VALUES (3,'admin/supportsuite',0,'G','allow');
				INSERT INTO `admin_rule` (role_id,resource_id,assert_id,role_type,permission)
					VALUES (3,'admin/supportsuite/ticket',0,'G','allow');

		");

    $installer->endSetup();

