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

if ($uam->permitted('acp_users_add_user'))
{		
	// Has the form been submitted?
	if (isset($_REQUEST['submit']))
	{
		validate_types($_REQUEST, array('username' => 'STR', 'password' => 'STR', 'confirm' => 'STR', 'email' => 'STR', 'group' => 'STR',
										'firstname' => 'STR', 'lastname' => 'STR', 'email' => 'STR', 'location' => 'STR', 'signature' => 'STR'));
		
		if (!$uam->user_register($_REQUEST))
		{
			// Template
			$error = $uim->fetch_template('global/error');
			$error->assign_var('error_message', $uam->auth_error);
			$error->show();
			
			// Reshow the form
			$user_add = $uim->fetch_template('admin/users_add_user');
			
			// Get all the groups
			$result = $dbim->query('SELECT id, name 
									FROM '.DB_PREFIX.'usergroups');
			
			while ($group = $dbim->fetch_array($result))
			{
				$user_add->assign_var('group', $group);
				$user_add->use_block('group');
			}
			
			$user_add->show();		
		}
		else
		{
			// Everything is ok
			$message = $uim->fetch_template('admin/users_add_user');
			$success = true; // For redirect EOF
			$message->assign_var('success', true);
			$message->show();
		}
	}
	else
	{
		// Show the form
		$user_add = $uim->fetch_template('admin/users_add_user');
		
		// Get all the groups
		$result = $dbim->query('SELECT id, name 
								FROM '.DB_PREFIX.'usergroups');
		
		while ($group = $dbim->fetch_array($result))
		{
			$user_add->assign_var('group', $group);
			$user_add->use_block('group');
		}
	
		$user_add->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'users_groups').' - '.$lm->language('admin', 'users_add'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'users_groups').' - '.$lm->language('admin', 'users_add'), 'admin.php?cmd=users_add_user');
}
?>