<?php
/**********************************
* Olate Download 3.3.0
* http://www.olate.co.uk/od3
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 118 $
* @package od
*
* Updated: $Date: 2005-09-19 21:13:19 +0100 (Mon, 19 Sep 2005) $
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
		<p id="intro">The upgrade from 3.3.0-Beta to 3.3.0-Final has been completed<a href="index.php"></a> successfully. However, before you can login again, you must take the following actions: </p>
		<br />
        <div id="options">
          <h3>Actions</h3>
          <p>&#8226; Please ensure /setup and all sub directories are removed</p>
          <p>&#8226; There have been new types of permissions added, so check the group settings to make sure you have access to everything you should have!</p>
          <h3>What's New?</h3>
          <p>A full changelog can be viewed in the Changelog.txt file within the original download package.</p>
        </div>
	</td>
</tr>
</table>

</body>
</html>