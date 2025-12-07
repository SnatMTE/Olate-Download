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

if ($uam->permitted('acp_users_delete_user'))
{
	// Template
	$delete_user = $uim->fetch_template('admin/users_delete_user');
	
	validate_types($_REQUEST, array('id' => 'INT'));
	
	// Have they specified a user id?
	if (!empty($_REQUEST['id']))
	{
		if (empty($_REQUEST['confirm_yes']) && empty($_REQUEST['confirm_no']))
		{
			// Get user info
			$result = $dbim->query('SELECT id, username, firstname, lastname 
									FROM '.DB_PREFIX.'users 
									WHERE id = '.$_REQUEST['id']);
			
			$row = $dbim->fetch_array($result);
			
			// Load template
			$delete_user = $uim->fetch_template('admin/generic_yes_no');
			
			// Variables
			$delete_user->assign_var('title', $lm->language('admin', 'users_delete'));
			$delete_user->assign_var('desc', $lm->language('admin', 'are_you_sure_list'));
			$delete_user->assign_var('action', 'admin.php?cmd=users_delete_user&id='.$_REQUEST['id']);
			
			// Add user to items list
			$text = str_replace('_NAME_', $row['username'], $lm->language('admin', 'users_delete_list_desc'));
			
			$delete_user->assign_var('text', $text);
			$delete_user->use_block('items');
		}
		elseif (!empty($_REQUEST['confirm_yes']))
		{
			// Delete user
			$dbim->query('DELETE FROM '.DB_PREFIX.'users
							WHERE (id = '.$_REQUEST['id'].')
							LIMIT 1');
							
			$success = true; // For redirect EOF
			$delete_user->assign_var('success', true);
		}
		else
		{
			$success = true; // For redirect EOF
			$delete_user->assign_var('success', 'nothing');
		}
	}
	else
	{
		// Display a list of users
		$result = $dbim->query('SELECT id, username, firstname, lastname 
								FROM '.DB_PREFIX.'users 
								ORDER BY username');
		
		while ($user = $dbim->fetch_array($result))
		{
			$delete_user->assign_var('user', $user);
			$delete_user->use_block('user');
		}
	}
	
	$delete_user->show();
}
else
{
	// User is not permitted
	$no_permission = $uim->fetch_template('admin/no_permission');
	$no_permission->show();
}

// End the page
$end = $uim->fetch_template('global/end');
$end->show();
				
if (!isset($success) || !$success)
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'users_groups').' - '.$lm->language('admin', 'users_add'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'users_groups').' - '.$lm->language('admin', 'users_add'), 'admin.php?cmd=users_delete_user');
}
?>