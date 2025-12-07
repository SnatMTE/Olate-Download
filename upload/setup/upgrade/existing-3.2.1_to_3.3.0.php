<?php
/**********************************
* Olate Download 3.3.0
* http://www.olate.co.uk/od3
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 125 $
* @package od
*
* Updated: $Date: 2005-10-15 22:16:22 +0100 (Sat, 15 Oct 2005) $
*/

// Initialisation
require('../../includes/config.php');

// Core modules
require('../../modules/core/dbim.php');
require('../../modules/core/ehm.php');
require('../../modules/core/lm.php');

// Define any constants
// Error types
define('FATAL', E_USER_ERROR);
define('ERROR', E_USER_WARNING);
define('WARNING', E_USER_NOTICE);

// Initialise modules (order is important)

// EHM
$ehm = new ehm(1); // Debug level 1 recommended for live environments

// DBIM
$dbim = new dbim();
$dbim->connect($config['database']['username'], $config['database']['password'], $config['database']['server'], $config['database']['name'], $config['database']['persistant']);

// Get the site config
$config_result = $dbim->query('SELECT * 
								FROM '.DB_PREFIX.'config 
								LIMIT 1');
$site_config = $dbim->fetch_array($config_result);

// LM
$lm = new lm();

// Alter database version
$dbim->query('UPDATE '.DB_PREFIX.'config
				SET version = "3.3.0"');

// Add new permissions
$dbim->query('INSERT INTO '.DB_PREFIX.'permissions
				(name, setting)
				VALUES ("acp_files_approve_files", 0);');

// Add new fields in downloads_config
$dbim->query('ALTER TABLE '.DB_PREFIX.'config 
				ADD enable_actual_upload INT(1) DEFAULT \'0\' NOT NULL,
				ADD enable_mirrors INT(1) DEFAULT \'0\' NOT NULL,
				ADD userupload_always_approve INT(1) DEFAULT \'0\' NOT NULL,
				ADD filter_cats INT(1) DEFAULT \'0\' NOT NULL,
				ADD ip_restrict_mode TINYINT(1) DEFAULT \'0\' NOT NULL,
				ADD enable_recommend_friend TINYINT(1) DEFAULT \'1\' NOT NULL,
				ADD enable_recommend_confirm tinyint(1) DEFAULT \'0\' NOT NULL,
				ADD acp_check_extensions TINYINT( 1 ) DEFAULT \'0\' NOT NULL,
				ADD use_fckeditor TINYINT( 1 ) DEFAULT \'0\' NOT NULL ;');

// Prepare multiples for each unit
if ($site_config['filesize_format'] == 'GB')
{
	$multiple = pow(1024,3);
}
elseif ($site_config['filesize_format'] == 'MB')
{
	$multiple = pow(1024,2);
}
elseif ($site_config['filesize_format'] == 'KB')
{
	$multiple = 1024;
}
else
{
	$multiple = 1;
}

// Get file sizes and IDs
$sql = 'SELECT id, size FROM '.DB_PREFIX.'files';
$result = $dbim->query($sql);

while ($row = $dbim->fetch_array($result))
{
	// Get new size, build the sql and run it
	$newsize = $row['size'] * $multiple;
	$update_sql = 'UPDATE '.DB_PREFIX.'files SET size = '.$newsize.' WHERE id = '.$row['id'];
	$dbim->query($update_sql);
}

// Add new field to downloads_files and change field type of size column
$dbim->query('ALTER TABLE `'.DB_PREFIX.'files`
				ADD `convert_newlines` TINYINT( 1 ) DEFAULT \'0\' NOT NULL,
				CHANGE `size` `size` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT \'0\',
				ADD `keywords` TEXT NOT NULL ;');

// Create some tables
$dbim->query("CREATE TABLE `".DB_PREFIX."ip_restrict` (
  `id` int(11) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `start` varchar(15) NOT NULL default '',
  `end` varchar(15) NOT NULL default '',
  `mask` varchar(15) NOT NULL default '',
  `action` tinyint(1) NOT NULL default '0',
  `active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;");

$dbim->query("CREATE TABLE `".DB_PREFIX."ip_restrict_log` (
  `id` int(11) NOT NULL auto_increment,
  `timestamp` int(20) default '0',
  `ip_address` varchar(15) NOT NULL default '',
  `request_uri` text NOT NULL,
  `referer` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;");

$dbim->query("CREATE TABLE `".DB_PREFIX."leech_settings` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`domain` TEXT NOT NULL ,
`action` TINYINT( 1 ) NOT NULL ,
PRIMARY KEY ( `id` )
) TYPE = MYISAM ;");

$dbim->query("CREATE TABLE `".DB_PREFIX."recommend_log` (
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
) TYPE = MYISAM ;");

$dbim->query("CREATE TABLE `".DB_PREFIX."recommend_blocklist` (
  `id` int(11) NOT NULL auto_increment,
  `address` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`,`address`)
) TYPE=MyISAM ;");

$dbim->query("ALTER TABLE `".DB_PREFIX."categories` ADD `keywords` TEXT NOT NULL ;");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Olate Download 3 - Upgrade</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<link href="../setup.css" rel="stylesheet" type="text/css" />
</head>
<body>

<table cellspacing="0" cellpadding="0" id="main">
<tr id="logo"><td colspan="2"><img src="../images/logo.gif" width="190" height="68" alt="Logo"/></td></tr>
<tr id="top">
	<td id="left">Install Progress</td>
	<td id="right">Olate Download 3 Setup</td>
</tr>

<tr>
	<td id="progress">
		<ul>
		<li class="first"><span>Start</span></li>
			<ul class="second">
				<li>Action Selection</li>
		<li class="complete">Complete!</li>
		</ul>	
	</td>
	<td id="content">
		<h1>Upgrade Complete</h1>
		<p id="intro">The upgrade from 3.2.1 to 3.3.0 has been completed<a href="index.php"></a> successfully. However, before you can login again, you must take the following actions: </p>
		<br />
        <div id="options">
          <h3>Actions</h3>
          <p>&#8226; Please ensure /setup and all sub directories are removed</p>
          <p>&#8226; There have been new types of permissions added, so check the group settings to make sure you have access to everything you should have!</p>
          <h3>What's New?</h3>
          <p>In this version, the following changes have been made:</p>
          <p> 
			- <a href="http://www.olate.co.uk/tracker/view.php?id=163" title="[resolved] Custom Field Value Won't Update">0000163</a>: <b>[Admin]</b> Custom Field Value Won't Update (David)<br />
			- <a href="http://www.olate.co.uk/tracker/view.php?id=164" title="[resolved] Agreements Parse Error">0000164</a>: <b>[Admin]</b> Agreements Parse Error (David)<br />
			- <a href="http://www.olate.co.uk/tracker/view.php?id=136" title="[resolved] &quot;Accept&quot; button label">0000136</a>: <b>[Core: LM (Languages)]</b> "Accept" button label (David)<br />
			- <a href="http://www.olate.co.uk/tracker/view.php?id=158" title="[resolved] File Count">0000158</a>: <b>[General]</b> File Count (David)<br />
			- <a href="http://www.olate.co.uk/tracker/view.php?id=167" title="[resolved] Incorrect ID in admin notification email on user file upload">0000167</a>: <b>[General]</b> Incorrect ID in admin notification email on user file upload (David)<br />
			- <a href="http://www.olate.co.uk/tracker/view.php?id=169" title="[resolved] Ability to display custom fields">0000169</a>: <b>[General]</b> Ability to display custom fields (David) <br />
			- <a href="http://www.olate.co.uk/tracker/view.php?id=184" title="[resolved] Vulnerability with file uploads">0000184</a>: <b>[Security]</b> Vulnerability with file uploads (David)<br />
			- <a href="http://www.olate.co.uk/tracker/view.php?id=165" title="[resolved] Agreement line breaks not being preserved">0000165</a>: <b>[Templates: olate]</b> Agreement line breaks not being preserved (David)<br />
			- <a href="http://www.olate.co.uk/tracker/view.php?id=181" title="[resolved] Page does not render fully in IE">0000181</a>: <b>[Templates: olate]</b> Page does not render fully in IE (David)</p>
          <p>A full changelog can be viewed in the Changelog.txt file within the original download package.</p>
        </div>
	</td>
</tr>
</table>

</body>
</html>