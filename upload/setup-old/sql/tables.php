<?php
/**********************************
* Olate Download 3.3.0
* https://github.com/SnatMTE/Olate-Download/
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 192 $
* @package od
*
* Original Author: Olate Download
* Updated by: Snat
* Last-Edited: 2025-12-16
*/

// Tables
$tables_sql[] = "CREATE TABLE `downloads_agreements` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  `contents` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";

$tables_sql[] = "CREATE TABLE `downloads_categories` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `description` varchar(200) NOT NULL default '',
  `sort` int(5) NOT NULL default '0',
  `keywords` TEXT NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";

$tables_sql[] = "CREATE TABLE `downloads_comments` (
  `id` int(11) NOT NULL auto_increment,
  `file_id` int(11) NOT NULL default '0',
  `timestamp` int(11) NOT NULL default '0',
  `name` varchar(150) NOT NULL default '',
  `email` varchar(150) NOT NULL default '',
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";

$tables_sql[] = "CREATE TABLE `downloads_config` (
  `version` varchar(20) NOT NULL default '',
  `site_name` varchar(200) NOT NULL default '',
  `url` varchar(150) NOT NULL default '',
  `flood_interval` int(3) NOT NULL default '60',
  `admin_email` varchar(150) NOT NULL default '',
  `language` varchar(30) NOT NULL default '',
  `template` varchar(30) NOT NULL default '',
  `date_format` varchar(15) NOT NULL default '',
  `filesize_format` varchar(2) NOT NULL default 'KB',
  `page_amount` int(5) NOT NULL default '10',
  `latest_files` int(5) NOT NULL default '5',
  `enable_topfiles` int(1) NOT NULL default '1',
  `top_files` int(5) NOT NULL default '5',
  `enable_allfiles` int(1) NOT NULL default '1',
  `enable_comments` int(1) NOT NULL default '0',
  `approve_comments` int(1) NOT NULL default '0',
  `enable_search` int(1) NOT NULL default '0',
  `enable_ratings` int(1) NOT NULL default '0',
  `enable_stats` int(1) NOT NULL default '0',
  `enable_rss` int(1) NOT NULL default '0',
  `enable_count` int(1) NOT NULL default '1',
  `enable_useruploads` int(1) NOT NULL default '0',
  `enable_actual_upload` int(1) NOT NULL default '0',
  `enable_mirrors` int(1) NOT NULL default '0',
  `enable_leech_protection` int(1) NOT NULL default '1',
  `mirrors` int(2) NOT NULL default '5',
  `uploads_allowed_ext` TEXT NOT NULL,
  `userupload_always_approve` int(1) NOT NULL default '0',
  `filter_cats` INT(1) DEFAULT '0' NOT NULL,
  `ip_restrict_mode` TINYINT( 1 ) DEFAULT '0' NOT NULL,
  `enable_recommend_friend` tinyint(1) NOT NULL default '1',
  `enable_recommend_confirm` tinyint(1) NOT NULL default '0',
  `acp_check_extensions` tinyint(1) NOT NULL default '0',
  `use_fckeditor` tinyint(1) NOT NULL default '0',
  `allow_user_lang` TINYINT( 1 ) NOT NULL default '0'
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";

$tables_sql[] = "CREATE TABLE `downloads_customfields` (
  `id` int(11) NOT NULL auto_increment,
  `label` varchar(50) NOT NULL default '',
  `value` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;"; 

$tables_sql[] = "CREATE TABLE `downloads_customfields_data` (
  `id` int(10) NOT NULL auto_increment,
  `field_id` int(10) NOT NULL default '0',
  `file_id` int(10) NOT NULL default '0',
  `value` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;"; 

