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

// Install
require_once('../../includes/helper.php');
// Check all fields filled in
if (empty($_POST['server']) || empty($_POST['name']) || empty($_POST['username']) ||  empty($_POST['prefix']))
{
	$error = 'You must fill out all the fields';
}
else
{
	// No errors? Good. Proceed with caution Mr Spock
	if (!isset($error))
	{
			// Host detection for remote vs local
			function _od_get_hostname($host) {
				$h = trim((string)$host);
				if ($h === '') return $h;
				if (stripos($h, 'sqlite:') === 0) return $h;
				if (preg_match('/^\[(.+)\](?::\d+)?$/', $h, $m)) return $m[1];
				if (substr_count($h, ':') > 1) return $h; // IPv6
				if (strpos($h, ':') !== false) {
					$parts = explode(':', $h);
					return $parts[0];
				}
				return $h;
			}
			$server_host = _od_get_hostname($_POST['server']);
			$is_remote_host = true;
			$local_candidates = array('localhost', '127.0.0.1', '::1');
			foreach ($local_candidates as $lc) {
				if (stripos($server_host, $lc) === 0) { $is_remote_host = false; break; }
			}
			if ($is_remote_host) {
				$remote_message = 'Remote database host detected ('.htmlspecialchars($_POST['server']).'). The installer will attempt to connect; ensure your DB server accepts remote connections and user has remote access privileges.';
			}

		// Select database
		@ $select = mysql_select_db($_POST['name']);
		
		// Connect to the MySQL server: Error handling
		if ($connect || $select)
		{	
			// Detect MySQL version - greater than 4.0.2?
			$version = mysql_query('SELECT VERSION() AS version');
			$version = mysql_fetch_array($version);
			
			// Files
			$files_result = mysql_query('SELECT id, name, count, votes, rating, location, size, category, description_brief, description_full
											FROM '.$_POST['od2_prefix'].'files');
												
			$rows = @mysql_num_rows($files_result);
			
			if ($rows == 0)
			{
				$error = 'Database Error: OD2.2 Prefix Incorrect - No OD2.2 tables found';
			}
			else
			{	
				$explode = explode('.', $version['version']);
				$version['major'] = $explode[0];
				$version['minor'] = $explode[1];
				$version['patch'] = $explode[2];
				
				$explode = explode('-', $version['patch']);
				$version['patch'] = $explode[0];
				
				if(($version['major'] >= 4 && $version['minor'] >= 0 && $version['patch'] >= 2) || $version['major'] > 4)
				{
					// Yes
					$search = 1;
					require('../sql/tables.php');
				}
				else
				{
					// No
					$search = 0;
					require('../sql/tables_oldmysql.php');
				}
				
				foreach ($tables_sql as $sql)
				{
					$sql = str_replace('downloads_', $_POST['prefix'], $sql); // Inserts pefix	
					
					$result = mysql_query($sql);
					if (!$result)
					{
						$error = 'Database Error: '.mysql_error();
						break;
					}
				}
				
				if (!isset($error))
				{
					// Import data from OD2.2 tables		
					for ($i = 0; $i < mysql_num_rows($files_result); $i++)
					{
						$file = mysql_fetch_array($files_result);
						
						$filesize = $file['size'] * pow(1024, 2);
						
						// prefix.files
						mysql_query('INSERT INTO '.$_POST['prefix'].'files
										SET id = "'.$file['id'].'",
											category_id = "'.$file['category'].'",
											name = "'.$file['name'].'",
											description_small = "'.$file['description_brief'].'",
											description_big = "'.$file['description_full'].'",
											downloads = "'.$file['count'].'",
											views = 0,
											size = "'.$filesize.'",
											date = "'.time().'",
											rating_votes = "'.$file['votes'].'",
											rating_value = "'.$file['rating'].'"');
													
						// prefix.mirrors
						mysql_query('INSERT INTO '.$_POST['prefix'].'mirrors
										SET file_id = "'.$file['id'].'",
											name = "Mirror",
											location = "Earth",
											url = "'.$file['location'].'"');
					}
							
					// Categories
					$categories_result = mysql_query('SELECT id, name
														FROM '.$_POST['od2_prefix'].'categories');
							
					for ($i = 0; $i < mysql_num_rows($categories_result); $i++)
					{
						$category = mysql_fetch_array($categories_result);
								
						mysql_query('INSERT INTO '.$_POST['prefix'].'categories
										SET id = "'.$category['id'].'",
											name = "'.$category['name'].'",
											description = "'.$category['name'].'"');
					}
							
					// Create the config.php file now we've got the database tables in
					@ $file = fopen('../../includes/config.php', 'w+');
					if (!$file)
					{
						$error = 'Error whilst attempting to open config.php. Please ensure it is writable/it exists.';
					}
					else
					{
						// Create data to go into config.php
						$data = '<?php '."\r\n";	
						$data.= '// Database config data'."\r\n";	
						$data.= '$config[\'database\'][\'username\'] 	= \''.$_POST['username'].'\';'."\r\n";
						$data.= '$config[\'database\'][\'password\'] 	= \''.$_POST['password'].'\';'."\r\n";
						$data.= '$config[\'database\'][\'server\'] 		= \''.$_POST['server'].'\';'."\r\n";
						$data.= '$config[\'database\'][\'name\'] 		= \''.$_POST['name'].'\';'."\r\n";
						$data.= '$config[\'database\'][\'persistant\'] 	= 0;'."\r\n";
						$data.= 'define (\'DB_PREFIX\', \''.$_POST['prefix'].'\');'."\r\n";
						$data.= '?>';
								
						@ $write = fwrite($file, $data);
						if (!$write)
						{
							$error = 'Error whilst attempting to write to config.php. Please ensure it is writable/it exists.';
						}
						else
						{
							fclose($file);
									
							$success = true;
						}
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
				<li class="error">General Settings</li>
			</ul>
		<li class="first"><span>Post-Install</span></li>
			<ul class="second">
				<li>Remove Installer Files</li>
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
			$path = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
		?>
			<h1>General Settings</h1>
			<p id="intro">Your database tables have been sucessfully created and existing data converted to the latest version. </p>
			<?php if (!empty($remote_message)) { echo '<p style="color:#a00"><strong>Note:</strong> '.htmlspecialchars($remote_message).'</p>'; } ?>
			<br />
					
			<div id="options">
				<h3>Settings</h3>			
				<p>Please fill out the information below. The installer will then connect to your database and populate the newly created tables with sample data and your configuration values.</p>		
				<form name="settings" id="settings" method="post" action="4.php">
				    <table width="100%"  border="0" cellspacing="1" cellpadding="4">
                    	<tr>
                    		<td>Site Name:</td>
                    		<td><input name="site_name" type="text" id="site_name" size="25" /></td>
                   		</tr>
                    	<tr>
                    		<td>Base URL:</td>
                    		<td><input name="url" type="text" id="url" size="35" value="<?php echo substr($path, 0, strrpos($path, '/setup')); ?>/"/>
                   			(include trailing slash/) </td>
                   		</tr>
                    	<tr>
                    		<td width="16%">Admin E-Mail:</td>
                    		<td width="84%"><input name="admin_email" type="text" id="admin_email" size="35" /></td>
                   		</tr>
                    	<tr>
                    		<td>Admin Username: </td>
                    		<td><input name="username" type="text" id="username" size="15" /></td>
                   		</tr>
                    	<tr>
                    		<td>Admin Password: </td>
                    		<td><input name="password" type="password" id="password" size="15" /></td>
                   		</tr>
                    	</table>
				    <input name="setup_type" type="hidden" value="install" />
					<input name="search" type="hidden" value="<?php echo $search; ?>" />
				<input type="submit" name="Submit" value="Continue" />
				</form>
			</div>
		<?php
		}
		else
		{
			// Failure
		?>
			<h1>Failure</h1>
			<p id="intro">
				You must correct the error below before installation can continue:<br /><br /><span style="color:#000000"><?php echo $error; ?></span><br /><br />
				<?php if (!empty($remote_message)) { echo '<p style="color:#a00"><strong>Note:</strong> '.htmlspecialchars($remote_message).'</p>'; } ?>
				Before you try again you will need to delete the tables created by the installer, which have the prefix "<?php echo $_POST['prefix']; ?>".<br /><br />
				<a href="javascript: history.go(-1)">Click here to go back</a>.
			</p>
		<?php
		}
		?>
	</td>
</tr>
</table>

</body>
</html>