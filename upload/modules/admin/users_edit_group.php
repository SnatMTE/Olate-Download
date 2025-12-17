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

if ($uam->permitted('acp_users_edit_group'))
{
	validate_types($_REQUEST, array('id' => 'INT', 'name' => 'STR', 'group_id' => 'INT'));
	
	// Have they specified a user id?
	if (!empty($_REQUEST['id']))
	{
		$result = $dbim->query('SELECT id, name 
								FROM '.DB_PREFIX.'usergroups 
								WHERE (id = "'.$_REQUEST['id'].'")');
		$group = $dbim->fetch_array($result);
		
		// Show the form
		$group_edit = $uim->fetch_template('admin/users_edit_group');
		$group_edit->assign_var('group', $group);
					
		// Show the permissions
		$group_permissions = $uam->group_permissions($_REQUEST['id']);
		
		$group_edit->assign_var('permissions', $group_permissions);
		
		$group_edit->show();
	}
	elseif (isset($_REQUEST['submit']))
	{
		if (!isset($_REQUEST['name']) || empty($_REQUEST['name']))
		{
			// Template
			$error = $uim->fetch_template('global/error');
			$error->assign_var('error_message', $lm->language('admin', 'enter_group_name'));
			$error->show();
			
			$result = $dbim->query('SELECT id, name 
									FROM '.DB_PREFIX.'usergroups 
									WHERE (id = "'.$_REQUEST['id'].'")');
			$group = $dbim->fetch_array($result);
			
			// Show the form
			$group_edit = $uim->fetch_template('admin/users_edit_group');
			$group_edit->assign_var('group', $group);
			
			$group_edit->show();
		}
		else
		{
			$dbim->query('UPDATE '.DB_PREFIX.'usergroups 
							SET name = "'.$_REQUEST['name'].'" 
							WHERE (id = "'.$_REQUEST['group_id'].'")');

			// Update permissions - Start by deleting any current 
			// ones they may have
			$dbim->query('DELETE FROM '.DB_PREFIX.'userpermissions
							WHERE (type = "user_group")	
								AND (type_value = "'.$_REQUEST['group_id'].'")');
			
			// A list of permissions
			$permissions = $uam->list_permissions();
			
			// Get the specified permissions
			$user_permissions = $_POST['permissions'];
			
			foreach($permissions as $permission => $permission_id)
			{
				$setting = (isset($user_permissions["$permission"])) ? 1 : 0;
				
				// Insert it - This is pretty damn query heavy :(
				$dbim->query('INSERT INTO '.DB_PREFIX.'userpermissions 
								SET permission_id = "'.$permission_id.'", 
									type = "user_group",
									type_value = "'.$_REQUEST['group_id'].'", 
									setting = "'.$setting.'"');
			}	
			
			$message = $uim->fetch_template('admin/users_edit_group');
			#$success = true; // For redirect EOF
			$message->assign_var('success', true);
			$message->show();
		}
	}
	else
	{
		// Display a list of users
		$result = $dbim->query('SELECT id, name 
								FROM '.DB_PREFIX.'usergroups 
								ORDER BY name');
		
		$list = $uim->fetch_template('admin/users_edit_group_list');
		
		while ($group = $dbim->fetch_array($result))
		{
			$list->assign_var('group', $group);
			$list->use_block('group');
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'users_groups').' - '.$lm->language('admin', 'groups_edit'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'users_groups').' - '.$lm->language('admin', 'groups_edit'), 'admin.php?cmd=users_edit_group');
}
?>