$tables_sql[] = "CREATE TABLE `downloads_files` (
  `id` int(11) NOT NULL auto_increment,
  `category_id` int(11) NOT NULL default '0',
  `name` varchar(60) NOT NULL default '',
  `description_small` text NOT NULL,
  `description_big` text NOT NULL,
  `downloads` int(10) NOT NULL default '0',
  `views` int(10) NOT NULL default '0',
  `size` bigint( 20 ) unsigned not null default '0',
  `date` int(10) NOT NULL default '0',
  `agreement_id` int(11) NOT NULL default '0',
  `rating_votes` int(10) NOT NULL default '0',
  `rating_value` varchar(5) NOT NULL default '0',
  `password` varchar(32) NOT NULL default '',
  `status` INT( 1 ) DEFAULT 1 NOT NULL,
  `convert_newlines` TINYINT( 1 ) DEFAULT 0 NOT NULL,
  `keywords` TEXT NOT NULL,
  `activate_at` INT( 10 ) DEFAULT '0' NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `name` (`name`,`description_small`,`description_big`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";

$tables_sql[] = "CREATE TABLE `downloads_mirrors` (
  `id` int(11) NOT NULL auto_increment,
  `file_id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `location` text NOT NULL,
  `url` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";

$tables_sql[] = "CREATE TABLE `downloads_permissions` (
  `permission_id` int(11) NOT NULL auto_increment,
  `name` varchar(40) NOT NULL default '',
  `setting` int(1) NOT NULL default '0',
  PRIMARY KEY  (`permission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";

$tables_sql[] = "CREATE TABLE `downloads_stats` (
  `id` int(11) NOT NULL auto_increment,
  `file_id` int(11) NOT NULL default '0',
  `timestamp` varchar(10) NOT NULL default '',
  `ip` varchar(20) NOT NULL default '',
  `referrer` text NOT NULL,
  `user_agent` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";

$tables_sql[] = "CREATE TABLE `downloads_usergroups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";

$tables_sql[] = "CREATE TABLE `downloads_userpermissions` (
  `id` int(11) NOT NULL auto_increment,
  `permission_id` int(11) NOT NULL default '0',
  `type` enum('user_group') NOT NULL default 'user_group',
  `type_value` int(10) NOT NULL default '0',
  `setting` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;"; 

$tables_sql[] = "CREATE TABLE `downloads_users` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` int(11) NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `salt` varchar(10) NOT NULL default '',
  `email` varchar(200) NOT NULL default '',
  `firstname` varchar(20) NOT NULL default '',
  `lastname` varchar(20) NOT NULL default '',
  `location` varchar(100) NOT NULL default '',
  `signature` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;"; 

$tables_sql[] = "CREATE TABLE `downloads_ip_restrict` (
  `id` int(11) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `start` varchar(15) NOT NULL default '',
  `end` varchar(15) NOT NULL default '',
  `mask` varchar(15) NOT NULL default '',
  `action` tinyint(1) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;"; 

$tables_sql[] = "CREATE TABLE `downloads_ip_restrict_log` (
  `id` int(11) NOT NULL auto_increment,
  `timestamp` int(20) default '0',
  `ip_address` varchar(15) NOT NULL default '',
  `request_uri` text NOT NULL,
  `referer` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;"; 

$tables_sql[] = "CREATE TABLE `downloads_leech_settings` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`domain` TEXT NOT NULL ,
`action` TINYINT( 1 ) NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM DEFAULT CHARSET=utf8mb4 ;";

$tables_sql[] = "CREATE TABLE `downloads_recommend_log` (
	`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
	`timestamp` INT( 20 ) NOT NULL ,
	`ip_address` VARCHAR( 15 ) NOT NULL ,
	`file_id` INT( 11 ) NOT NULL ,
	`sender_name` VARCHAR( 100 ) NOT NULL ,
	`sender_email` VARCHAR( 100 ) NOT NULL ,
	`rcpt_name` VARCHAR( 100 ) NOT NULL ,
	`rcpt_email` VARCHAR( 100 ) NOT NULL ,
	`message` TEXT NOT NULL ,
	`confirm_hash` VARCHAR( 32 ) NOT NULL ,
	`confirmed` TINYINT( 1 ) DEFAULT '0' NOT NULL ,
	PRIMARY KEY ( `id` )
) ENGINE = MYISAM DEFAULT CHARSET=utf8mb4 ;";

$tables_sql[] = "CREATE TABLE `downloads_recommend_blocklist` (
  `id` int(11) NOT NULL auto_increment,
  `address` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`,`address`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 ;";

$tables_sql[] = 'CREATE TABLE `downloads_languages` (
	`id` int(11) NOT NULL auto_increment,
	`name` text NOT NULL,
	`site_default` tinyint(1) NOT NULL default \'0\',
	`filename` text NOT NULL,
	`version_major` tinyint(4) NOT NULL default \'0\',
	`version_minor` tinyint(4) NOT NULL default \'0\',
	PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;';

?>