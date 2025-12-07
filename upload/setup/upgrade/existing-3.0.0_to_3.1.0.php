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

// Change DB_PREFIXfiles.size to a float fixing #0000096
$dbim->query('ALTER TABLE '.DB_PREFIX.'files 
				CHANGE size size FLOAT( 11 )');
				
// Add status field for file uploads
$dbim->query('ALTER TABLE '.DB_PREFIX.'files 
				ADD status INT( 1 ) DEFAULT 1 NOT NULL');

// Add option to enable category file count
$dbim->query('ALTER TABLE '.DB_PREFIX.'config 
				ADD enable_count INT( 1 ) DEFAULT 1 NOT NULL 
				AFTER enable_rss');

// New comments management functions					
$dbim->query('INSERT INTO '.DB_PREFIX.'permissions
				SET name = "acp_files_manage_comments",
					setting = 0');

$dbim->query('INSERT INTO '.DB_PREFIX.'userpermissions
				SET permission_id = 23,
					type = "user_group",
					type_value = 2,
					setting = 1');
									
// Alter database version
$dbim->query('UPDATE '.DB_PREFIX.'config
				SET version = "3.1.0"');
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
		<p id="intro">The upgrade from 3.0.0 to 3.1.0 has been completed<a href="index.php"></a> successfully. However, before you can login again, you must take the following actions: </p>
		<br />		
        <div id="options">
          <h3>Actions</h3>
          <p>
            <?php
		  // Check config.php is writable
			if (!is_writable('../../uploads'))
			{
			?>
		  </p>
		  <p>&#8226; uploads/ not server writable <span style="color:#CC0000">You need to chmod this directory to 777 and/or change file permissions to allow server writing for file uploads.</span></p>
			<?php
			}
			?>
		  <p>&#8226; Run the v3.1.0 to v3.2.0 upgrader by <a href="existing-3.1.0_to_3.2.0.php">clicking here</a></p>
          <h3>What's New?</h3>
          <p>In this version, the following changes have been made:</p>
          <p> - <a href="http://www.olate.co.uk/tracker/view.php?id=117">0000117 </a>: <strong>[Admin] </strong> File Uploads (David) <br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=116">0000116 </a>: <strong>[Admin] </strong> Comments Management (David) <br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=103">0000103 </a>: <strong>[Admin] </strong> (Edit Existing File) Download Locations Value Error (David)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=99">0000099 </a>: <strong>[Admin] </strong> Editing Categories (Matt)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=98">0000098 </a>: <strong>[Admin] </strong> Ability to delete/edit approved comments (David)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=97">0000097 </a>: <strong>[Admin] </strong> Template select text (David)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=95">0000095 </a>: <strong>[Admin] </strong> Incorrect file link on editing file success (David)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=112">0000112 </a>: <strong>[Core: FCM (Categories)] </strong> Loss of ability to assign parent category and list category when edited. (Matt) <br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=111">0000111 </a>: <strong>[Core: FCM (Categories)] </strong> Category File Count (David)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=106">0000106 </a>: <strong>[Core: FCM (Categories)] </strong> Limited levels of categories (Matt)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=107">0000107 </a>: <strong>[Core: FLDM (File Listing)] </strong> Certain fields are required when add a file URL. (David) <br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=102">0000102 </a>: <strong>[Core: FLDM (File Listing)] </strong> Ability to edit file added date. (Matt)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=113">0000113 </a>: <strong>[Core: UIM (User Interface)] </strong> Using directories in {insert} (Matt)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=109">0000109 </a>: <strong>[Core: UIM (User Interface)] </strong> Seperate heading for Agreements (David)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=108">0000108 </a>: <strong>[Core: UIM (User Interface)] </strong> Where you return to after either ADDing or EDITing a file. (David)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=114">0000114 </a>: <strong>[Templates: olate] </strong> Typo on button "Logged in" (Matt)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=110">0000110 </a>: <strong>[Templates: olate] </strong> RSS link appears even if disabled (David) <br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=94">0000094 </a>: <strong>[Templates: olate] </strong> Error 404 to template image reference (David)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=104">0000104 </a>: <strong>[General] </strong> RSS Feed Statistical Data (David)<br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=96">0000096 </a>: <strong>[General] </strong> Filesize is integer, should be float (David)<br />

- <a href="http://www.olate.co.uk/tracker/view.php?id=93">0000093 </a>: <strong>[General] </strong> RSS Feed $global_vars Error in core.tpl.php (David) <br />
- <a href="http://www.olate.co.uk/tracker/view.php?id=92">0000092 </a>: <strong>[General] </strong> Parse error in RSS feed (David)          </p>
          <p>A full changelog can be viewed in the Changelog.txt file within the original download package and also in the <a href="http://www.olate.co.uk/tracker/changelog_page.php">live changelog</a>. </p>
        </div>
	</td>
</tr>
</table>

</body>
</html>