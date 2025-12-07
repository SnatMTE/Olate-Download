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

if ($uam->permitted('acp_leech_settings'))
{
	$template = $uim->fetch_template('admin/leech_settings');
	
	// Variabes so they don't get overwritten when using validate_types()
	$allow_list = (!empty($_REQUEST['allow_list']) && is_array($_REQUEST['allow_list']))
					? $_REQUEST['allow_list'] : false;
					
	$deny_list = (!empty($_REQUEST['deny_list']) && is_array($_REQUEST['deny_list']))
					? $_REQUEST['deny_list'] : false;
	
	// Should we be adding an entry?
	if (!empty($_REQUEST['submit_add_allow']) || !empty($_REQUEST['submit_add_deny']))
	{
		// Set up variables
		if (!empty($_REQUEST['submit_add_allow']))
		{
			$domain = $_REQUEST['new_domain_allow'];
			$action = 1;
		}
		elseif (!empty($_REQUEST['submit_add_deny']))
		{
			$domain = $_REQUEST['new_domain_deny'];
			$action = 0;
		}
		
		// Check entered domain name is valid
		if (eregi('^([a-z0-9\-]|\.|\*)+$', $domain))
		{
			// Insert it
			$sql = 'INSERT INTO '.DB_PREFIX.'leech_settings
					SET domain = "'.$domain.'",
						action = '.$action;
			
			$dbim->query($sql);
		}
		else
		{
			$error = $lm->language('admin', 'invalid_domain');
			$domain_text = $lm->language('admin', 'domain_name_characters');
			
			$message = $error.'  '.$domain_text;
			
			$hide_form = true;
			$template->assign_var('message', $message);
		}
		
	}
	// Delete entries from allow list?
	elseif ((!empty($_REQUEST['submit_delete_allow'])) || (!empty($_REQUEST['submit_delete_allow_x']) && !empty($_REQUEST['submit_delete_allow_y'])))
	{
		if (!empty($_REQUEST['allow_list']) && is_array($_REQUEST['allow_list']))
		{
			// Convert all values to integer
			$id_list = array_map('intval', $_REQUEST['allow_list']);
			$id_list_imploded = implode(', ', $id_list);
			
			if (empty($_REQUEST['confirm_yes']) && empty($_REQUEST['confirm_no']))
			{
				// Don't need to show the form
				$hide_form = true;
				
				// Set up confirmation template
				$template = $uim->fetch_template('admin/generic_yes_no');
				
				// Variables
				$template->assign_var('title', $lm->language('admin', 'leech_settings_delete'));
				$template->assign_var('desc', $lm->language('admin', 'are_you_sure_list'));
				$template->assign_var('action', 'admin.php?cmd=leech_settings&submit_delete_allow=1');
				
				// Get details of what we're deleting
				$sql = 'SELECT id, domain FROM '.DB_PREFIX.'leech_settings
						WHERE id IN('.$id_list_imploded.')
						ORDER BY domain ASC';
				
				$result = $dbim->query($sql);
				
				while ($row = $dbim->fetch_array($result))
				{
					// Add text to list of entries
					$text = str_replace('_DOMAIN_', $row['domain'], $lm->language('admin', 'leech_settings_domain'));
					$template->assign_var('text', $text);
					$template->use_block('items');
					
					// Hidden field
					$template->assign_var('field_name', 'allow_list[]');
					$template->assign_var('value', $row['id']);
					$template->use_block('hidden_fields');
				}
			}
			elseif (!empty($_REQUEST['confirm_yes']))
			{
				// Build SQL
				$sql = 'DELETE FROM '.DB_PREFIX.'leech_settings
						WHERE id IN('.$id_list_imploded.')';
				
				$dbim->query($sql);
			}
		}
	}
	// What about the deny list?
	elseif ((!empty($_REQUEST['submit_delete_deny'])) || (!empty($_REQUEST['submit_delete_deny_x']) && !empty($_REQUEST['submit_delete_deny_y'])))
	{
		if (!empty($_REQUEST['deny_list']) && is_array($_REQUEST['deny_list']))
		{
			// Convert all values to integer
			$id_list = array_map('intval', $_REQUEST['deny_list']);
			$id_list_imploded = implode(', ', $id_list);
			
			if (empty($_REQUEST['confirm_yes']) && empty($_REQUEST['confirm_no']))
			{
				// Don't need to show the form
				$hide_form = true;
				
				// Set up confirmation template
				$template = $uim->fetch_template('admin/generic_yes_no');
				
				// Variables
				$template->assign_var('title', $lm->language('admin', 'leech_settings_delete'));
				$template->assign_var('desc', $lm->language('admin', 'are_you_sure_list'));
				$template->assign_var('action', 'admin.php?cmd=leech_settings&submit_delete_deny=1');
				
				// Get details of what we're deleting
				$sql = 'SELECT id, domain FROM '.DB_PREFIX.'leech_settings
						WHERE id IN('.$id_list_imploded.')
						ORDER BY domain ASC';
				
				$result = $dbim->query($sql);
				
				while ($row = $dbim->fetch_array($result))
				{
					// Add text to list of entries
					$text = str_replace('_DOMAIN_', $row['domain'], $lm->language('admin', 'leech_settings_domain'));
					$template->assign_var('text', $text);
					$template->use_block('items');
					
					// Hidden field
					$template->assign_var('field_name', 'deny_list[]');
					$template->assign_var('value', $row['id']);
					$template->use_block('hidden_fields');
				}
			}
			elseif (!empty($_REQUEST['confirm_yes']))
			{
				// Build SQL
				$sql = 'DELETE FROM '.DB_PREFIX.'leech_settings
						WHERE id IN('.$id_list_imploded.')';
				
				$dbim->query($sql);
			}
		}
	}
	// Maybe we are moving entries from one list to the other?
	elseif (!empty($_REQUEST['submit_move_deny_allow']))
	{
		if (!empty($_REQUEST['deny_list']) && is_array($_REQUEST['deny_list']))
		{
			// Convert all values to integer
			$id_list = array_map('intval', $_REQUEST['deny_list']);
			$id_list_imploded = implode(', ', $id_list);
			
			// Build SQL
			$sql = 'UPDATE '.DB_PREFIX.'leech_settings
					SET action = 1
					WHERE id IN('.$id_list_imploded.')';
			
			$dbim->query($sql);
		}
	}
	// Or vice versa?
	elseif (!empty($_REQUEST['submit_move_allow_deny']))
	{
		if (!empty($_REQUEST['allow_list']) && is_array($_REQUEST['allow_list']))
		{
			// Convert all values to integer
			$id_list = array_map('intval', $_REQUEST['allow_list']);
			$id_list_imploded = implode(', ', $id_list);
			
			// Build SQL
			$sql = 'UPDATE '.DB_PREFIX.'leech_settings
					SET action = 0
					WHERE id IN('.$id_list_imploded.')';
			
			$dbim->query($sql);
		}
	}
	
	// Do we display the form?
	if (empty($hide_form) || $hide_form !== true)
	{
		// Get data
		$sql = 'SELECT id, domain, action
				FROM '.DB_PREFIX.'leech_settings
				ORDER BY domain ASC';
		
		$result = $dbim->query($sql);
		
		$allow_list_count = 0;
		$deny_list_count = 0;
		
		while ($entry = $dbim->fetch_array($result))
		{
			// Are we adding this to the allow list or the deny list?
			if (intval($entry['action']) == 1)
			{
				$block_name = 'allow_list';
				$allow_list_count++;
			}
			else
			{
				$block_name = 'deny_list';
				$deny_list_count++;
			}
			
			$template->assign_var('entry', $entry);
			$template->use_block($block_name);
		}
		
		// Are there any entries?
		if ($allow_list_count == 0)
		{
			$template->assign_var('allow_list_empty', true);
		}
		else
		{
			$template->assign_var('allow_list_empty', false);
		}
		
		if ($deny_list_count == 0)
		{
			$template->assign_var('deny_list_empty', true);
		}
		else
		{
			$template->assign_var('deny_list_empty', false);
		}
	}
	else
	{
		$template->assign_var('hide_form', true);
	}
	
	// Template
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'security').' - '.$lm->language('admin', 'leech_settings'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'security').' - '.$lm->language('admin', 'leech_settings'), 'admin.php?cmd=leech_settings');
}
?>