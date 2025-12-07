<?php

include './includes/init.php';

error_reporting(E_ALL);

// Include module
require_once('modules/core/listings.php');
$listing = new listing();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Mass file adder</title>
<style type="text/css">
body,table,tr,td { font: 13px Trebuchet MS; }
</style>
</head>
<body>
<h1>Mass file adder</h1>

<div style="margin: 10px; padding: 5px; border: 1px red solid; background-color: #FFEAEA; font-size: 16px;">
	If you find this script doesn't show all the files it should, please contact David Salisbury (Olate Download developer) by email.  The email address to use is <a href="mailto:dsalisbury@olate.co.uk">dsalisbury@olate.co.uk</a>.
</div>

<?php

if (empty($_POST['submit']))
{
	$path = realpath('.').'/uploads';
	
	$protocol = (empty($_SERVER['HTTPS'])) ? 'http://' : 'https://';
	$port = (intval($_SERVER['SERVER_PORT']) == 80) ? '' : ':'.$_SERVER['SERVER_PORT'];
	$url_file = dirname($_SERVER['PHP_SELF']);
	$url = $protocol.$_SERVER['HTTP_HOST'].$url_file.'/uploads';
?>
<h2>Please fill in some settings</h2>
<p>Enter the full path to the directory containing the files you wish to add to your download site along with the URL to access this directory. If your files have ID3 tags (i.e. for .mp3 files), the script can extract the data from them for you.</p>
<form action="mass_add.php" method="post">
<input type="hidden" name="action" value="list_files" />
<table>
	<tr>
		<td>Filesystem path: </td>
		<td><input type="text" name="path" value="<?php echo $path; ?>" size="50" /> (don't include a trailing slash)</td>
	</tr>
	<tr>
		<td>URL of this folder</td>
		<td><input type="text" name="url" value="<?php echo $url; ?>" size="50" /></td>
	</tr>
	<tr>
		<td>Look for ID3 (and other) tags?</td>
		<td>
			<input type="checkbox" name="use_id3" value="1" />
		</td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="List files" /></td>
	</tr>
</table>
</form>
<?php
}
else
{
	if (!empty($_POST['action']) && $_POST['action'] == 'add_files')
	{
		if (!empty($_POST['files']) && is_array($_POST['files']) && count($_POST['files']) > 0)
		{
			foreach ($_POST['files'] as $file_num => $file)
			{
				if (!empty($file['add']))
				{
					// What do we need to multiply size by to get it into bytes
					if ($file['size_unit'] == 'GB')
					{
						$power = 3;
					}
					elseif ($file['size_unit'] == 'MB')
					{
						$power = 2;
					}
					elseif ($file['size_unit'] == 'KB')
					{
						$power = 1;
					}
					else
					{
						$power = 0;
					}
					
					// Convert filesize to bytes
					$size_bytes = $file['size'] * pow(1024, $power);
					
					// Insert into DB
					$dbim->query('INSERT INTO '.DB_PREFIX.'files
									SET category_id = "'.$file['category'].'", 
										name = "'.$file['name'].'", 
										description_small = "'.$file['description_small'].'", 
										description_big = "'.$file['description_big'].'", 
										size = "'.$size_bytes.'", 
										date = "'.time().'",
										agreement_id = "'.$file['agreement'].'",
										status = 1');
					$file_id = $dbim->insert_id();
					
					$dbim->query('INSERT INTO '.DB_PREFIX.'mirrors
									SET file_id = '.$file_id.', 
										name = "Mirror 1", 
										location = "Earth", 
										url = "'.$_POST['base_url'].'/'.$file['name'].'"');
										
					echo 'File '.$file_num.' ('.$file['name'].') added<br />';
				}
			}
		}
	}
	elseif (!empty($_POST['action']) && $_POST['action'] == 'list_files')
	{
		if (!is_dir($_POST['path']))
		{
			echo "Invalid directory";
		}
		else
		{
			if ($handle = opendir($_POST['path']))
			{
?>
<p>Fill out the details for the files below and then click Continue at the bottom of the page to add the files to your site.</p>
<form action="mass_add.php" method="post" name="path" id="path">
<input type="hidden" name="action" value="add_files" />
<input type="hidden" name="base_path" value="<?php echo $_POST['path']; ?>" />
<input type="hidden" name="base_url" value="<?php echo $_POST['url']; ?>" />
<table border="1">
<?php
	$row = 1;
	
	if (!empty($_POST['use_id3']))
	{
		// ID3 stuff
		require('./getid3/getid3.php');
		
		// Initialize getID3 engine
		$getID3 = new getID3;
	}
		
	while (false !== ($file = readdir($handle)))
	{
		$file_path = $_POST['path'].'/'.$file;
		$file_url = $_POST['url'].'/'.$file;
		
		if (!is_dir($file_path) && (strpos($file, '.') !== 0))
		{
			$filesize = $fldm->format_size(filesize($file_path));
			
			$files[$file] = array(
				'name' => $file,
				'size' => $filesize
			);
			
			if (!empty($_POST['use_id3']))
			{
				$file_id3 = $getID3->analyze($file_path);

				getid3_lib::CopyTagsToComments($file_id3);
				
				$files[$file]['id3'] = $file_id3;
				
				#var_dump($file_id3);
			}
		}
	}
	
	ksort($files);
	
	foreach ($files as $file)
	{
		if (empty($_POST['use_id3']) || (!empty($file['id3']['error']) && is_array($file['id3']['error']) 
			&& in_array('unable to determine file format', $file['id3']['error'])))
		{
			$name = $file['name'];
			$short_desc = '';
			$large_desc = '';
		}
		else
		{
			$name = $file['id3']['comments_html']['artist'][0]." - ".$file['id3']['comments_html']['title'][0];
			$short_desc = $name;
			$large_desc = "Bitrate: ".round($file['id3']['audio']['bitrate']/1000, 1)." Kbps<br />\nPlaying Time: ".$file['id3']['playtime_string'];
		}
		
?>
<h3 style="font:verdana, tahoma, sans-serif">File <?php echo $row; ?></h3>
<table width="100%"  border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td width="19%">File Name:</td>
		<td width="81%"><input name="files[<?php echo $row; ?>][name]" type="text" value="<?php echo $name; ?>" size="30" /></td>
	</tr>
	<tr>
		<td valign="top">Short Description:</td>
		<td><textarea name="files[<?php echo $row; ?>][description_small]" cols="45" rows="3"><?php echo $short_desc; ?></textarea></td>
	</tr>
	<tr>
		<td valign="top">Large Description:</td>
		<td><textarea name="files[<?php echo $row; ?>][description_big]" cols="45" rows="5"><?php echo $large_desc; ?></textarea></td>
	</tr>
	<tr>
		<td>Category:</td>
		<td>
			<select name="files[<?php echo $row; ?>][category]">
				<option selected>Select Category</option>
<?php
	echo $listing->list_cat_combo_box_html('');
?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Agreement:</td>
		<td>
			<select name="files[<?php echo $row; ?>][agreement]">
				<option value="0">Select Agreement</option>
				<option value="0" selected>None</option>
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
			</select>
		</td>
	</tr>
	<tr>
		<td>File Size:</td>
		<td>
			<input name="files[<?php echo $row; ?>][size]" type="text" value="<?php echo $file['size']['size']; ?>" size="5">
			<select name="files[<?php echo $row; ?>][size_unit]">
				<option value="<?php echo $file['size']['unit']; ?>" selected="selected"><?php echo $file['size']['unit']; ?></option>
				<option value="<?php echo $file['size']['unit']; ?>">- - -</option>
				<option value="B">B</option>
				<option value="KB">K</option>
				<option value="MB">MB</option>
				<option value="GB">GB</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Add file:</td>
		<td><input name="files[<?php echo $row; ?>][add]" type="checkbox" value="1" checked> Untick to not add this file</td>
	</tr>
</table>
<input type="hidden" name="files[<?php echo $row; ?>][filename]" value="<?php echo $file['name']; ?>">
<hr size="1" color="#000000">
<?php
			$row++;
		}
?>
<input type="submit" name="submit" value="Add files" />
</form>
<?php
	}
	else
	{
		echo "Couldn't read from directory";
	}
?>
</table>
<?php
		}
	}
}

?>
</body>
</html>