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

// Add ability for users to upload files (admin enable/disable option)
$dbim->query('ALTER TABLE '.DB_PREFIX.'config 
				ADD enable_useruploads INT( 1 ) DEFAULT 0 NOT NULL AFTER enable_count');
				
// Add option to enable/disable Top x Files link/feature
$dbim->query('ALTER TABLE '.DB_PREFIX.'config 
				ADD enable_topfiles INT( 1 ) DEFAULT 1 NOT NULL AFTER latest_files');
				
// Add option to enable/disable All Files link/feature
$dbim->query('ALTER TABLE '.DB_PREFIX.'config 
				ADD enable_allfiles INT( 1 ) DEFAULT 1 NOT NULL AFTER top_files');
				
// Add file views (#0000143)
$dbim->query('ALTER TABLE '.DB_PREFIX.'files 
				ADD views INT( 10 ) DEFAULT 0 NOT NULL AFTER downloads');
				
// Add upload extension restrictions (#0000139)
$dbim->query('ALTER TABLE '.DB_PREFIX.'config 
				ADD uploads_allowed_ext TEXT NOT NULL');
				
// More advanced leech protection
$dbim->query('ALTER TABLE '.DB_PREFIX.'config
				ADD enable_leech_protection INT( 1 ) DEFAULT 1 NOT NULL AFTER enable_useruploads');
				
// Custom fields tables
$dbim->query('CREATE TABLE '.DB_PREFIX.'customfields` (
  `id` int(11) NOT NULL auto_increment,
  `label` varchar(50) NOT NULL default "",
  `value` varchar(50) NOT NULL default "",
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;'); 

// Custom fields tables
$dbim->query('CREATE TABLE '.DB_PREFIX.'customfields_data (
  `id` int(10) NOT NULL auto_increment,
  `field_id` int(10) NOT NULL default 0,
  `file_id` int(10) NOT NULL default 0,
  `value` varchar(50) NOT NULL default "",
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;');

// Custom fields admin permissions				
$dbim->query('INSERT INTO '.DB_PREFIX.'permissions
				SET name = "acp_customfields_add",
					setting = 0');
$dbim->query('INSERT INTO '.DB_PREFIX.'userpermissions
				SET permission_id = 24,
					type = "user_group",
					type_value = 2,
					setting = 1');
					
$dbim->query('INSERT INTO '.DB_PREFIX.'permissions
				SET name = "acp_customfields_edit",
					setting = 0');
$dbim->query('INSERT INTO '.DB_PREFIX.'userpermissions
				SET permission_id = 25,
					type = "user_group",
					type_value = 2,
					setting = 1');
					
$dbim->query('INSERT INTO '.DB_PREFIX.'permissions
				SET name = "acp_customfields_delete",
					setting = 0');
$dbim->query('INSERT INTO '.DB_PREFIX.'userpermissions
				SET permission_id = 26,
					type = "user_group",
					type_value = 2,
					setting = 1');
									
// Alter database version
$dbim->query('UPDATE '.DB_PREFIX.'config
				SET version = "3.2.0"');
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
		<p id="intro">The upgrade from 3.1.0 to 3.2.0 has been completed<a href="index.php"></a> successfully. However, before you can login again, you must take the following actions: </p>
		<br />
        <div id="options">
          <h3>Actions</h3>
          <p>&#8226; Please ensure /setup and all sub directories are removed</p>
          <h3>What's New?</h3>
          <p>In this version, the following changes have been made:</p>
          <p> - <a href="http://www.olate.co.uk/tracker/view.php?id=130">0000130 </a>: <strong>[Admin] </strong> Manage Comments listing all comments have same file (David) <br />
            - 0000000 : <strong>[Admin]</strong> Fixed a couple of bugs with the settings and editing the enable_count             (David)<br />
            - 0000000 : <strong></strong><strong>[Core: DBIM (Database Interaction Module)]</strong> Added query display to DBIM error handling for debugging             (David)<br />
            - <a href="http://www.olate.co.uk/tracker/view.php?id=121">0000121 </a>: <strong>[Core: UIM (User Interface)] </strong> current language file not properly selected (David)<br />
            - <a href="http://www.olate.co.uk/tracker/view.php?id=146">0000146 </a>: <strong>[Core: UIM (User Interface)] </strong> Variable inheritance with {insert} (David)            <br />
            - 0000000 : <strong></strong><strong>[Core: UIM (User Interface)]</strong> Fixed a bug with the search disabled display             (David)<br />
            - 0000000 : <strong></strong><strong>[Core: UIM (User Interface)]</strong> Added option to disable &quot;Top x Files&quot; and &quot;All files&quot; links/features (David)<br />
- 0000000 : <strong></strong><strong>[Core: UIM (User Interface)]</strong> Search link will now disappear if search is disabled             (David)<br />
            - <a href="http://www.olate.co.uk/tracker/view.php?id=123">0000123 </a>: <strong>[General] </strong> Ratings could not be disabled (David)            <br />
            - <a href="http://www.olate.co.uk/tracker/view.php?id=126">0000126 </a>: <strong>[General] </strong> Problem when reporting a problem with a download (David) <br />

- <a href="http://www.olate.co.uk/tracker/view.php?id=139">0000139 </a>: <strong>[General] </strong> Restrict file type (David) <br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=143">0000143 </a>: <strong>[General] </strong> Tracking views (David) <br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=144">0000144 </a>: <strong>[General] </strong> Info block (David)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=148">0000148 </a>: <strong>[Setup System] </strong> 3.1.0 -&gt; 3.2.0 Updater - Add column to wrong table (David)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=153">0000153</a> : <strong>[General]</strong> Added custom fields for all files (David)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=149">0000149 </a>: <strong>[General] </strong> Problem with advanced leech protection (David)<br />
- 0000000 : <strong></strong><strong>[General]</strong> Added ability to have user uploads (enable/disable via admin panel) (David)<br />
- 0000000 : <strong></strong><strong>[General]</strong> Added file statuses to enable or disable files (David)<br />
- 0000000 : <strong></strong><strong>[General]</strong> Added more advanced leech protection (David)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=154">0000154 </a>: <strong>[Templates: olate] </strong> Title bar is {lang:admin:groups_delete (David)          </p>
          <p>A full changelog can be viewed in the Changelog.txt file within the original download package and also in the <a href="http://www.olate.co.uk/tracker/changelog_page.php">live changelog</a>. </p>
        </div>
	</td>
</tr>
</table>

</body>
</html>