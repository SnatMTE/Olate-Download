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

// Initialisation
require('./includes/init.php');

// Show categories
$fcm->show_cats();

// Start sessions
session_start();
$_SESSION['valid_user'] = true;

// Allowed values (To prevent injections)
$allowed_sort = array('name', 'date', 'downloads', 'size');
$allowed_order = array('asc', 'desc');

// Get template
$category_files = $uim->fetch_template('categories/files');

validate_types($_REQUEST, array('page' => 'INT', 'cat' => 'INT', 'sort' => 'STR', 'order' => 'STR'));

// If no page is supplied, set it to 1
if (empty($_REQUEST['page']))
{
	$_REQUEST['page'] = 1;
}

if (!empty($_REQUEST['cat']))
{	
	// Get the category
    $category = $fcm->get_cat($_REQUEST['cat']);
    $category_files->assign_var('category', $category);
	$category_files->assign_var('cat', $_REQUEST['cat']);
	
	// Meta data
	if (!empty($category['description']))
	{
		$uim->add_meta_data('description', $category['description']);
	}
	if (!empty($category['keywords']))
	{
		$uim->add_meta_data('keywords', $category['keywords']);
	}
	
	// See if it has a parent
	if (isset($category['parent_id']) && $category['parent_id'])
	{
		$parent = $fcm->get_cat($category['parent_id']);
	}
	
	// Are we filtering categories?
	if ($site_config['filter_cats'] == 1)
	{
		// Listings module
		require_once 'modules/core/listings.php';
		$listing = new listing($_REQUEST['cat']);
		
		// Get categories
		$cat_heirachy = $listing->filter_cats('activate_at <= "'.time().'"', $listing->cat_heirachy);
		
		foreach ($cat_heirachy as $category_id => $not_needed)
		{
			$children[] = $fcm->get_cat($category_id);
		}
	}
	else
	{
		// Get it's children
		$children = $fcm->get_children($_REQUEST['cat']);
	}
	
	#var_dump();
	
	$category_files->assign_var('children', $children);
    
	// See if we have to sort it
	if (!empty($_REQUEST['sort']) && in_array($_REQUEST['sort'], $allowed_sort) && in_array($_REQUEST['order'], $allowed_order))
	{
		// Get files in the category
		$all_files = $fldm->get_files($_REQUEST['sort'].' '.$_REQUEST['order'], $_REQUEST['cat'], true);
		
		// Get the files on the page by using the limit start,amount syntax
		$files = $fldm->get_files($_REQUEST['sort'].' '.$_REQUEST['order'], $_REQUEST['cat'], (($_REQUEST['page']-1) * $site_config['page_amount']).','.$site_config['page_amount'], true);
	} 
	else 
	{
		// Get files in the category
		$all_files = $fldm->get_files('date DESC', $_REQUEST['cat'], false, true);
		
		// Get the files on the page by using the limit start,amount syntax
		$files = $fldm->get_files('date DESC', $_REQUEST['cat'], (($_REQUEST['page']-1) * $site_config['page_amount']).','.$site_config['page_amount'], true);
    }
	
	// Sorting vars
	$category_files->assign_var('current_sort', $_REQUEST['sort']);
	$category_files->assign_var('current_order', $_REQUEST['order']);
	$category_files->assign_var('current_page', $_REQUEST['page']);
	
    // Assign template variables
    if (count($files) > 0)
    {
        foreach ($files as $file)
        {
        	// Are we converting new lines to <br />?
			if (intval($file['convert_newlines']) === 1)
			{
				$file['description_small'] = nl2br($file['description_small']);
				$file['description_big'] = nl2br($file['description_big']);
			}
			
			$filesize = $fldm->format_size($file['size']);
        	
			$file['size'] = $filesize['size'];
			
			$category_files->assign_var('filesize_format', $filesize['unit']);
			$category_files->assign_var('file', $file);
			
			if (isset($parent))
			{
				$category_files->assign_var('parent', $parent);
			}
			
            $category_files->use_block('file');
        }
    }
    else
    {
        $category_files->assign_var('empty', $lm->language('frontend', 'error_no_files'));
    }

    if (!empty($children))
    {
    	$children_template = $uim->fetch_template('categories/children');
    	
    	foreach ($children as $child)
    	{
    		if ($site_config['enable_count'] == 1)
			{			
				$count_query = $dbim->query('SELECT c.name, COUNT( * ) AS download_count
												FROM '.DB_PREFIX.'files AS f, '.DB_PREFIX.'categories AS c
												WHERE f.category_id = c.id AND c.id = '.$child['id'].'
												GROUP BY f.category_id');
						
				$count = $dbim->fetch_array($count_query);
						
				if ($count['download_count'] != 0)
				{
					$children_template->assign_var('count', $count['download_count']);
				}
				else
				{
					$children_template->assign_var('count', 0);
				}
			}
	
			$children_template->assign_var('child', $child);
    		$children_template->use_block('child');
    	}
    	
    	$children_template->show();
    }
    
   	// Show template
	#$category_files->show();
}
else
{
    $category_files->assign_var('empty', $lm->language('frontend', 'error_no_files'));
    
    // Show template
	#$category_files->show();
}

// Show page selector
$pagination = $fldm->make_page_box($all_files, 'files.php?cat='.$_REQUEST['cat'].'&amp;cmd=all&amp;sort='.$_REQUEST['sort'].'&amp;order='.$_REQUEST['order'].'&amp;');
$category_files->assign_var('pagination', $pagination);

// Show it
$category_files->show();

// End table
$end = $uim->fetch_template('global/end');
$end->show();

// Show everything
$uim->generate(TITLE_PREFIX.$lm->language('frontend', 'files'));
?>