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

if ($uam->permitted('acp_users_edit_user'))
{
	validate_types($_REQUEST, array('id' => 'INT', 'user_id' => 'INT', 'username' => 'STR', 'password' => 'STR', 'confirm' => 'STR', 'email' => 'STR', 'group' => 'STR',
									'firstname' => 'STR', 'lastname' => 'STR', 'email' => 'STR', 'location' => 'STR', 'signature' => 'STR'));
	
	// Have they specified a user id?
	if (!empty($_REQUEST['id']))
	{	
		$result = $dbim->query('SELECT u.*, g.id AS group_id, g.name AS group_name 
								FROM '.DB_PREFIX.'users u, '.DB_PREFIX.'usergroups g 
								WHERE (u.id = "'.$_REQUEST['id'].'") 
									AND (u.group_id = g.id)');
		$user = $dbim->fetch_array($result);
		
		// Show the form
		$user_edit = $uim->fetch_template('admin/users_edit_user');
		$user_edit->assign_var('user', $user);
		
		// Get all the groups
		$result = $dbim->query('SELECT id, name 
								FROM '.DB_PREFIX.'usergroups');
			
		while ($group = $dbim->fetch_array($result))
		{
			$user_edit->assign_var('group', $group);
			$user_edit->use_block('group');
		}
		
		$user_edit->show();
	}
	elseif (isset($_REQUEST['submit']))
	{
		if (!$uam->user_update($_REQUEST['user_id'], $_REQUEST))
		{
			$error = $uim->fetch_template('global/error');
			$error->assign_var('error_message', $uam->auth_error);
			$error->show();
			
			$result = $dbim->query('SELECT u.*, g.id AS group_id, g.name AS group_name 
									FROM '.DB_PREFIX.'users u, '.DB_PREFIX.'usergroups g 
									WHERE (u.id = "'.$_REQUEST['user_id'].'") 
										AND (u.group_id = g.id)');
			$user = $dbim->fetch_array($result);
			
			// Reshow the form
			$user_edit = $uim->fetch_template('admin/users_edit_user');
			$user_edit->assign_var('user', $user);
			
			// Get all the groups
			$result = $dbim->query('SELECT id, name 
									FROM '.DB_PREFIX.'usergroups');
			
			while ($group = $dbim->fetch_array($result))
			{
				$user_edit->assign_var('group', $group);
				$user_edit->use_block('group');
			}
			
			$user_edit->show();		
		}
		else
		{			
			// Everything is ok
			$message = $uim->fetch_template('admin/users_edit_user');
			$success = true; // For redirect EOF
			$message->assign_var('success', true);
			$message->show();
		}	
	}
	else
	{
		// Display a list of users
		$result = $dbim->query('SELECT id, username, firstname, lastname 
								FROM '.DB_PREFIX.'users 
								ORDER BY username');
		
		$list = $uim->fetch_template('admin/users_edit_user_list');
		
		while ($user = $dbim->fetch_array($result))
		{
			$list->assign_var('user', $user);
			$list->use_block('user');
		}
		
		$list->show();
	}
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'users_groups').' - '.$lm->language('admin', 'users_edit'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'users_groups').' - '.$lm->language('admin', 'users_edit'), 'admin.php?cmd=users_edit_user');
}
?>