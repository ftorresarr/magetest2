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

$installer = $this;

$installer->startSetup();

$installer->run("

CREATE TABLE `{$installer->getTable('supportsuite/ticket')}` (
    `ticket_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `token` char(32) default NULL,
    `store_id` smallint(5) unsigned default NULL,
    `increment_id` varchar(50) default NULL,
    `order_id` int(10) unsigned default NULL,
    `order_increment_id` varchar(50) default NULL,
    `open` tinyint(1) unsigned default NULL,
    `customer_id` int(10) unsigned default NULL,
    `name` varchar(255) default NULL,
    `email` varchar(255) default NULL,
    `created_at` datetime default NULL,
    `query_at` datetime default NULL,
    `reply_at` datetime default NULL,
    `subject` varchar(255) default NULL,
    `priority` int(10) unsigned default NULL,
    `vendorid` int(10) unsigned default NULL,
    PRIMARY KEY (`ticket_id`),
    KEY `created_at` (`created_at`),
    KEY `query_at` (`query_at`),
    KEY `reply_at` (`reply_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('supportsuite/email')}` (
    `email_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `unique_id` varchar(255) default NULL,
    PRIMARY KEY (`email_id`),
    UNIQUE KEY `unique_id` (`unique_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('supportsuite/ticket_message')}` (
    `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `ticket_id` int(10) unsigned default NULL,
    `message` text default NULL,
    `created_at` datetime default NULL,
    PRIMARY KEY (`message_id`),
    KEY `ticket_id` (`ticket_id`),
    KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('supportsuite/ticket_note')}` (
    `note_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `ticket_id` int(10) unsigned default NULL,
    `note` text default NULL,
    `created_at` datetime default NULL,
    PRIMARY KEY (`note_id`),
    KEY `ticket_id` (`ticket_id`),
    KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('supportsuite/ticket_attachment')}` (
    `attachment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `message_id` int(10) unsigned default NULL,
    `type` varchar(255) default NULL,
    `size` int(10) unsigned default NULL,
    `name` varchar(255) default NULL,
    PRIMARY KEY (`attachment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `{$installer->getTable('supportsuite/template')}` (
    `template_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) default NULL,
    `content` TEXT default NULL,
    PRIMARY KEY (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->addEntityType('ticket', array(
    'entity_model'        => 'supportsuite/ticket',
    'table'               => 'supportsuite/ticket',
    'increment_model'     => 'eav/entity_increment_numeric',
    'increment_per_store' => true
));

$installer->endSetup();
