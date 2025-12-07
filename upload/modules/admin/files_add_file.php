<?php
/**********************************
* Olate Download 3.4.0
* http://www.olate.co.uk/od3
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 202 $
* @package od
*
* Updated: $Date: 2005-12-18 16:37:18 +0000 (Sun, 18 Dec 2005) $
*/

// Start admin cp
$start = $uim->fetch_template('admin/start');
$start->show();

if ($uam->permitted('acp_files_add_file'))
{		
	// Upload file
	if (isset($_FILES['uploadfile']))
	{
		$ext = strrchr($_FILES['uploadfile']['name'], '.');
		$allowed_ext = explode(',', $site_config['uploads_allowed_ext']);
			
		// It's like finding a needle in a haystack...
		if (in_array($ext, $allowed_ext) || !($site_config['acp_check_extensions']))
		{	
			if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], './uploads/'.basename($_FILES['uploadfile']['name']))) 
			{
			   // File was uploaded successfully - add as a mirror
			   $dbim->query('INSERT INTO '.DB_PREFIX.'mirrors
								SET file_id = '.$_REQUEST['file_id'].', 
									name = "Mirror 1", 
									location = "Earth", 
									url = "'.$site_config['url'].'uploads/'.basename($_FILES['uploadfile']['name']).'"');
				
			   $filesize = filesize('./uploads/'.basename($_FILES['uploadfile']['name']));
			   
				// Set file active
				$dbim->query('UPDATE '.DB_PREFIX.'files
								SET status = 1,
									size = '.$filesize.'
								WHERE (id = '.$_REQUEST['file_id'].')');
								
				// Template
				$add_file = $uim->fetch_template('admin/files_add_file');
				
				$success = true; // For redirect EOF
				$add_file->assign_var('id', $_REQUEST['file_id']);
				$add_file->assign_var('success', true);				
			} 
			else 
			{
				switch ($_FILES['uploadfile']['error'])
				{
					case 1:
						$error = $lm->language('admin', 'upload_error_1');
						break;
					case 3:
						$error = $lm->language('admin', 'upload_error_3');
						break;
					case 4:
						$error = $lm->language('admin', 'upload_error_4');
						break;
				}
				
				// Template
				$add_file = $uim->fetch_template('admin/files_upload_file');
				$add_file->assign_var('error', $error);
			}
		}
		else
		{
			// Template
			$add_file = $uim->fetch_template('admin/files_upload_file');
			$add_file->assign_var('error', $lm->language('admin', 'upload_error_ext'));
		}
	}
	
	// Has the form been submitted?
	if (isset($_REQUEST['submit']) && !empty($_REQUEST['name']))
	{
		validate_types($_REQUEST, array('name' => 'STR', 'description_small' => 'STR_HTML',
										 'description_big' => 'STR_HTML', 'category' => 'INT',
										 'downloads' => 'INT', 'views' => 'INT', 'size' => 'FLOAT', 
										 'agreement' => 'INT', 'password' => 'STR', 'upload' => 'INT',
										 'convert_newlines' => 'INT', 'filesize_format' => 'STR', 
										 'keywords' => 'STR', 'day' => 'INT', 'month' => 'INT', 'year' => 'INT', 
										 'hour' => 'INT', 'minute' => 'INT'));
	
		if (!empty($_REQUEST['password']))
		{
			$password = md5($_REQUEST['password']);
		}
		else
		{
			$password = '';
		}
		
		if (!empty($_REQUEST['convert_newlines']) && $_REQUEST['convert_newlines'] === 1)
		{
			$convert_newlines = $_REQUEST['convert_newlines'];
		}
		else
		{
			$convert_newlines = 0;
		}
		
		// Gigabyte
		if ($_REQUEST['filesize_format'] == 'gb')
		{
			$filesize = floatval($_REQUEST['size'] * pow(1024,3));
		}
		// Megabyte
		elseif ($_REQUEST['filesize_format'] == 'mb')
		{
			$filesize = floatval($_REQUEST['size'] * pow(1024,2));
		}
		// Kilobyte
		elseif ($_REQUEST['filesize_format'] == 'kb')
		{
			$filesize = floatval($_REQUEST['size'] * 1024);
		}
		// Byte, or otherwise
		else
		{
			$filesize = floatval($_REQUEST['size']);
		}
		
		// Get the activation time
		$activate_at = mktime($_REQUEST['hour'], $_REQUEST['minute'], 0, $_REQUEST['month'], $_REQUEST['day'], $_REQUEST['year']);
		
		$dbim->query('INSERT INTO '.DB_PREFIX.'files
						SET category_id = "'.$_REQUEST['category'].'", 
							name = "'.$_REQUEST['name'].'", 
							description_small = "'.$_REQUEST['description_small'].'", 
							description_big = "'.$_REQUEST['description_big'].'", 
							downloads = "'.$_REQUEST['downloads'].'",
							views = "'.$_REQUEST['views'].'", 
							size = "'.$filesize.'", 
							date = "'.time().'",
							agreement_id = "'.$_REQUEST['agreement'].'",
							password = "'.$password.'",
							status = 1,
							convert_newlines = '.$convert_newlines.',
							keywords = "'.$_REQUEST['keywords'].'",
							activate_at = "'.$activate_at.'"');
		
		$file_id = $dbim->insert_id();
		
		// Add the new custom field value
		for ($i = 1; $i <= $_REQUEST['custom_field_total']; $i++)
		{										
			if (!empty($_REQUEST['custom_field_'.$i.'_value']))
			{
				validate_types($_REQUEST, array('custom_field_'.$i.'_value' => 'STR_HTML'));
				
				// Add
				$dbim->query('INSERT INTO '.DB_PREFIX.'customfields_data
								SET field_id = '.$_REQUEST['custom_field_'.$i.'_field_id'].',
									file_id = '.$file_id.',
									value = "'.$_REQUEST['custom_field_'.$i.'_value'].'"');
			}
		}
		
		if (isset($_REQUEST['upload']))
		{
			// Hmm, fire might result if the file is available to users with no actual download
			$dbim->query('UPDATE '.DB_PREFIX.'files
							SET status = 0
							WHERE (id = '.$file_id.')');
				
			// Upload the file then jim..
			// Template
			$add_file = $uim->fetch_template('admin/files_upload_file');
			$add_file->assign_var('id', $file_id);
		}
		else
		{
			for ($i = 1; $i <= $site_config['mirrors']; $i++)
			{
				validate_types($_REQUEST, array('mirror'.$i.'_name' => 'STR', 'mirror'.$i.'_location' => 'STR', 'mirror'.$i.'_url' => 'STR'));
					
				if (!empty($_REQUEST['mirror'.$i.'_name']) && !empty($_REQUEST['mirror'.$i.'_location'])&& !empty($_REQUEST['mirror'.$i.'_url']))
				{
					$dbim->query('INSERT INTO '.DB_PREFIX.'mirrors
									SET file_id = '.$file_id.', 
										name = "'.$_REQUEST['mirror'.$i.'_name'].'", 
										location = "'.$_REQUEST['mirror'.$i.'_location'].'", 
										url = "'.$_REQUEST['mirror'.$i.'_url'].'"');
				}
			}			
					
			// Template
			$add_file = $uim->fetch_template('admin/files_add_file');
			
			$success = true; // For redirect EOF
			$add_file->assign_var('id', $file_id);
			$add_file->assign_var('success', true);
		}
	}
	
	if (!isset($add_file))
	{
		// Template
		$add_file = $uim->fetch_template('admin/files_add_file');
	}
			
	$fcm->generate_category_list($add_file, 'category', 'cats');
	
	// Get current date/time, substitute placeholder and assign to template
	$date_message = $lm->language('admin', 'server_time');
	$date_message = str_replace('_TIME_', date('r'), $date_message);
	$add_file->assign_var('date_message', $date_message);
	
	// Get the agreements
	$agreements_result = $dbim->query('SELECT id, name, contents
										FROM '.DB_PREFIX.'agreements');
										
	while ($agreement = $dbim->fetch_array($agreements_result))
	{
		$add_file->assign_var('agreement', $agreement);
		$add_file->use_block('agreements');
	}
	
	// Custom fields
	$custom_query = $dbim->query('SELECT id, label, value
									FROM '.DB_PREFIX.'customfields');
	$rows = $dbim->num_rows($custom_query);		
	$add_file->assign_var('custom_field_total', $rows);		
	$id = 1;
	while ($custom_fields = $dbim->fetch_array($custom_query))
	{
		$custom_fields['uid'] = $id;
		$add_file->assign_var('custom_field', $custom_fields);
		$add_file->use_block('custom_fields');
		$id++;
	}
	
	// Show mirror entry forms
	$mirror_amount = $site_config['mirrors'];
	$current_mirror = 1;
	
	while ($current_mirror <= $mirror_amount)
	{
		$add_file->assign_var('mirror', $current_mirror);
		$add_file->use_block('mirror');
		
		$current_mirror++;
	}
	
	// Use FCKeditor or not?
	if (use_fckeditor())
	{
		$add_file->assign_var('use_fckeditor', true);
		
		// Module
		include_once ('FCKeditor/fckeditor.php');
		
		// Small description
		$fck_desc_small = new FCKeditor('description_small');
		$fck_desc_small->BasePath = $site_config['url'].'FCKeditor/';
		$fck_desc_small->ToolbarSet = 'od';
		$fck_desc_small->Width = '90%';
		$fck_desc_small->Height = '200';
		$desc_small_html = $fck_desc_small->CreateHtml();
		$add_file->assign_var('desc_small_html', $desc_small_html);
		
		// Big description
		$fck_desc_big = new FCKeditor('description_big');
		$fck_desc_big->BasePath = $site_config['url'].'FCKeditor/';
		$fck_desc_big->ToolbarSet = 'od';
		$fck_desc_big->Width = '90%';
		$fck_desc_big->Height = '200';
		$desc_big_html = $fck_desc_big->CreateHtml();
		$add_file->assign_var('desc_big_html', $desc_big_html);
	}
	else
	{
		$add_file->assign_var('use_fckeditor', false);
	}
	
	// Get next 2 years
	$years[] = intval(date('Y'));
	$years[] = intval(date('Y') + 1);
	$years[] = intval(date('Y') + 2);
	
	foreach ($years as $year)
	{
		$add_file->assign_var('year', $year);
		$add_file->use_block('year_select');
	}
	
	$add_file->assign_var('filesize_format', $site_config['filesize_format']);
	$add_file->show();
}
else
{
	// User is not permitted
	$no_permission = $uim->fetch_template('admin/no_permission');
	$no_permission->show();
}
		
$end = $uim->fetch_template('global/end');
$end->show();
		
if (!isset($success) || !$success)
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'file_add'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'file_add'), 'admin.php?cmd=files_add_file');
}
?>