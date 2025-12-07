<?php

// Initialise
include './includes/init.php';

// General useful things
ini_set('implicit_flush', 'on');
error_reporting(E_ALL);
restore_error_handler();

// Include module
require_once('modules/core/listings.php');
$listing = new listing();

session_start();

function verbose($message)
{
	if (defined('VERBOSE') && VERBOSE)
	{
		echo $message."<br />\n";
	}
}

function getfilesize($url)
{
	$parsed = parse_url($url);
	
	if (empty($parsed['scheme']))
	{
		if (empty($_ENV['HTTPS']))
		{
			$parsed['scheme'] = 'http';
		}
		else
		{
			$parsed['scheme'] = 'https';
		}
	}
	
	if (empty($parsed['host']))
	{
		$parsed['host'] = $_SERVER['HTTP_HOST'];
	}
	
	if (empty($parsed['port']))
	{
		$parsed['port'] = $_SERVER['SERVER_PORT'];
	}
	
	if (empty($parsed['path']))
	{
		$parsed['path'] = '';
	}
	
	$user_pass = '';
	
	if (!empty($parsed['user']) && !empty($parsed['pass']))
	{
		$user_pass = $parsed['user'].':'.$parsed['pass'].'@';
	}
	
	$end = '';
	
	if (!empty($parsed['query']))
	{
		$end .= '?'.$parsed['query'];
	}
	
	if (!empty($parsed['fragment']))
	{
		$end .= '#'.$parsed['fragment'];
	}
	
	$complete_url = $parsed['scheme'].'://'.$user_pass.$parsed['host'].':'.$parsed['port'].$parsed['path'].$end;
	
	if (!$fp = fopen($complete_url, 'r'))
	{
		echo '<span style="color: red;">Error getting filesize of '.$url.'.  Please manually set this value in the Admin Control Panel</span><br />';
		return 0;
	}
	
	$meta = stream_get_meta_data($fp);
	
	if (sizeof($meta) > 0)
	{
		foreach ($meta['wrapper_data'] as $data)
		{
			if (strpos($data, 'Content-Length:') === 0)
			{
				$length = str_replace('Content-Length: ', '', $data);
				$length = intval($length);
				
				break;
			}
		}
	}
	
	fclose($fp);
	
	if (!empty($length))
	{
		return $length;
	}
	else
	{
		return 0;
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>paFileDB 3.1 Importer</title>
<style type="text/css">
body,table,tr,td { font: 13px Trebuchet MS; }

li {padding-bottom: 5px;margin-bottom: 5px;border-bottom: 1px black solid;}
</style>
</head>
<body>

<h1>paFileDB 3.1 Importer</h1>

<?php

if (!empty($_GET['action']) && $_GET['action'] == 'list_importable')
{
	if (@mysql_connect($_POST['hostname'], $_POST['username'], $_POST['password'], true))
	{
		if (@mysql_select_db($_POST['dbname']))
		{
			$_SESSION['db']['username'] = $_POST['username'];
			$_SESSION['db']['password'] = $_POST['password'];
			$_SESSION['db']['hostname'] = $_POST['hostname'];
			$_SESSION['db']['dbname'] = $_POST['dbname'];
			$_SESSION['db']['prefix'] = $_POST['prefix'];
		}
		else
		{
			die('<h2>Couldn\'t select database</h2>');
		}
	}
	else
	{
		die('<h2>Couldn\'t connect to database server</h2>');
	}
?>
<form action="pafiledb-3.1.php?action=do_import" method="post">
<h2>What do you want to import?</h2>
<ol>
	<li>
		<input type="radio" name="import[categories]" value="1" checked="checked" /> Import categories<br />
		or<br />
		<input type="radio" name="import[categories]" value="0" /> Don't import categories, instead insert any files into this category: 
		<select name="dest_category">
			<option selected="selected">Select Category</option>
<?php
	echo $listing->list_cat_combo_box_html('');
?>
		</select>
	</li>
	<li>
		<input type="radio" name="import[files]" value="1" checked="checked" /> Import files<br />
		or<br />
		<input type="radio" name="import[files]" value="0" /> Don't import files<br />
	</li>
	<li>
		<input type="radio" name="import[fields]" value="2" checked="checked" /> Import custom fields and values<br />
		or<br />
		<input type="radio" name="import[fields]" value="1" /> Import custom fields without values<br />
		or<br />
		<input type="radio" name="import[fields]" value="0" /> Don't import custom fields<br />
	</li>
	<li>
		<input type="radio" name="import[agreements]" value="1" checked="checked" /> Import agreements<br />
		or<br />
		<input type="radio" name="import[agreements]" value="0" /> Don't import agreements, instead use this agreement: 
		<select name="agreement_id">
			<option value="0" selected="selected">None</option>
<?php
	// Get the agreements
	$agreements_result = $dbim->query('SELECT id, name, contents
										FROM '.DB_PREFIX.'agreements');
	
	while ($agreement = $dbim->fetch_array($agreements_result))
	{
?>
			<option value="<?php echo $agreement['id']; ?>"><?php echo $agreement['name']; ?></option>
<?php
	}
?>
		</select><br />
	</li>
</ol>
<p>
	<input type="checkbox" name="detailed_output" value="1" /> Show detailed output<br />
	<input type="submit" name="submit" value="Proceed" />
</p>
</form>
<?php
}
elseif (!empty($_GET['action']) && $_GET['action'] == 'do_import')
{
	if (!@mysql_connect($_SESSION['db']['hostname'], $_SESSION['db']['username'], $_SESSION['db']['password'], true))
	{
		die('<h2>Couldn\'t connect to database server</h2>');
	}
	else
	{
		if (!@mysql_select_db($_SESSION['db']['dbname']))
		{
			die('<h2>Couldn\'t select database</h2>');
		}
	}
	
	$import = array_map('intval', $_POST['import']);
	
	define('VERBOSE', ((!empty($_POST['detailed_output'])) ? true : false));
	
	define('PAFDB_PREFIX', mysql_real_escape_string($_SESSION['db']['prefix']));
	
	if ($import['agreements'] === 1)
	{
		echo "<h2>Inserting agreements...</h2>";
		
		// Get paFileDB agreements
		$agreements_result = mysql_query('SELECT license_id, license_name, license_text
											FROM '.PAFDB_PREFIX.'license');
		
		if (mysql_num_rows($agreements_result) > 0)
		{
			while ($agreement = mysql_fetch_array($agreements_result))
			{
				verbose('Inserting agreement "'.$agreement['license_name'].'"');
				
				$dbim->query('INSERT INTO '.DB_PREFIX.'agreements
								SET
									name = "'.mysql_real_escape_string($agreement['license_name']).'",
									contents = "'.mysql_real_escape_string($agreement['license_text']).'"');
				
				$agreement_ids[$agreement['license_id']] = $dbim->insert_id();
			}
		}
		else
		{
			verbose('No agreements in database');
		}
	}
	else
	{
		echo "<h2>Skipping agreements...</h2>";
	}
	
	// Custom fields
	if ($import['fields'] > 0)
	{
		echo "<h2>Inserting custom fields...</h2>";
		
		$cfield_result = mysql_query('SELECT custom_id, custom_name
										FROM '.PAFDB_PREFIX.'custom');
		
		if (mysql_num_rows($cfield_result) > 0)
		{
			while ($cfield = mysql_fetch_array($cfield_result))
			{
				verbose('Inserting custom field "'.$cfield['custom_name'].'"');
				
				$dbim->query('INSERT INTO '.DB_PREFIX.'customfields
								SET
									label = "'.mysql_real_escape_string($cfield['custom_name']).'"');
				
				$cfield_ids[$cfield['custom_id']] = $dbim->insert_id();
			}
		}
		else
		{
			verbose('No custom fields in database');
		}
	}
	else 
	{
		echo "<h2>Skipping custom fields...</h2>";
	}
	
	// Categories?
	if ($import['categories'] === 1)
	{
		echo "<h2>Inserting categories...</h2>";
		
		$root_cat_result = mysql_query('SELECT cat_id, cat_name, cat_desc
										FROM '.PAFDB_PREFIX.'cat
										WHERE cat_parent = 0
										ORDER BY cat_order ASC');
		
		if (mysql_num_rows($root_cat_result) > 0)
		{
			while ($root_cat = mysql_fetch_array($root_cat_result))
			{
				verbose('Inserting category "'.$root_cat['cat_name'].'"');
				
				$dbim->query('INSERT INTO '.DB_PREFIX.'categories
								SET
									parent_id = 0,
									name = "'.$root_cat['cat_name'].'",
									description = "'.$root_cat['cat_desc'].'"');
				
				$parent_id = $cat_ids[$root_cat['cat_id']] = $dbim->insert_id();
				
				// Sub categories?
				$sub_cat_result = mysql_query('SELECT cat_id, cat_name, cat_desc
												FROM '.PAFDB_PREFIX.'cat
												WHERE cat_parent = '.$root_cat['cat_id'].'
												ORDER BY cat_order ASC');
				
				if (mysql_num_rows($sub_cat_result) > 0)
				{
					while ($sub_cat = mysql_fetch_array($sub_cat_result))
					{
						verbose('Inserting sub-category "'.$sub_cat['cat_name'].'"');
						
						$dbim->query('INSERT INTO '.DB_PREFIX.'categories
										SET
											parent_id = '.$parent_id.',
											name = "'.$sub_cat['cat_name'].'",
											description = "'.$sub_cat['cat_desc'].'"');
						
						$cat_ids[$sub_cat['cat_id']] = $dbim->insert_id();
					}
				}
			}
		}
		else
		{
			verbose('No categories to insert');
		}
	}
	else 
	{
		echo "<h2>Skipping categories</h2>";
	}
	
	if ($import['files'] === 1)
	{
		echo "<h2>Inserting files...</h2>";
		
		$files_result = mysql_query('SELECT * 
										FROM '.PAFDB_PREFIX.'files');
		
		if (mysql_num_rows($files_result) > 0)
		{
			while ($file = mysql_fetch_array($files_result))
			{
				verbose('Inserting file "'.$file['file_name'].'"');
				
				if ($import['categories'] === 1)
				{
					$cat_id = $cat_ids[$file['file_catid']];
				}
				else
				{
					$cat_id = intval($_POST['dest_category']);
				}
				
				if ($import['agreements'] === 1 && intval($file['file_license']) !== 0)
				{
					$agreement_id = $agreement_ids[$file['file_license']];
				}
				elseif (intval($file['file_license']) !== 0)
				{
					$agreement_id = intval($_POST['agreement_id']);
				}
				else 
				{
					$agreement_id = 0;
				}
				
				$filesize = getfilesize($file['file_dlurl']);
				
				$desc_big = $file['file_longdesc'];
				
				if (!empty($file['file_version']) || !empty($file['file_creator']) || !empty($file['file_ssurl']) || !empty($file['file_docsurl']))
				{
					$desc_big .= "\n";
				}
				
				if (!empty($file['file_version']))
				{
					$desc_big .= "\nVersion: ".$file['file_version'];
				}
				
				if (!empty($file['file_creator']))
				{
					$desc_big .= "\nCreator: ".$file['file_creator'];
				}
				
				if (!empty($file['file_ssurl']))
				{
					$desc_big .= "\nScreenshot: ".$file['file_ssurl'];
				}
				
				if (!empty($file['file_docsurl']))
				{
					$desc_big .= "\nDocumentation: ".$file['file_docsurl'];
				}
				
				$dbim->query('INSERT INTO '.DB_PREFIX.'files
								SET
									name = "'.$file['file_name'].'",
									category_id = '.$cat_id.',
									description_small = "'.$file['file_desc'].'",
									description_big = "'.$desc_big.'",
									date = '.$file['file_time'].',
									rating_value = '.($file['file_rating'] / 2).',
									rating_votes = '.$file['file_totalvotes'].',
									agreement_id = '.$agreement_id.',
									convert_newlines = 1,
									status = 1,
									size = '.$filesize);
				
				$file_id = $dbim->insert_id();
				
				if ($file['file_dlurl'] != '')
				{
					verbose('Inserting mirror');
					
					// Mirror
					$dbim->query('INSERT INTO '.DB_PREFIX.'mirrors
									SET
										file_id = '.$file_id.',
										name = "Mirror 1",
										location = "The Internet",
										url = "'.$file['file_dlurl'].'"');
				}
				else
				{
					verbose('No mirror to insert');
				}
				
				// Are there any custom fields?
				if ($import['fields'] === 2 && sizeof($cfield_ids) > 0)
				{
					verbose('Inserting custom field data');
					
					// Get custom field data
					$cfield_data_result = mysql_query('SELECT customdata_custom, data
														FROM '.PAFDB_PREFIX.'customdata
														WHERE customdata_file = '.$file['file_id']);
					
					while ($cfield_data = mysql_fetch_array($cfield_data_result))
					{
						$od_cfield_id = $cfield_ids[$cfield_data['customdata_custom']];
						
						$dbim->query('INSERT INTO '.DB_PREFIX.'customfields_data
										SET
											field_id = '.$od_cfield_id.',
											file_id = '.$file_id.',
											value = "'.$cfield_data['data'].'"');
					}
				}
				elseif ($import['fields'] !== 2)
				{
					verbose('Skipping custom field data');
				}
				else
				{
					verbose('No custom field data to insert');
				}
			}
		}
		else 
		{
			verbose('No files to insert');	
		}
	}
	else 
	{
		echo "<h2>Skipping files</h2>";
	}
}
else
{
?>
<h2>Please enter your paFileDB database access details</h2>
<form action="pafiledb-3.1.php?action=list_importable" method="post">
<table>
	<tr>
		<td>Database hostname:</td>
		<td><input type="text" name="hostname" value="localhost" />
	</tr>
	<tr>
		<td>Username:</td>
		<td><input type="text" name="username" value="root" />
	</tr>
	<tr>
		<td>Password:</td>
		<td><input type="password" name="password" />
	</tr>
	<tr>
		<td>Database name:</td>
		<td><input type="text" name="dbname" />
	</tr>
	<tr>
		<td>Table prefix:</td>
		<td><input type="text" name="prefix" value="pafiledb_" />
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Proceed" />
	</tr>
</table>
</form>
<?php
}

?>

</body>
</html>