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

if ($uam->permitted('acp_categories_ordering'))
{
	$ordering = $uim->fetch_template('admin/categories_ordering');
	
	if (!isset($_REQUEST['submit']) && isset($_REQUEST['cat']))
	{		
		$ordering->assign_var('specified', true);
		
		$child_result = $dbim->query('SELECT id, name, description, sort 
										FROM '.DB_PREFIX.'categories 
										WHERE (parent_id = '.$_REQUEST['cat'].')');
		
		if ($dbim->num_rows($child_result) == 0)
		{
			$ordering->assign_var('none', true);
		}
		else
		{
			while ($category = $dbim->fetch_array($child_result))
			{
				$ordering->assign_var('category', $category);
				$ordering->use_block('cats_specified');
			}
		}
	}
	elseif (!isset($_REQUEST['submit']) && !isset($_REQUEST['cat']))
	{		
		// First get the categories
		$categories = $fcm->get_root_cats();
		
		// Go through each category
		foreach ($categories as $category)
		{
			// Now children, be good
			if (isset($category['children']) && !empty($category['children']))
			{
				foreach ($category['children'] as $child)
				{
					$ordering->assign_var('child', $child);
					$ordering->use_block('cat_child');							
				}		
			}
			
			$ordering->assign_var('category', $category);
			$ordering->use_block('cats');
							
			// Clear the child block, so they don't show
			// in the next category
			$ordering->clear_block('cat_child');
		}
	}
	else
	{
		// Count number of categories		
		$count_result = $dbim->query('SELECT id
										FROM '.DB_PREFIX.'categories');	
		
		while ($category = $dbim->fetch_array($count_result))
		{
			validate_types($_REQUEST, array('sort_'.$category['id'] => 'INT'));
			
			if (!empty($_REQUEST['sort_'.$category['id']]))
			{
				$dbim->query('UPDATE '.DB_PREFIX.'categories
								SET sort = '.$_REQUEST['sort_'.$category['id']].' 
								WHERE (id = '.$category['id'].')');
			}
		}
		
		$success = true;
		$ordering->assign_var('success', true);
	}
	
	$ordering->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'categories').' - '.$lm->language('admin', 'categories_ordering'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'categories').' - '.$lm->language('admin', 'categories_ordering'), 'admin.php?cmd=categories_ordering');
}
?>