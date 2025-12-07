<?php
/**********************************
* Olate Download 3.3.0
* http://www.olate.co.uk/od3
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 213 $
* @package od
*
* Updated: $Date: 2005-12-22 14:55:42 +0000 (Thu, 22 Dec 2005) $
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

// Alter database version
$dbim->query('UPDATE '.DB_PREFIX.'config
				SET version = "3.4.0"');

// Add field to downloads_files
$dbim->query('ALTER TABLE `'.DB_PREFIX.'files` 
				ADD `activate_at` INT( 10 ) DEFAULT \'0\' NOT NULL ;');

// Add config table fields
$dbim->query('ALTER TABLE `'.DB_PREFIX.'config` 
				ADD `allow_user_lang` TINYINT( 1 ) NOT NULL ;');

// Add entries to permissions table
$dbim->query("INSERT INTO `".DB_PREFIX."permissions`
				( `permission_id` , `name` , `setting` )
				VALUES
					(30, 'acp_files_mass_move', 0),
					(31, 'acp_files_mass_delete', 0),
					(32, 'acp_languages', '0');");

// Create languages table
$dbim->query('CREATE TABLE `'.DB_PREFIX.'languages` (
				`id` int(11) NOT NULL auto_increment,
				`name` text NOT NULL,
				`site_default` tinyint(1) NOT NULL default \'0\',
				`filename` text NOT NULL,
				`version_major` tinyint(4) NOT NULL default \'0\',
				`version_minor` tinyint(4) NOT NULL default \'0\',
				PRIMARY KEY  (`id`)
				) ENGINE=MyISAM;');

$dbim->query('INSERT INTO `'.DB_PREFIX.'languages` 
				VALUES (\'\', \'English (British)\', 1, \'english.php\', 3, 4)');

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
		<p id="intro">The upgrade from 3.3.1 to 3.4.0 has been completed<a href="index.php"></a> successfully. However, before you can login again, you must take the following actions: </p>
		<br />
        <div id="options">
          <h3>Actions</h3>
          <p>&#8226; Please ensure /setup and all sub directories are removed</p>
          <p>&#8226; There have been new access permissions added in this version.  Please check that you have given user groups access to any appropriate new sections</p>
          <h3>Environment Survey</h3>
          <p>To help us determine what environment our products are running in, we have created a simple script which will gather some information about the server it is run on and then anonymously submit that data to us.</p>
          <p>If you are interested in helping us by running this script, it would be much appreciated and will allow us to decide exactly what kind of platforms we need to continue to support, and what features of new software versions (e.g. PHP/MySQL) we can safely take advantage of.</p>
          <p>You can take part in this survey <a href="../../environment.php">by clicking here</a> and following the instructions.</p>
          <h3>What's New?</h3>
          <p>A full changelog can be viewed in the Changelog.txt file within the original download package.</p>
        </div>
	</td>
</tr>
</table>

</body>
</html>
