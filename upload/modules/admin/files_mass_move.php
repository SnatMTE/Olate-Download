<?php
/**********************************
* Olate Download 3.5.0
* https://github.com/SnatMTE/Olate-Download/
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 141 $
* @package od
*
* Original Author: Olate Download
* Updated by: Snat
* Last-Edited: 2025-12-16
*/

// Start admin cp
$start = $uim->fetch_template('admin/start');
$start->show();

if ($uam->permitted('acp_files_mass_move'))
{
	validate_types($_REQUEST, array('source_id' => 'INT', 'dest_id' => 'INT'));
	
	$template = $uim->fetch_template('admin/files_mass_move');
	
	if (isset($_REQUEST['submit']))
	{
		if (!isset($_REQUEST['source_id']) || !isset($_REQUEST['dest_id']))
		{
			$template->assign_var('error', $lm->language('admin', 'must_specify_source_dest'));
		}
		elseif (!$fcm->get_cat(intval($_REQUEST['source_id'])))
		{
			$template->assign_var('error', $lm->language('admin', 'invalid_category'));
		}
		elseif (!$fcm->get_cat(intval($_REQUEST['dest_id'])))
		{
			$template->assign_var('error', $lm->language('admin', 'invalid_category'));
		}
		else
		{
			// All is good so let's try and move the files
			$dbim->query('UPDATE '.DB_PREFIX.'files 
							SET category_id = '.intval($_REQUEST['dest_id']).'
							WHERE category_id = '.intval($_REQUEST['source_id']));
			
			$success = true;
			$template->assign_var('success', true);
			$template->assign_var('message', $lm->language('admin', 'files_moved'));
		}
	}
	
	if (empty($error) || $error !== false)
	{
		$fcm->generate_category_list($template, 'category', 'source_id');
		$fcm->generate_category_list($template, 'category', 'dest_id');
	}
	
	// Can we get the source category?
	if (!empty($_REQUEST['source_id']) && $_REQUEST['source_id'] != '--' 
		&& $source = $fcm->get_cat(intval($_REQUEST['source_id'])))
	{
		$source['name'] = '- '.$source['name'];
		$template->assign_var('source', $source);
	}
	else 
	{
		$source = array('id' => '--', 'name' => $lm->language('admin', 'categories_select'));
		$template->assign_var('source', $source);
	}
	
	// Can we get the destination category?
	if (!empty($_REQUEST['dest_id']) && $_REQUEST['dest_id'] != '--' 
		&& $dest = $fcm->get_cat(intval($_REQUEST['dest_id'])))
	{
		$dest['name'] = '- '.$dest['name'];
		$template->assign_var('dest', $dest);
	}
	else 
	{
		$dest = array('id' => '--', 'name' => $lm->language('admin', 'categories_select'));
		$template->assign_var('dest', $dest);
	}
	
	$template->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'file_mass_move'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'file_mass_move'), 'admin.php?cmd=files_mass_move');
}
?>