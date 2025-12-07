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

if ($uam->permitted('acp_ip_restrict_main'))
{
	// Update the global allow/deny setting here
	if (!empty($_REQUEST['update_action']))
	{
		$new_mode = intval($_REQUEST['ip_restrict_mode']);
		
		// Update config
		$config_sql = 'UPDATE '.DB_PREFIX.'config
						SET ip_restrict_mode = '.$new_mode;
		
		$config_result = $dbim->query($config_sql);
		
		// Update ip_restrict table
		$table_sql = 'UPDATE '.DB_PREFIX.'ip_restrict
						SET action = '.$new_mode;
		
		$table_result = $dbim->query($table_sql);
		
		$success = true;
		$redirect_to = 'admin.php?cmd=ip_restrict_main';
		
		$template = $uim->fetch_template('admin/ip_restrict_main');
		$template->assign_var('message', $lm->language('admin', 'ip_restrict_default_update'));
		$template->show();
	}
	// Test given IP address
	elseif (!empty($_REQUEST['test_ip']))
	{
		$ip_address = $_REQUEST['ip_address'];
		
		// Why check if it isn't valid?
		if ($sm->validate_ip($ip_address))
		{
			$template = $uim->fetch_template('admin/ip_restrict_testip');
			$template->assign_var('ip_address', $ip_address);
			
			$count_entries = $sm->count_entries($ip_address, 1);
			$count_all_entries = $sm->count_entries($ip_address);
			
			// Decide whether IP is allowed/denied access
			if ($count_entries == 0)
			{
				if ($site_config['ip_restrict_mode'] == 0)
				{
					$denied_message = $lm->language('admin', 'ip_restrict_ip_allowed');
				}
				else
				{
					$denied_message = $lm->language('admin', 'ip_restrict_ip_denied');
				}
			}
			else
			{
				if ($site_config['ip_restrict_mode'] == 0)
				{
					$denied_message = $lm->language('admin', 'ip_restrict_ip_denied');
				}
				else
				{
					$denied_message = $lm->language('admin', 'ip_restrict_ip_allowed');
				}
			}
			
			// Replace _IP_ADDRESS_ with real IP address
			$denied_message = str_replace('_IP_ADDRESS_', $ip_address, $denied_message);
			
			// Assign message and show template
			$template->assign_var('denied_message', $denied_message);
			$template->show();
			
			
			// Entries?
			if ($count_all_entries > 0)
			{
				$entries = $uim->fetch_template('admin/ip_restrict_entries');
				
				$entries->assign_var('has_entries', true);
				$entries->assign_var('disable_sort', true);
				
				while ($filter_row = $sm->get_entries($ip_address))
				{
					$entries->assign_var('filter_row', $filter_row);
					$entries->use_block('filter_row');
				}
				
				$entries->show();
			}
		}
		else
		{
			// Invalid IP
			$template = $uim->fetch_template('admin/ip_restrict_main');
			$template->assign_var('message', $lm->language('admin', 'ip_restrict_invalid_ip'));
			$template->show();
		}
	}
	// Add new entry: IP address
	elseif (!empty($_REQUEST['act']) && $_REQUEST['act'] == 'new_ipaddress')
	{
		$template = $uim->fetch_template('admin/ip_restrict_new_ipaddress');
		
		// Submitted in some way...
		if (!empty($_REQUEST['submit']) || !empty($_REQUEST['submit_yes']) || !empty($_REQUEST['submit_no']))
		{
			// Good IP?
			if ($sm->validate_ip($_REQUEST['ip_address'], true))
			{
				if ($_REQUEST['ip_address'] == $_SERVER['REMOTE_ADDR']
						&& (empty($_REQUEST['submit_yes']) && empty($_REQUEST['submit_no'])))
				{
					// Conflict in that users IP equals the one entered
					$error = $lm->language('admin', 'ip_restrict_includes_client');
					$template->assign_var('error', $error);
					
					$template->assign_var('submitted', $_REQUEST);
					
					$template->assign_var('hide_form', true);
					$template->assign_var('need_confirmation', true);
				}
				elseif (!empty($_REQUEST['submit_no']))
				{
					// User doesn't want to proceed
					header('Location: admin.php?cmd=ip_restrict_main');
					exit;
				}
				else
				{
					// User does want to proceed
					validate_types($_REQUEST, array('ip_address' => 'STR',
													'filter_active' => 'INT'));
					
					// Assuming either there was no conflict or user clicked yes
					$sql = 'INSERT INTO '.DB_PREFIX.'ip_restrict
								(type, start, action, active)
							VALUES
								(
									1,
									"'.$_REQUEST['ip_address'].'", 
									'.$site_config['ip_restrict_mode'].', 
									'.intval($_REQUEST['filter_active']).'
								)';
					
					$result = $dbim->query($sql);
					
					$success = true;
					$redirect_to = 'admin.php?cmd=ip_restrict_main&amp;act=new_ipaddress';
				}
			}
			else
			{
				// Bad IP
				$error = $lm->language('admin', 'ip_restrict_invalid_ip');
				$template->assign_var('error', $error);
			}
		}
		
		if (!isset($success) && $success !== true)
		{
			$template->assign_var('submitted', $_REQUEST);
		}
		else
		{
			$template->assign_var('success', $success);
		}
		
		// Templates
		$template->show();
	}
	// Add new entry: IP range
	elseif (!empty($_REQUEST['act']) && $_REQUEST['act'] == 'new_iprange')
	{
		$template = $uim->fetch_template('admin/ip_restrict_new_iprange');
		
		if (!empty($_REQUEST['submit']) || !empty($_REQUEST['submit_yes']) || !empty($_REQUEST['submit_no']))
		{
			// Good IP?
			if ($sm->validate_ip($_REQUEST['ip_address_start'], true) && $sm->validate_ip($_REQUEST['ip_address_end'], true))
			{
				// Convert IP addresses to integers for comparison
				$range_start_long = sprintf("%u\n", ip2long($_REQUEST['ip_address_start']));
				$range_end_long = sprintf("%u\n", ip2long($_REQUEST['ip_address_end']));
				
				// Is the end IP before the starting one?
				if ($range_start_long > $range_end_long)
				{
					$error = $lm->language('admin', 'ip_restrict_start_gt_end');
					$template->assign_var('error', $error);
					
					$template->assign_var('submitted', $_REQUEST);
				}
				// Does user's IP fall into the range?
				elseif ($sm->range_contains_ip($_SERVER['REMOTE_ADDR'], $_REQUEST['ip_address_start'], $_REQUEST['ip_address_end'])
						&& (empty($_REQUEST['submit_yes']) && empty($_REQUEST['submit_no'])))
				{
					$error = $lm->language('admin', 'ip_restrict_includes_client');
					$template->assign_var('error', $error);
					
					$template->assign_var('submitted', $_REQUEST);
					
					$template->assign_var('hide_form', true);
					$template->assign_var('need_confirmation', true);
				}
				// User doesn't want to continue
				elseif (!empty($_REQUEST['submit_no']))
				{
					header('Location: admin.php?cmd=ip_restrict_main');
					exit;
				}
				// User does want to continue
				else
				{
					validate_types($_REQUEST, array('ip_address_start' => 'STR',
													'ip_address_end' => 'STR',
													'filter_active' => 'INT'));
					
					// Assuming either there was no conflict or user clicked yes
					$sql = 'INSERT INTO '.DB_PREFIX.'ip_restrict
								(type, start, end, action, active)
							VALUES
								(
									2,
									"'.$_REQUEST['ip_address_start'].'", 
									"'.$_REQUEST['ip_address_end'].'", 
									'.$site_config['ip_restrict_mode'].', 
									'.intval($_REQUEST['filter_active']).'
								)';
					
					$result = $dbim->query($sql);
					
					$success = true;
					$redirect_to = 'admin.php?cmd=ip_restrict_main&amp;act=new_iprange';
				}
			}
			else
			{
				// Bad IP
				$error = $lm->language('admin', 'ip_restrict_invalid_ip');
				$template->assign_var('error', $error);
			}
		}
		
		if (!isset($success) && $success !== true)
		{
			$template->assign_var('submitted', $_REQUEST);
		}
		else
		{
			$template->assign_var('success', $success);
		}
		$template->show();
	}
	// Add new entry: Network
	elseif (!empty($_REQUEST['act']) && $_REQUEST['act'] == 'new_network')
	{
		$template = $uim->fetch_template('admin/ip_restrict_new_network');
		
		if (!empty($_REQUEST['submit']) || !empty($_REQUEST['submit_yes']) || !empty($_REQUEST['submit_no']))
		{
			// Good IP?
			if ($sm->validate_ip($_REQUEST['network_address']))
			{
				// Good netmask?
				if ($_REQUEST['filter_type'] == 3 && !$sm->validate_ip($_REQUEST['dotted_netmask']))
				{
					// No
					$error = $lm->language('admin', 'ip_restrict_invalid_ip_plural');
					$template->assign_var('error', $error);
				}
				else
				{
					// Yes :)
					$netmask = ($_REQUEST['filter_type'] == 3) ? $_REQUEST['dotted_netmask'] : $_REQUEST['cidr_netmask'];
					
					// User's IP in the network?
					if ($sm->network_contains_ip($_SERVER['REMOTE_ADDR'], $_REQUEST['network_address'], $netmask)
						&& (empty($_REQUEST['submit_yes']) && empty($_REQUEST['submit_no'])))
					{
						// Yes, so ask for confirmation
						$error = $lm->language('admin', 'ip_restrict_includes_client');
						$template->assign_var('error', $error);
						
						$template->assign_var('submitted', $_REQUEST);
						
						$template->assign_var('hide_form', true);
						$template->assign_var('need_confirmation', true);
					}
					// User doesn't want to continue
					elseif (!empty($_REQUEST['submit_no']))
					{
						header('Location: admin.php?cmd=ip_restrict_main');
						exit;
					}
					// User does want to continue
					else
					{
						validate_types($_REQUEST, array('filter_type' => 'INT',
														'network_address' => 'STR',
														'dotted_netmask' => 'STR',
														'cidr_netmask' => 'INT',
														'filter_active' => 'INT'));
						
						if (intval($_REQUEST['filter_type']) == 3)
						{
							$netmask = $_REQUEST['dotted_netmask'];
						}
						else
						{
							$netmask = $_REQUEST['cidr_netmask'];
						}
						
						// Insert into database
						$sql = 'INSERT INTO '.DB_PREFIX.'ip_restrict
								SET type = '.intval($_REQUEST['filter_type']).',
									start = "'.$_REQUEST['network_address'].'",
									mask = "'.$netmask.'",
									action = '.intval($site_config['ip_restrict_mode']).',
									active = '.intval($_REQUEST['filter_active']);
						
						$result = $dbim->query($sql);
						
						$success = true;
						$redirect_to = 'admin.php?cmd=ip_restrict_main&amp;act=new_network';
					}
				}
			}
			else
			{
				// Bad IP
				$error = $lm->language('admin', 'ip_restrict_invalid_ip_plural');
				$template->assign_var('error', $error);
			}
		}
		
		if (!isset($success) && $success !== true)
		{
			$template->assign_var('submitted', $_REQUEST);
		}
		else
		{
			$template->assign_var('success', $success);
		}
		
		$template->show();
	}
	// Edit entry/entries
	elseif (!empty($_REQUEST['submit_edit']))
	{
		// Get list of IDs to work with
		foreach ($_REQUEST as $name => $value)
		{
			if (ereg('ip_check_([0-9]{1,})', $name, $registers))
			{
				$id_list[] = intval($registers[1]);
			}
		}
		
		$template = $uim->fetch_template('admin/ip_restrict_edit_main');
		
		// Submitting data or not?	
		if (!empty($_REQUEST['submit_submit']))
		{
			// Loop over input
			foreach ($_REQUEST['entries'] as $id => $data)
			{
				// For each type, check specific fields
				if ($data['type'] == 1)
				{
					if (!$sm->validate_ip($data['start'], true) || $data['start'] == '')
					{
						$error_fields[$id]['start'] = true;
					}
				}
				elseif ($data['type'] == 2)
				{
					if (!$sm->validate_ip($data['start'], true) || $data['start'] == '')
					{
						$error_fields[$id]['start'] = true;
					}
					
					if (!$sm->validate_ip($data['end'], true) || $data['end'] == '')
					{
						$error_fields[$id]['end'] = true;
					}
				}
				elseif ($data['type'] == 3)
				{
					if (!$sm->validate_ip($data['start']) || $data['start'] == '')
					{
						$error_fields[$id]['start'] = true;
					}
					
					if (!$sm->validate_ip($data['mask']) || $data['mask'] == '')
					{
						$error_fields[$id]['mask'] = true;
					}
				}
				elseif ($data['type'] == 4)
				{
					if (!$sm->validate_ip($data['start']) || $data['start'] == '')
					{
						$error_fields[$id]['start'] = true;
					}
					
					if ($data['mask'] <= 0 || $data['mask'] >= 32)
					{
						$error_fields[$id]['mask'] = true;
					}
				}
				
				// No errors with this entry, so update it
				if (empty($error_fields[$id]))
				{
					validate_types($data, array('id' => 'INT', 'start' => 'STR', 
												'end' => 'INT', 'mask' => 'STR', 
												'active' => 'INT'));
					
					// Build query
					$sql = 'UPDATE '.DB_PREFIX."ip_restrict SET\n";
					
					// Lazy way to make the field/value list
					foreach ($data as $name => $value)
					{
						 if ($name != 'id')
						 {
						 	$sql_bits[] = $name.' = "'.$value.'"';
						 }
					}
					
					// Add field/value list to query and finish it off
					$sql .= implode(",\n", $sql_bits);
					$sql .= "\nWHERE id = $id";
					
					$dbim->query($sql);
					
					// Unset this entry from list as it is finished
					$key = array_search($id, $id_list);
					unset($id_list[$key]);
				}
			}
			
			// No errors? If so then we're done here
			if (empty($error_fields))
			{
				$message = $lm->language('admin', 'ip_restrict_entry_updated');
				$template->assign_var('message', $message);
				
				$hide_form = true;
				$template->assign_var('hide_form', $hide_form);
				
				$success = true;
				$redirect_to = 'admin.php?cmd=ip_restrict_main';
			}
		}
		
		// Assign error field list to template if there are any
		if (!empty($error_fields))
		{
			$template->assign_var('error_fields', $error_fields);
		}
		
		if (empty($hide_form) || $hide_form === false)
		{
			// No point continuing when nothing needs editing
			if (count($id_list) > 0)
			{
				foreach ($id_list as $id)
				{
					// Has the form been submitted yet?
					if (empty($_REQUEST['entries'][$id]) || count($_REQUEST['entries'][$id]) == 0 || !empty($_REQUEST['submit_revert']))
					{
						// No, so get entry from database
						$entry = $sm->get_entry_by_id($id);
					}
					else
					{
						// Yes, so use submitted data
						$entry = $_REQUEST['entries'][$id];
						
						// Don't pass dotted netmask to CIDR
						if ($entry['type'] == 4 && !($entry['mask'] >0 && $entry['mask'] <= 32))
						{
							$entry['mask'] = '';
						}
					}
					
					$template_for_types = array(
						1 => 'admin/ip_restrict_edit_ipaddress',
						2 => 'admin/ip_restrict_edit_iprange',
						3 => 'admin/ip_restrict_edit_dot_network',
						4 => 'admin/ip_restrict_edit_cidr'
					);
					
					// Template and assign entry to it
					$type_form = $uim->fetch_template($template_for_types[$entry['type']]);
					$type_form->assign_var('entry', $entry);
					
					// Assign error field list to template if there are any
					if (!empty($error_fields))
					{
						$type_form->assign_var('error_fields', $error_fields);
					}
					
					// Get output from type_form template
					$form_for_type = $type_form->show(true);
					
					// Assign
					$template->assign_var('form_for_type', $form_for_type);
					
					// Block
					$template->assign_var('entry', $entry);
					$template->use_block('entry');
				}
			}
			else
			{
				// Nothing selected
				$template->assign_var('message', $lm->language('admin', 'ip_restrict_entries_none'));
			}
		}
		
		// Template
		$template->show();
	}
	// Delete entry/entries
	elseif (!empty($_REQUEST['submit_delete']))
	{
		// Get list of IDs to work with
		foreach ($_REQUEST as $name => $value)
		{
			if (ereg('ip_check_([0-9]{1,})', $name, $registers))
			{
				$id_list[] = intval($registers[1]);
			}
		}
		
		if (count($id_list) > 0)
		{
			if (empty($_REQUEST['confirm_yes']) && empty($_REQUEST['confirm_no']))
			{
				// Load template
				$template = $uim->fetch_template('admin/generic_yes_no');
				
				// Variables
				$template->assign_var('title', $lm->language('admin', 'ip_restrict_delete_entries'));
				$template->assign_var('desc', $lm->language('admin', 'are_you_sure_list'));
				$template->assign_var('action', 'admin.php?cmd=ip_restrict_main&submit_delete=1');
				
				foreach ($id_list as $entry_id)
				{
					// Get entry details
					$entry = $sm->get_entry_by_id($entry_id);
					
					switch (intval($entry['type']))
					{
						case 1:
							$text = str_replace('_ADDRESS_', $entry['start'], $lm->language('admin', 'ip_restrict_del_desc_address'));
							break;
						case 2:
							$text = str_replace('_ADDRESS_START_', $entry['start'], $lm->language('admin', 'ip_restrict_del_desc_range'));
							$text = str_replace('_ADDRESS_END_', $entry['end'], $text);
							break;
						case 3:
							$text = str_replace('_NETWORK_ADDRESS_', $entry['start'], $lm->language('admin', 'ip_restrict_del_desc_network'));
							$text = str_replace('_NETWORK_MASK_', $entry['mask'], $text);
							break;
						case 4:
							$text = str_replace('_NETWORK_ADDRESS_', $entry['start'], $lm->language('admin', 'ip_restrict_del_desc_network'));
							$text = str_replace('_NETWORK_MASK_', $entry['mask'], $text);
							break;
					}
					
					// Add text to list
					$template->assign_var('text', $text);
					$template->use_block('items');
					
					// Hidden fields
					$template->assign_var('field_name', 'ip_check_'.$entry_id);
					$template->assign_var('value', 1);
					$template->use_block('hidden_fields');
				}
			}
			elseif (!empty($_REQUEST['confirm_yes']))
			{
				// Build SQL
				$sql = 'DELETE FROM '.DB_PREFIX.'ip_restrict
						WHERE id IN (';
				$sql .= implode(', ', $id_list);
				$sql .= ')';
				
				// Run it
				$dbim->query($sql);
				
				// Closing vars
				$message = $lm->language('admin', 'ip_restrict_entries_deleted');
				$success = true;
				$redirect_to = 'admin.php?cmd=ip_restrict_main';
			}
			else
			{
				$message = $lm->language('admin', 'ip_restrict_entries_not_deleted');
				$success = true;
				$redirect_to = 'admin.php?cmd=ip_restrict_main';	
			}
		}
		else
		{
			// Nothing selected
			$message = $lm->language('admin', 'ip_restrict_entries_none');
		}
		
		if (empty($template))
		{
			$template = $uim->fetch_template('admin/ip_restrict_delete');
		}
		
		$template->assign_var('message', $message);
		$template->show();
	}
	// Enable entry/entries
	elseif (!empty($_REQUEST['submit_enable']))
	{
		// Get list of IDs to work with
		foreach ($_REQUEST as $name => $value)
		{
			if (ereg('ip_check_([0-9]{1,})', $name, $registers))
			{
				$id_list[] = intval($registers[1]);
			}
		}
		
		if (count($id_list) > 0)
		{
			// Build SQL
			$sql = 'UPDATE '.DB_PREFIX.'ip_restrict
					SET active = 1
					WHERE id IN (';
			$sql .= implode(', ', $id_list);
			$sql .= ')';
			
			// Run it
			$dbim->query($sql);
			
			// Closing vars
			$message = $lm->language('admin', 'ip_restrict_entry_updated');
			$success = true;
			$redirect_to = 'admin.php?cmd=ip_restrict_main';
		}
		else
		{
			// Nothing selected
			$message = $lm->language('admin', 'ip_restrict_entries_none');
		}
		
		$template = $uim->fetch_template('admin/ip_restrict_active');
		$template->assign_var('message', $message);
		$template->show();
	}
	// Disable entry/entries
	elseif (!empty($_REQUEST['submit_disable']))
	{
		// Get list of IDs to work with
		foreach ($_REQUEST as $name => $value)
		{
			if (ereg('ip_check_([0-9]{1,})', $name, $registers))
			{
				$id_list[] = intval($registers[1]);
			}
		}
		
		if (count($id_list) > 0)
		{
			// Build SQL
			$sql = 'UPDATE '.DB_PREFIX.'ip_restrict
					SET active = 0
					WHERE id IN (';
			$sql .= implode(', ', $id_list);
			$sql .= ')';
			
			// Run it
			$dbim->query($sql);
			
			// Closing vars
			$message = $lm->language('admin', 'ip_restrict_entry_updated');
			$success = true;
			$redirect_to = 'admin.php?cmd=ip_restrict_main';
		}
		else
		{
			// Nothing selected
			$message = $lm->language('admin', 'ip_restrict_entries_none');
		}
		
		$template = $uim->fetch_template('admin/ip_restrict_active');
		$template->assign_var('message', $message);
		$template->show();
	}
	// Catch-all for any typos/whatever
	else
	{
		$display_main_page = true;
	}
	
	// Only display it if needed
	if (isset($display_main_page) && $display_main_page === true)
	{
		validate_types($_REQUEST, array('sort_field' => 'STR', 'sort_direc' => 'STR'));
		
		// Template
		$template = $uim->fetch_template('admin/ip_restrict_main');
		
		// Get denial count from db
		$count_sql = 'SELECT COUNT(*) AS count
				FROM '.DB_PREFIX.'ip_restrict_log';
		
		$count_result = $dbim->query($count_sql);
		$count_row = $dbim->fetch_array($count_result);
		
		$template->assign_var('denial_count', $count_row['count']);
		
		// Get latest denial
		$denial_sql = 'SELECT *, UNIX_TIMESTAMP(timestamp) AS date  
						FROM '.DB_PREFIX.'ip_restrict_log
						ORDER BY timestamp DESC
						LIMIT 1';
		
		$denial_result = $dbim->query($denial_sql);
		$denial_row = $dbim->fetch_array($denial_result);
		
		if ($dbim->num_rows($denial_result) == 0)
		{
			$template->assign_var('show_stats', false);
		}
		else
		{
			// Format the date
			$denial_row['formatted_date'] = format_date($denial_row['date']);
			$denial_row['formatted_time'] = date('H:i:s', $denial_row['date']);
			
			// Try to lookup hostname from ip
			$hostname = gethostbyaddr($denial_row['ip_address']);
			
			// Assign hostname if lookup worked
			if ($hostname == $denial_row['ip_address'])
			{
				$template->assign_var('has_hostname', false);
			}
			else
			{
				$template->assign_var('has_hostname', true);
				$denial_row['hostname'] = $hostname;
			}
			
			// Assign
			$template->assign_var('denial_data', $denial_row);
		}
		
		// Show template
		$template->show();
		
		// Entries
		$entries = $uim->fetch_template('admin/ip_restrict_entries');
		
		// Sort by a field?
		if (isset($_REQUEST['sort_field']))
		{
			$allowed_sort_fields = array('id', 'type', 'active');
			
			if (in_array($_REQUEST['sort_field'], $allowed_sort_fields))
			{
				$field = $_REQUEST['sort_field'];
			}
			else
			{
				// Default
				$field = 'id';
			}
		}
		else
		{
			// Default
			$field = 'id';
		}
		
		// Sort in which direction?
		if (isset($_REQUEST['sort_field']))
		{
			$allowed_sort_direc = array('asc', 'desc');
			
			if (in_array($_REQUEST['sort_direc'], $allowed_sort_direc))
			{
				$direction = $_REQUEST['sort_direc'];
			}
			else
			{
				// Default
				$direction = 'asc';
			}
		}
		else
		{
			// Default
			$direction = 'asc';
		}
		
		// Assign sorting details
		$entries->assign_var('sort_field', $field);
		$entries->assign_var('sort_direc', $direction);
		
		// Get list of filters from database
		$filter_sql = 'SELECT * FROM '.DB_PREFIX.'ip_restrict
						ORDER BY '.$field.' '.strtoupper($direction);
		
		$filter_result = $dbim->query($filter_sql);
		
		while ($filter_row = $dbim->fetch_array($filter_result))
		{
			$entries->assign_var('filter_row', $filter_row);
			$entries->use_block('filter_row');
		}
		
		// Show template
		$entries->show();	
	}
}
else
{
	// User is not permitted
	$no_permission = $uim->fetch_template('admin/no_permission');
	$no_permission->show();
}

$end = $uim->fetch_template('global/end');
$end->show();

if (isset($success) && isset($redirect_to) && $redirect_to != '')
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'security').' - '.$lm->language('admin', 'ip_restriction'), $redirect_to);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'security').' - '.$lm->language('admin', 'ip_restriction'), false);
}

?>