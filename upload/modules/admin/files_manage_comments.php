<?php
/**********************************
* Olate Download 3.5.0
* https://github.com/SnatMTE/Olate-Download/
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 197 $
* @package od
*
* Original Author: Olate Download
* Updated by: Snat
* Last-Edited: 2025-12-16
*/

// Start admin cp
$start = $uim->fetch_template('admin/start');
$start->show();

if ($uam->permitted('acp_files_manage_comments'))
{	
	validate_types($_REQUEST, array('search' => 'INT', 'date' => 'STR', 'comment_id' => 'INT', 'file_id' => 'INT', 
									'name' => 'STR', 'email' => 'STR', 'status' => 'INT',
									'perform' => 'INT', 'action' => 'INT'));
									 
	// Has the user submitted a search request?
	if (isset($_REQUEST['search']))
	{
		$template_manage = $uim->fetch_template('admin/files_manage_comments');
		
		// Initialise arrays
		$sql_conditions = array();
		$sql_params = array();
		
		// Specified comment id?
		if (!empty($_REQUEST['comment_id']))
		{
			$sql_conditions[] = '(id = ?)';
			$sql_params[] = $_REQUEST['comment_id'];
		}
		
		// Specified a file ID?
		if (!empty($_REQUEST['file_id']) && $_REQUEST['file_id'] != '')
		{
			$sql_conditions[] = '(file_id = ?)';
			$sql_params[] = $_REQUEST['file_id'];
		}
		
		// Specified a date?
		if (!empty($_REQUEST['date']) && substr_count($_REQUEST['date'], '/') == 3)
		{
			$date_parts = explode('/', $_REQUEST['date']);
			$timestamp = mktime(0, 0, 0, $date_parts['1'], $date_parts['0'], $date_parts['2']);
			
			$sql_conditions[] = '(timestamp >= ?)';
			$sql_params[] = $timestamp;
		}
		
		// Specified a name?
		if (!empty($_REQUEST['name']))
		{
			$sql_conditions[] = '(name LIKE ?)';
			$sql_params[] = $_REQUEST['name'];
		}
		
		// Specified an email address?
		if (!empty($_REQUEST['email']))
		{
			$sql_conditions[] = '(email LIKE ?)';
			$sql_params[] = $_REQUEST['email'];
		}
		
		// Specified an email address?
		if (!empty($_REQUEST['status']))
		{
			$sql_conditions[] = '(status = ?)';
			$sql_params[] = $_REQUEST['status'];
		}
		
		// Search database
		$search_sql = 'SELECT id, file_id, timestamp, name, email, status
										FROM '.DB_PREFIX.'comments';
		
		// Are there any search conditions?
		if (sizeof($sql_conditions) > 0)
		{
			$search_sql .= ' WHERE '.implode(' OR ', $sql_conditions);
		}
		
		// Add sorting on the end
		$search_sql .= ' ORDER BY timestamp ASC';
		
		$search_result = $dbim->pquery($search_sql, $sql_params);
															
		if ($dbim->num_rows_p($search_result) >= 1)
		{
			$comments = array();
			
			while ($comment = $dbim->fetch_array_p($search_result))
			{			
				// Format the date
				$comment['timestamp'] = format_date($comment['timestamp']);			
				
				// Format status
				if ($comment['status'] == 1)
				{
					$comment['status'] = $lm->language('admin', 'approved');
				}
				else
				{
					$comment['status'] = $lm->language('admin', 'unapproved');
				}
				
				$comments[] = $comment;
			}
			
			// Assiging
			foreach ($comments as $comment)
			{		
				// Lookup owner file
				$file = $fldm->get_details($comment['file_id']);
								
				$template_manage->assign_var('file', $file);
				$template_manage->assign_var('comment', $comment);
				$template_manage->use_block('comments');
			}
			
			// Result count
			$template_manage->assign_var('result_count', $dbim->num_rows_p($search_result));
		}
		else
		{
			$template_manage->assign_var('no_results', true);
		}
		
		$template_manage->show();
	}
	elseif ($_REQUEST['perform'] == 1)
	{
		$template_manage = $uim->fetch_template('admin/files_manage_comments_action');
		
		// What does the user want to do?
		switch ($_REQUEST['action'])
		{
			// Delete
			case 1:
				foreach ($_POST['comment'] as $comment)
				{
					$delete = $dbim->pquery('DELETE FROM '.DB_PREFIX.'comments
											WHERE (id = ?)',
											array(intval($comment)));
				}
				$template_manage->assign_var('action', 1);
				$success = true;
				break;
			// Unapprove
			case 2:
				foreach ($_POST['comment'] as $comment)
				{
					$dbim->pquery('UPDATE '.DB_PREFIX.'comments
											SET status = 0
											WHERE (id = ?)',
											array(intval($comment)));
				}
				$template_manage->assign_var('action', 2);
				$success = true;
				break;
			// Approve
			case 3:
				foreach ($_POST['comment'] as $comment)
				{
					$dbim->pquery('UPDATE '.DB_PREFIX.'comments
											SET status = 1
											WHERE (id = ?)',
											array(intval($comment)));
				}
				$template_manage->assign_var('action', 3);
				$success = true;
				break;
			default:
				$template_manage->assign_var('action', 0);
				break;
		}
		
		$template_manage->show();
	}
	else
	{
		$template_search = $uim->fetch_template('admin/files_manage_comments_search');
		
		// Show all files
		$files_result = $dbim->pquery('SELECT id, name, category_id, description_small, description_big, downloads, size, date 
										FROM '.DB_PREFIX.'files
										ORDER BY name DESC', array());
		
		$files = array();
		
		while ($file = $dbim->fetch_array_p($files_result))
		{
			// If set, get the category details
			if ($file['category_id'] != 0)
			{
				$category = $fcm->get_cat($file['category_id']);
				$file['cat_name'] = $category['name'];
			}
			else
			{
				$file['cat_name'] = $lm->language('admin', 'private_files');
			}
						
			$files[] = $file;
		}
					
		foreach ($files as $file)
		{
			$template_search->assign_var('file', $file);
			$template_search->use_block('files');
		}
		$template_search->show();
	}
	
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'comments').' - '.$lm->language('admin', 'comments_manage'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'comments').' - '.$lm->language('admin', 'comments_manage'), 'admin.php?cmd=files_manage_comments');
}
?>