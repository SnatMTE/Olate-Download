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

if ($uam->permitted('acp_files_approve_files'))
{
	if (isset($_REQUEST['submit']))
	{
		$approve_files = $uim->fetch_template('admin/files_approve_files');
		
		foreach ($_REQUEST as $key => $value)
		{
			if (strpos($key, 'file_') !== false)
			{
				$id = intval(str_replace('file_', '', $key));
				
				if (intval($value) == 1)
				{
					// Update in database
					$result = $dbim->query('UPDATE '.DB_PREFIX.'files
											SET status = 1
											WHERE id = '.$id);
					
					if (!$result)
					{
						$success = false;
					}
				}
			}
		}
		
		if (isset($success) && $success === false)
		{
			$approve_files->assign_var('success', false);
		}
		else
		{
			$success = true;
			$approve_files->assign_var('success', true);
		}
	}
	else
	{
		// Main template that has the form and other stuff in
		$approve_files = $uim->fetch_template('admin/files_approve_files');
		
		$sql = 'SELECT COUNT(*) AS count 
				FROM '.DB_PREFIX.'files 
				WHERE status = 0';
		
		$result = $dbim->query($sql);
		$row = $dbim->fetch_array($result);
		
		if ($row['count'] > 0)
		{
			// Include module
			require_once('modules/core/listings.php');
			$listing = new listing();
			
			$files_hide = false;
			
			// WHERE clause to only show unavailable files
			$listing->where_file = 'status = 0';
			
			// Query string
			$self_query = 'cmd='.$_REQUEST['cmd'];
			
			// Filter categories to only get ones with inactive files
			$filtered_categories = $listing->filter_cats($listing->where_file, false, true);
			
			// Build listing
			$approve_files_list = $listing->list_cat_file_check($self_query, $filtered_categories, false, false);
			
			// Private/hidden files
			$private_file_count = $fcm->count_files(0, $listing->where_file);
			
			if ($private_file_count > 0)
			{
				$cat_level = array(0);
				$private_files = $listing->list_cat_file_check($self_query, $cat_level, $files_hide, $file_link);
				$private_category = $private_files->show(true);
				
				// Assign it
				$approve_files_list->assign_var('private_category', $private_category);
			}
			
			$approve_files_list_final = $approve_files_list->show(true);
			
			// Assign list to main template
			$approve_files->assign_var('file_list', $approve_files_list_final);
		}
		else
		{
			$approve_files->assign_var('has_files', false);
		}
	}
	
	// Show template
	$approve_files->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'files_approve'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'files_approve'), 'admin.php?cmd=files_approve_files');
}
?>