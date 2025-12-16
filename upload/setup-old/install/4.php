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

// Install
require_once('../../includes/helper.php');
// Check all fields filled in
if (empty($_POST['site_name']) || empty($_POST['url']) || empty($_POST['admin_email']) || empty($_POST['username']) || empty($_POST['password']))
{
	$error = 'You must fill out all the fields';
}
else
{
	// No errors? Good.
	if (!isset($error))
	{
		// Connect to the MySQL server
		require('../../includes/config.php');
		@ $connect = mysql_connect($config['database']['server'], $config['database']['username'], $config['database']['password']);
		
		// Select database
		@ $select = mysql_select_db($config['database']['name']);
		
		// Connect to the MySQL server: Error handling
		if ($connect || $select)
		{
			// Everything seems ok
			require('../sql/data.php');
			
			foreach ($data_sql as $sql)
			{
				$sql = str_replace('downloads_', DB_PREFIX, $sql); // Inserts pefix	
				
				$result = mysql_query($sql);
				if (!$result)
				{
					$error = '<br>Database Error: '.mysql_error();
					break;
				}
			}
			
			if (!isset($error))
			{
				// Are we enabling search?
				if ($_POST['search'] == 1)
				{ // Yes
					$search = 1;
				}
				else
				{ // No
					$search = 0;
				}
				
				// Populate config table
				$config_sql = 'INSERT INTO '.DB_PREFIX.'config 
								SET version = "3.5.0", 
									site_name = "'.htmlentities($_POST['site_name']).'", 
									url = "'.$_POST['url'].'", 
									flood_interval = 60,
									admin_email = "'.$_POST['admin_email'].'", 
									language = "english", 
									template = "olate", 
									date_format = "d/m/Y", 
									filesize_format = "--", 
									page_amount = 10, 
									latest_files = 5, 
									enable_topfiles = 1,
									top_files = 5, 
									enable_allfiles = 1,
									enable_comments = 1, 
									approve_comments = 0, 
									enable_search = '.$search.', 
									enable_ratings = 1, 
									enable_stats = 1, 
									enable_rss = 1, 
									enable_count = 1,
									enable_useruploads = 1,
									enable_actual_upload = 1,
									enable_mirrors = 1,
									enable_leech_protection = 1,
									mirrors = 5,
									uploads_allowed_ext = "",
									userupload_always_approve = 0,
									filter_cats = 0,
									ip_restrict_mode = 0,
									enable_recommend_friend = 1,
									acp_check_extensions = 0,
									use_fckeditor = 0,
									allow_user_lang = 0';
				$result = mysql_query($config_sql);
				if (!$result)
				{
					$error = '<br>Database Error: '.mysql_error();
				}
				else
				{
					// Insert admin user
					require('../../modules/core/uam.php');
					$uam = new uam();
					
					$salt = $uam->generate_salt();
					$password = $uam->encrypt_password($_POST['password'], $salt);
					
					$user_sql = 'INSERT INTO '.DB_PREFIX.'users 
									SET group_id = 2, 
										username = "'.$_POST['username'].'", 
										password = "'.$password.'", 
										salt = "'.$salt.'"';
					$result = mysql_query($user_sql);
					if (!$result)
					{
						$error = '<br>Database Error: '.mysql_error();
					}
					else
					{
						// And we're finally done
						$success = true;
					}
				}
			}				
        
		}
		else
		{
			$error =  'Database Error: '.mysql_error();
		}		
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Olate Download 3 - Installation</title>
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
				<li class="done">Action Selection</li>
				<li class="done">Server Requirements</li>
				<li class="done">License Agreement</li>
			</ul>
		<li class="first"><span>Configuration</span></li>
			<ul class="second">
				<li class="done">Database Settings</li>
				<li class="done">General Settings</li>
			</ul>
		<li class="first"><span>Post-Install</span></li>
			<ul class="second">
				<li class="error">Remove Installer Files</li>
				<li>Login</li>
			</ul>
		<li class="complete">Complete!</li>
		</ul>	
	</td>
	<td id="content">
		<?php
		if (isset($success))
		{
			// Success - tables created
		?>
			<h1>Remove Installer Files</h1>
			<p id="intro">Your database has now been completely set up and is ready for use However, before you can finally login, you must take the following actions: </p>
			<br />
					
			<div id="options">
				<h3>Actions</h3>			
				<p>&#8226; Please ensure  /setup and all sub directories are removed<br />
					&#8226; chmod 755 includes/config.php or remove world writeable permissions (for security) <br />
				</p>
				
				<h3>Environment Survey</h3>
		          <p>To help us determine what environment our products are running in, we have created a simple script which will gather some information about the server it is run on and then anonymously submit that data to us.</p>
		          <p>If you are interested in helping us by running this script, it would be much appreciated and will allow us to decide exactly what kind of platforms we need to continue to support, and what features of new software versions (e.g. PHP/MySQL) we can safely take advantage of.</p>
			  <p>You can take part in this survey by following the instructions.</p>
				<div class="begin"><a href="../../admin.php">Login</a></div><br />
			</div>
			<?php
			if ($search == 0)
			{
			?>
			<p style="color: #CC0000"><strong>Note:</strong> Search functionality was not installed as you are not using MySQL 4.0.2+. If you upgrade MySQL in the future, you can use the tools/fulltext.php script to enable the search feature. </p>
			<?php
			}
			?>
		<?php
		}
		else
		{
			// Failure
		?>
			<h1>Failure</h1>
			<p id="intro">
				You must correct the error below before installation can continue:<br /><br />
				<span style="color:#000000"><?php echo $error; ?></span><br /><br />
				<a href="javascript: history.go(-1)">Click here to go back</a>.</p>
		<?php
		}
		?>
	</td>
</tr>
</table>

</body>
</html>