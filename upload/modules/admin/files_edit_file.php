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
	
if ($uam->permitted('acp_files_edit_file'))
{	
	validate_types($_REQUEST, array('action' => 'STR', 'file_id' => 'INT', 'mirror_id' => 'INT', 'name' => 'STR', 
									'description_small' => 'STR_HTML', 'description_big' => 'STR_HTML', 'category' => 'INT',
									'downloads' => 'INT', 'views' => 'INT', 'size' => 'FLOAT', 'rating_votes' => 'INT',
									'rating_value' => 'STR', 'agreement' => 'INT', 'password' => 'STR', 'date' => 'STR', 
									'status' => 'INT', 'mirror_edit_type' => 'INT', 'files_for' => 'STR', 
									'file_order_primary' => 'STR', 'file_order_secondary' => 'STR',
									'convert_newlines' => 'INT', 'filesize_format' => 'STR', 'keywords' => 'STR', 
									'change_pass' => 'STR', 'password_confirm' => 'STR', 'day' => 'INT', 'month' => 'INT',
									'year' => 'INT', 'hour' => 'INT', 'minute' => 'INT'));
	
	if ($_REQUEST['action'] == 'file_edit' && !empty($_REQUEST['file_id']))
	{
		// Template
		$edit_file = $uim->fetch_template('admin/files_edit_file');
		
		// Has the password changed in any way?
		if ($_REQUEST['change_pass'] == 'change')
		{
			if ($_REQUEST['password'] != $_REQUEST['password_confirm'])
			{
				$error = $lm->language('admin', 'passwords_not_match');
			}
			else
			{
				$password_line = 'password = "'.md5($_REQUEST['password']).'",';
			}
		}
		elseif ($_REQUEST['change_pass'] == 'erase')
		{
			$password_line = 'password = "",';
		}
		else
		{
			$password_line = '';
		}
		
		if (!empty($_REQUEST['convert_newlines']) && $_REQUEST['convert_newlines'] === 1)
		{
			$convert_newlines = $_REQUEST['convert_newlines'];
		}
		else
		{
			$convert_newlines = 0;
		}
		
		// Put the date back into a correct format
		$date_parts = explode('/', $_REQUEST['date']);
		$date = mktime(0,0,0,$date_parts['1'], $date_parts['0'], $date_parts['2']);
		
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
		
		if (empty($error))
		{
			$dbim->query('UPDATE '.DB_PREFIX.'files
							SET category_id = "'.$_REQUEST['category'].'", 
								name = "'.$_REQUEST['name'].'", 
								description_small = "'.$_REQUEST['description_small'].'", 
								description_big = "'.$_REQUEST['description_big'].'", 
								downloads = "'.$_REQUEST['downloads'].'", 
								views = "'.$_REQUEST['views'].'",
								size = "'.$filesize.'", 
								agreement_id = "'.$_REQUEST['agreement'].'",
								rating_votes = "'.$_REQUEST['rating_votes'].'", 
								rating_value = "'.$_REQUEST['rating_value'].'",
								'.$password_line.'
								date = "'.$date.'",
								status = "'.$_REQUEST['status'].'",
								convert_newlines = "'.$convert_newlines.'",
								keywords = "'.$_REQUEST['keywords'].'",
								activate_at = "'.$activate_at.'"
							WHERE (id = '.$_REQUEST['file_id'].')');
			
			// Add the new custom field value
			for ($i = 1; $i <= $_REQUEST['custom_field_total']; $i++)
			{
				// Delete it if it wasn't set
				if (!empty($_REQUEST['custom_field_'.$i.'_value']))
				{																
					validate_types($_REQUEST, array('custom_field_'.$i.'_value' => 'STR_HTML'));
					
					if (!empty($_REQUEST['custom_field_'.$i.'_id']))
					{
						// Update
						$dbim->query('UPDATE '.DB_PREFIX.'customfields_data
										SET value = "'.$_REQUEST['custom_field_'.$i.'_value'].'"
										WHERE (id = '.$_REQUEST['custom_field_'.$i.'_id'].')');					
						
					}
					else
					{
						// Add
						$dbim->query('INSERT INTO '.DB_PREFIX.'customfields_data
										SET field_id = '.$_REQUEST['custom_field_'.$i.'_field_id'].',
											file_id = '.$_REQUEST['file_id'].',
											value = "'.$_REQUEST['custom_field_'.$i.'_value'].'"');
					}
				}
				else
				{
					if (!empty($_REQUEST['custom_field_'.$i.'_id']))
					{
						$dbim->query('DELETE FROM '.DB_PREFIX.'customfields_data
										WHERE (id = '.$_REQUEST['custom_field_'.$i.'_id'].')');
					}
				}
			}
		
			// Do we do mirrors?
			if (!empty($_REQUEST['mirror_edit_type']) && $_REQUEST['mirror_edit_type'] == 1)
			{
				// Existing mirrors - update/delete
				foreach($_POST['mirror_existing'] as $id => $details)
				{
					validate_types($details, array('name' => 'STR', 'location' => 'STR',
													'url' => 'STR', 'delete' => 'INT'));
					$mirror_id = intval($id);
					
					if (intval($details['delete']) == 1)
					{
						// Delete
						$dbim->query('DELETE FROM '.DB_PREFIX.'mirrors
										WHERE id = '.$mirror_id);
					}
					elseif (($details['name'] != '') && ($details['location'] != '') && ($details['url'] != ''))
					{
						// All is good, so update
						$dbim->query('UPDATE '.DB_PREFIX.'mirrors
										SET name = "'.$details['name'].'",
											location = "'.$details['location'].'",
											url = "'.$details['url'].'"
										WHERE id = '.$mirror_id);
					}
					elseif (!(($details['name'] == '') && ($details['location'] == '') && ($details['url'] == '')) 
							&& (($details['name'] == '') || ($details['location'] == '') || ($details['url'] == '')))
					{
						// Mirror = bad, so add it to bad mirror array
						$data = array(
							'id' => $mirror_id,
							'name' => $details['name'],
							'location' => $details['location'],
							'url' => $details['url']
						);
						
						$error_mirrors['existing_id'][] = $mirror_id;
						$error_mirrors['existing'][] = $data;
						$error = true;
					}
				}
				
				// New mirrors - add
				foreach($_POST['mirror_new'] as $details)
				{
					validate_types($details, array('name' => 'STR', 'location' => 'STR',
													'url' => 'STR', 'delete' => 'INT'));
					
					// Not bothered if all fields are blank
					if (($details['name'] != '') && ($details['location'] != '') && 
						($details['url'] != ''))
					{
						// All is good, so update
						$dbim->query('INSERT INTO '.DB_PREFIX.'mirrors
										SET name = "'.$details['name'].'",
											location = "'.$details['location'].'",
											url = "'.$details['url'].'",
											file_id = '.$_REQUEST['file_id']);
					}
					elseif (!(($details['name'] == '') && ($details['location'] == '') 
							&& ($details['url'] == '')) && (($details['name'] == '') || 
							($details['location'] == '') || ($details['url'] == '')))
					{
						// Mirror = bad, so add it to bad mirror array
						$data = array(
							'name' => $details['name'],
							'location' => $details['location'],
							'url' => $details['url']
						);
						
						$error_mirrors['new'][] = $data;
						$error = true;
					}
				}
			}
										
			$edit_file->assign_var('id', $_REQUEST['file_id']);
			
			$edit_file->assign_var('success', true);
			$success = true; // EOF redirect
			
			// No need to show the form as there aren't any errors
			$show_form = false;
		}
		else
		{
			$edit_file->assign_var('success', false);
			$success = false; // EOF redirect
			
			$edit_file->assign_var('error', $error);
			
			// Show the file edit form again
			$show_form = true;
		}
		
		$show_listing = false;
	}
	
	if (($_REQUEST['action'] == 'file_select' && !empty($_REQUEST['file_id'])) || (!empty($show_form) && $show_form === true && !empty($_REQUEST['file_id'])))
	{
		if (empty($edit_file))
		{
			$edit_file = $uim->fetch_template('admin/files_edit_file');
		}
		
		// Output
		$file_result = $dbim->query('SELECT id, category_id, name, description_small, description_big, downloads, views, size, agreement_id, rating_votes, rating_value, password, date, status , convert_newlines, keywords, activate_at 
										FROM '.DB_PREFIX.'files
										WHERE (id = '.$_REQUEST['file_id'].')');	
		$file_row = $dbim->fetch_array($file_result);
		
		// Put the date in the correct format
		$file_row['date'] = date('d/m/Y',$file_row['date']);
		
		$filesize = $fldm->format_size($file_row['size']);
		
		$file_row['size'] = $filesize['size'];
		
		if ($file_row['activate_at'] > 0)
		{
			$file_row['activate_at_day'] = intval(date('d', $file_row['activate_at']));
			$file_row['activate_at_month'] = intval(date('m', $file_row['activate_at']));
			$file_row['activate_at_year'] = intval(date('Y', $file_row['activate_at']));
			$file_row['activate_at_hour'] = intval(date('H', $file_row['activate_at']));
			$file_row['activate_at_minute'] = intval(date('i', $file_row['activate_at']));
		}
		else 
		{
			$file_row['activate_at_day'] = 0;
			$file_row['activate_at_month'] = 0;
			$file_row['activate_at_year'] = 0;
			$file_row['activate_at_hour'] = 0;
			$file_row['activate_at_minute'] = 0;
		}
		
		// Build year list
		$years[] = intval(date('Y'));
		$years[] = intval(date('Y') + 1);
		$years[] = intval(date('Y') + 2);
		
		foreach ($years as $year)
		{
			$edit_file->assign_var('year', $year);
			$edit_file->use_block('year_select');
		}
		
		// Get current date/time, substitute placeholder and assign to template
		$date_message = $lm->language('admin', 'server_time');
		$date_message = str_replace('_TIME_', date('r'), $date_message);
		$edit_file->assign_var('date_message', $date_message);
		
		$edit_file->assign_var('file', $file_row);
		$edit_file->assign_var('filesize_format', $filesize['unit']);
		$edit_file->assign_var('filesize_format_lc', strtolower($filesize['unit']));
		
		if (!is_null($file_row['category_id']) && $file_row['category_id'] != 0)
		{	
			// Get current category name
			$category_result = $dbim->query('SELECT id, name
												FROM '.DB_PREFIX.'categories
												WHERE (id = '.$file_row['category_id'].')');		
			$category_row = $dbim->fetch_array($category_result);
		}
		else
		{
			$category_row['name'] = $lm->language('admin', 'no_category');
		}
		
		$edit_file->assign_var('current_category_name', $category_row['name']);
		
		// Get current agreement name
		$agreement_result = $dbim->query('SELECT id, name
											FROM '.DB_PREFIX.'agreements
											WHERE (id = '.$file_row['agreement_id'].')');		
		$agreement_row = $dbim->fetch_array($agreement_result);
		
		if ($dbim->num_rows($agreement_result) == 0)
		{
			$edit_file->assign_var('current_agreement_name', $lm->language('admin', 'none'));
		}
		else
		{
			$edit_file->assign_var('current_agreement_name', $agreement_row['name']);
		}
		
		// Generate category list
 		$fcm->generate_category_list($edit_file, 'category', 'cats');
		
		// Get the agreements
		$agreements_result = $dbim->query('SELECT id, name, contents
											FROM '.DB_PREFIX.'agreements');
											
		while ($agreement = $dbim->fetch_array($agreements_result))
		{
			$edit_file->assign_var('agreement', $agreement);
			$edit_file->use_block('agreements');
		}
		
		// Custom fields:
		// 1. Get a list of fields:
		$custom_query = $dbim->query('SELECT id, label
										FROM '.DB_PREFIX.'customfields');
		
		$id = 1;
		while ($custom_fields = $dbim->fetch_array($custom_query))
		{
			// 2. Get the value (if any) for the current field
			$cf_value_query = $dbim->query('SELECT id, field_id, value 
											FROM '.DB_PREFIX.'customfields_data
											WHERE (field_id = '.$custom_fields['id'].')
												AND (file_id = '.$_REQUEST['file_id'].')');
			
			$cf_value_array = $dbim->fetch_array($cf_value_query);
			
			// 3. Build an array of the current field's id, value and label
			$customfield_data = array(
					'label' => $custom_fields['label'],
					'id' =>  $cf_value_array['id'],
					'uid' =>  $id,
					'field_id' => $custom_fields['id'],
					'value' => $cf_value_array['value']
			);
			
			// 4. Assign to template and "use" the block
			$edit_file->assign_var('custom_field', $customfield_data);
			$edit_file->use_block('custom_fields');
			
			$id++;
		}	
			
		$edit_file->assign_var('custom_field_total', $id-1);
		
		// Show mirror edit forms for good mirrors
		if (!empty($error_mirrors['existing_id']) && is_array($error_mirrors['existing_id']))
		{
			$good_mirror_list = implode(', ', $error_mirrors['existing_id']);
			$good_mirror_sql = 'AND (id NOT IN('.$good_mirror_list.'))';
		}
		else
		{
			$good_mirror_sql = '';
		}
		
		$mirrors_result = $dbim->query('SELECT id, file_id, name, location, url
										FROM '.DB_PREFIX.'mirrors
										WHERE (file_id = '.$_REQUEST['file_id'].')'.$good_mirror_sql);
											
		while ($mirror = $dbim->fetch_array($mirrors_result))
		{
			$edit_file->assign_var('mirror', $mirror);
			$edit_file->use_block('mirror_edit');
		}
		
		// Bad existing mirrors
		if (!empty($error_mirrors['existing_id']) && is_array($error_mirrors['existing']))
		{
			foreach ($error_mirrors['existing'] as $mirror)
			{
				$mirror_prefix = 'mirror_existing['.$mirror['id'].']';
				$mirror_id_prefix = 'mirror_existing_'.$mirror['id'];
				
				$edit_file->assign_var('mirror_prefix', $mirror_prefix);
				$edit_file->assign_var('mirror_id_prefix', $mirror_id_sdprefix);
				
				$edit_file->assign_var('mirror_existing', true);
				
				$edit_file->assign_var('mirror', $mirror);
				$edit_file->use_block('mirror_bad');
			}
		}
		
		// Bad new mirrors
		$current_mirror_id = 1;
		if (!empty($error_mirrors['new']) && is_array($error_mirrors['new']))
		{
			foreach ($error_mirrors['new'] as $mirror)
			{
				$mirror_prefix = 'mirror_new['.$current_mirror_id.']';
				$mirror_id_prefix = 'mirror_new_'.$current_mirror_id;
				
				$edit_file->assign_var('mirror_prefix', $mirror_prefix);
				$edit_file->assign_var('mirror_id_prefix', $mirror_id_prefix);
				
				$edit_file->assign_var('mirror_existing', false);
				
				$edit_file->assign_var('mirror', $mirror);
				$edit_file->use_block('mirror_bad');
				
				$current_mirror_id++;
			}
		}
		
		// Show mirror add forms
		$mirror_amount = $site_config['mirrors'];
		
		for ($i = 1; $i <= $mirror_amount; $i++)
		{
			$edit_file->assign_var('mirror', $current_mirror_id);
			$edit_file->use_block('mirror_add');
			
			$current_mirror_id++;
		}
		
		// Get comments
		$comments_result = $dbim->query('SELECT id, file_id, timestamp, name, email, comment, status
											FROM '.DB_PREFIX.'comments
											WHERE (file_id = '.$_REQUEST['file_id'].')
												AND (status = 1)
											ORDER BY timestamp ASC');
		
		// Yoo-hoo, anyone there?
		if ($dbim->num_rows($comments_result) != 0)
		{
			while ($comment = $dbim->fetch_array($comments_result))
			{		
				// Get details of file comment belongs to				
				$edit_file->assign_var('comment', $comment);
				$edit_file->assign_var('date', format_date($comment['timestamp']));
				$edit_file->assign_var('time', date('G:i:s', $comment['timestamp']));
					
				// fCode Formatting
				// [b] - bold
				$comment['comment'] = str_replace('[b]', '<strong>',$comment['comment']);
				$comment['comment'] = str_replace('[/b]', '</strong>',$comment['comment']);
				// [u] - underline
				$comment['comment'] = str_replace('[u]', '<ins>',$comment['comment']);
				$comment['comment'] = str_replace('[/u]', '</ins>',$comment['comment']);
				// [i] - italic
				$comment['comment'] = str_replace('[i]', '<em>',$comment['comment']);
				$comment['comment'] = str_replace('[/i]', '</em>',$comment['comment']);
				// [url] - urls
				$comment['comment'] = preg_replace('/\[url\](.*)\[\/url\]/si','<a href="$1">$1</a>',$comment['comment']);
					
				$edit_file->assign_var('text', nl2br($comment['comment']));
				$edit_file->use_block('comment');
			}
		}
		else
		{		
			// Where did everyone go?
			$edit_file->assign_var('comment_empty', true);
		}
		
		// FCKeditor?
		if (use_fckeditor())
		{
			$edit_file->assign_var('use_fckeditor', true);
			
			// Module
			include_once ('FCKeditor/fckeditor.php');
			
			// Small description
			$fck_desc_small = new FCKeditor('description_small');
			$fck_desc_small->BasePath = $site_config['url'].'FCKeditor/';
			$fck_desc_small->ToolbarSet = 'od';
			$fck_desc_small->Width = '90%';
			$fck_desc_small->Height = '200';
			$fck_desc_small->Value = $file_row['description_small'];
			$desc_small_html = $fck_desc_small->CreateHtml();
			$edit_file->assign_var('desc_small_html', $desc_small_html);
			
			// Big description
			$fck_desc_big = new FCKeditor('description_big');
			$fck_desc_big->BasePath = $site_config['url'].'FCKeditor/';
			$fck_desc_big->ToolbarSet = 'od';
			$fck_desc_big->Width = '90%';
			$fck_desc_big->Height = '200';
			$fck_desc_big->Value = $file_row['description_big'];
			$desc_big_html = $fck_desc_big->CreateHtml();
			$edit_file->assign_var('desc_big_html', $desc_big_html);
		}
		else
		{
			$edit_file->assign_var('use_fckeditor', false);
		}
	}
	elseif ($_REQUEST['action'] == 'mirrors_delete' && !empty($_REQUEST['mirror_id']) && !empty($_REQUEST['file_id']))
	{
		// Template
		$edit_file = $uim->fetch_template('admin/files_edit_file');
		
		$dbim->query('DELETE FROM '.DB_PREFIX.'mirrors
						WHERE (id = '.$_REQUEST['mirror_id'].')
						LIMIT 1');
		
		$success = true; // For redirect EOF
		$edit_file->assign_var('id', $_REQUEST['file_id']);
		$edit_file->assign_var('success', true);
	}
	elseif ($_REQUEST['action'] == 'mirrors_edit' && !empty($_REQUEST['mirror_id']) && !empty($_REQUEST['file_id']))
	{
		// Template
		$edit_file = $uim->fetch_template('admin/files_edit_file');
		
		$dbim->query('UPDATE '.DB_PREFIX.'mirrors
						SET name = "'.$_REQUEST['name'].'", 
							location = "'.$_REQUEST['location'].'", 
							url = "'.$_REQUEST['url'].'"
						WHERE (id = '.$_REQUEST['mirror_id'].')
						LIMIT 1');
		
		$success = true; // For redirect EOF
		$edit_file->assign_var('id', $_REQUEST['file_id']);
		$edit_file->assign_var('success', true);
	}
	elseif ($_REQUEST['action'] == 'mirrors_add' && !empty($_REQUEST['file_id']))
	{
		// Template
		$edit_file = $uim->fetch_template('admin/files_edit_file');
		
		for ($i = 1; $i <= $site_config['mirrors']; $i++)
		{
			validate_types($_REQUEST, array('mirror'.$i.'_name' => 'STR', 'mirror'.$i.'_location' => 'STR', 'mirror'.$i.'_url' => 'STR'));
			
			if (!empty($_REQUEST['mirror'.$i.'_name']) && !empty($_REQUEST['mirror'.$i.'_location']) && !empty($_REQUEST['mirror'.$i.'_url']))
			{
				$dbim->query('INSERT INTO '.DB_PREFIX.'mirrors
								SET file_id = '.$_REQUEST['file_id'].', 
									name = "'.$_REQUEST['mirror'.$i.'_name'].'", 
									location = "'.$_REQUEST['mirror'.$i.'_location'].'", 
									url = "'.$_REQUEST['mirror'.$i.'_url'].'"');
			}
		}
		
		$success = true; // For redirect EOF
		$edit_file->assign_var('id', $_REQUEST['file_id']);
		$edit_file->assign_var('success', true);
	}
	else
	{
		if (!isset($show_listing) || $show_listing !== false)
		{
			// Include module
			require_once('modules/core/listings.php');
			$listing = new listing();
			
			$cat_heirachy = $listing->filter_cats(false, $listing->cat_heirachy);
			
			// Categories to expand?
			if (!empty($_REQUEST['files_for']) || $_REQUEST['files_for'] == '0')
			{
				$files_for = $_REQUEST['files_for'];
				// Tidy it up
				$listing->trim_files_for($files_for);
				
				// Explode... boom!
				$files_for = explode(',', $files_for);
			}
			else
			{
				$files_for = false;
			}
			
			// Link for files
			$file_link = array(
				'link' => 'admin.php',
				'query' => 'cmd=files_edit_file&amp;action=file_select&amp;file_id=#file_id#'
				);
			
			// Query string
			$self_query = 'cmd='.$_REQUEST['cmd'];
			
			// Build listing
			$edit_file = $listing->list_cat_file_div($self_query, false, $file_link, $cat_heirachy, $files_for);
			
			// Header and text...
			$text = $lm->language('admin', 'file_edit_select_desc').'. '.$lm->language('admin', 'list_expand_collapse');
			$edit_file->assign_var('title', $lm->language('admin', 'file_edit'));
			$edit_file->assign_var('text', $text);
			
			if ($fcm->count_files(0))
			{
				// Private/hidden files
				$cat_level = array(0);
				$private_files = $listing->list_cat_file_div($self_query, false, $file_link, $cat_level, $files_for);
				$private_category = $private_files->show(true);
				
				// Assign it
				$edit_file->assign_var('private_category', $private_category);
			}
		}
	}
	
	$edit_file->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'file_edit'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'file_edit'), 'admin.php?cmd=files_edit_file');
}
?>