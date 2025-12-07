<?php
/**********************************
* Olate Download 3.4.0
* http://www.olate.co.uk/od3
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 197 $
* @package od
*
* Updated: $Date: 2005-12-17 11:22:39 +0000 (Sat, 17 Dec 2005) $
*/

// Initialisation
require_once('./includes/init.php');

// Show categories
$fcm->show_cats();

// Start sessions
session_start();
$_SESSION['valid_user'] = true;

// Allowed values (To prevent injections)
$allowed_sort = array('name', 'date', 'downloads', 'size');
$allowed_order = array('asc', 'desc');
$allowed_commands = array('top', 'all');

validate_types($_REQUEST, array('cmd' => 'STR', 'page' => 'INT', 'sort' => 'STR', 'order' => 'STR'));

// If no page is supplied, set it to 1
if (empty($_REQUEST['page']))
{
	$_REQUEST['page'] = 1;
}

// Decide what to show, either latest or top files
if (empty($_REQUEST['cmd']) || !in_array($_REQUEST['cmd'], $allowed_commands))
{
	// Show latest files
	$latest = $uim->fetch_template('display/latest');
	
	// Get the files on the page
	$latest_files = $fldm->get_files('date DESC', false, $site_config['latest_files'], true);
	
	foreach ($latest_files as $file)
	{
		// Get the category
		$category = $fcm->get_cat($file['category_id']);
		
		// See if it has a parent
		if (isset($category['parent_id']) && $category['parent_id'])
		{
			$parent = $fcm->get_cat($category['parent_id']);
			$latest->assign_var('parent', $parent);
		}
		
		// Are we converting new lines to <br />?
		if (intval($file['convert_newlines']) === 1)
		{
			$file['description_small'] = nl2br($file['description_small']);
			$file['description_big'] = nl2br($file['description_big']);
		}
		
		$filesize = $fldm->format_size($file['size']);
		
		$file['size'] = $filesize['size'];
		
		$latest->assign_var('filesize_format', $filesize['unit']);
		$latest->assign_var('file', $file);
		$latest->use_block('latest_file');
	}
	$latest->show();
		
	// End the table
	$end = $uim->fetch_template('global/end');
	$end->show();	
} 
elseif ($_REQUEST['cmd'] == 'top')
{
	if ($site_config['enable_topfiles'] == 1)
	{
		// Show top files
		$top = $uim->fetch_template('display/top');
		
		// Get the files from the database
		$all_top_files = $fldm->get_files('downloads DESC');
		
		// Get the files on the page by using the limit start, amount syntax
		$top_files = $fldm->get_files('downloads DESC', false, $site_config['top_files'], true);
		
		foreach ($top_files as $file)
		{
			// Get the category
			$category = $fcm->get_cat($file['category_id']);
			
			// See if it has a parent
			if (isset($category['parent_id']) && $category['parent_id'])
			{
				$parent = $fcm->get_cat($category['parent_id']);
				$top->assign_var('parent', $parent);
			}
			
			// Are we converting new lines to <br />?
			if (intval($file['convert_newlines']) === 1)
			{
				$file['description_small'] = nl2br($file['description_small']);
				$file['description_big'] = nl2br($file['description_big']);
			}
			
			$filesize = $fldm->format_size($file['size']);
		
			$file['size'] = $filesize['size'];
			
			$top->assign_var('filesize_format', $filesize['unit']);
			$top->assign_var('file', $file);
			$top->use_block('top_file');
		}
	}
	else
	{
		$top = $uim->fetch_template('display/disabled');
	}
	
	$top->show();
		
	// End the table
	$end = $uim->fetch_template('global/end');
	$end->show();	
}
elseif ($_REQUEST['cmd'] == 'all') 
{	
	if ($site_config['enable_allfiles'] == 1)
	{
		// Show all files
		$all = $uim->fetch_template('display/all');
	
		// See if we have to sort it
		if (!empty($_REQUEST['sort']) && in_array($_REQUEST['sort'], $allowed_sort) && in_array($_REQUEST['order'], $allowed_order))
		{		
			// Get the files from the database
			$all_files = $fldm->get_files($_REQUEST['sort'].' '.$_REQUEST['order']);	
				
			$sort = $_REQUEST['sort'];
			$order = $_REQUEST['order'];
					
			// Get the files on the page by using the limit start,amount syntax
			$page_files = $fldm->get_files($sort.' '.$order, false, (($_REQUEST['page']-1) * $site_config['page_amount']) . ',' . $site_config['page_amount'], true);
		} 
		else 
		{
			// Get the files from the database
			$all_files = $fldm->get_files('date', false, false, true);
				
			$sort = 'name';
			$order = 'ASC';
			
			// Get the files on the page by using the limit start,amount syntax
			$page_files = $fldm->get_files($sort.' '.$order, false, (($_REQUEST['page']-1) * $site_config['page_amount']) . ',' . $site_config['page_amount'], true);
		}
		
		// Sorting vars
		$all->assign_var('current_sort', $sort);
		$all->assign_var('current_order', $order);
		$all->assign_var('current_page', $_REQUEST['page']);
				
		foreach ($page_files as $file)
		{
			// Get the category
			$category = $fcm->get_cat($file['category_id']);
			
			// See if it has a parent
			if (isset($category['parent_id']) && $category['parent_id'])
			{
				$parent = $fcm->get_cat($category['parent_id']);
				$all->assign_var('parent', $parent);
			}
			
			// Are we converting new lines to <br />?
			if (intval($file['convert_newlines']) === 1)
			{
				$file['description_small'] = nl2br($file['description_small']);
				$file['description_big'] = nl2br($file['description_big']);
			}
			
			$filesize = $fldm->format_size($file['size']);
		
			$file['size'] = $filesize['size'];
			
			$all->assign_var('filesize_format', $filesize['unit']);
			$all->assign_var('file', $file);
			$all->use_block('all_file');
		}
		
		// Page selector
		$pagination = $fldm->make_page_box($all_files, 'index.php?cmd=all&amp;sort='.$sort.'&amp;order='.$order.'&amp;');
		$all->assign_var('pagination', $pagination);
		
		// Show it!
		$all->show();
	}
	else
	{
		$all = $uim->fetch_template('display/disabled');
		$all->show();	
	}
		
	// End the table
	$end = $uim->fetch_template('global/end');
	$end->show();
}

// Show everything
$uim->generate(TITLE_PREFIX.'Index');
?>