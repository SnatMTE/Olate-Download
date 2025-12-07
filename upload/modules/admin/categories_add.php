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

// Start admin cp
$start = $uim->fetch_template('admin/start');
$start->show();

if ($uam->permitted('acp_categories_add'))
{		
	// Template
	$categories_add = $uim->fetch_template('admin/categories_add');
	
	// Make any changes
	if (isset($_REQUEST['submit']))
	{
		validate_types($_REQUEST, array('name' => 'STR', 'description' => 'STR', 'parent_id' => 'INT', 'order' => 'INT', 'keywords' => 'STR'));
		
		$dbim->query('INSERT INTO '.DB_PREFIX.'categories
						SET name = "'.$_REQUEST['name'].'", 
							description = "'.$_REQUEST['description'].'", 
							parent_id = "'.$_REQUEST['parent_id'].'",
							sort = "'.$_REQUEST['sort'].'",
							keywords = "'.$_REQUEST['keywords'].'"');
	
		$success = true;
		$categories_add->assign_var('success', $success);
	}
	
	// List categories
	$fcm->generate_category_list($categories_add, 'category', 'cats');
	
	$categories_add->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'categories').' - '.$lm->language('admin', 'categories_add'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'categories_add'), 'admin.php?cmd=categories_add');
}
?>