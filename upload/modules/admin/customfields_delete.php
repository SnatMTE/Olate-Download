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

if ($uam->permitted('acp_customfields_delete'))
{	
	if (isset($_REQUEST['id']))
	{
		// Template
		$customfields_delete = $uim->fetch_template('admin/customfields_delete');
		
		validate_types($_REQUEST, array('id' => 'INT'));
		
		if (empty($_REQUEST['confirm_yes']) && empty($_REQUEST['confirm_no']))
		{
			// Get field label
			$result = $dbim->query('SELECT label
									FROM '.DB_PREFIX.'customfields
									WHERE id = '.$_REQUEST['id']);
			
			$row = $dbim->fetch_array($result);
			
			// Template for confirmation
			$customfields_delete = $uim->fetch_template('admin/generic_yes_no');
			
			// Variables
			$customfields_delete->assign_var('title', $lm->language('admin', 'custom_fields_delete'));
			$customfields_delete->assign_var('desc', $lm->language('admin', 'are_you_sure_list'));
			$customfields_delete->assign_var('action', 'admin.php?cmd=customfields_delete&id='.$_REQUEST['id']);
			
			// Block for what's being deleted
			$text = str_replace('_NAME_', $row['label'], $lm->language('admin', 'custom_field_list_desc'));
			$customfields_delete->assign_var('text', $text);
			$customfields_delete->use_block('items');
		}
		elseif (!empty($_REQUEST['confirm_yes']))
		{
			$dbim->query('DELETE FROM '.DB_PREFIX.'customfields
							WHERE (id = '.$_REQUEST['id'].')');
			
			$dbim->query('DELETE FROM '.DB_PREFIX.'customfields_data
							WHERE (field_id = '.$_REQUEST['id'].')');
		
			$success = true;
			$customfields_delete->assign_var('success', $success);
		}
		else
		{
			$success = true;
			$customfields_delete->assign_var('success', 'nothing');
		}
	}
	else
	{
		// Template
		$customfields_delete = $uim->fetch_template('admin/customfields_delete');
		
		$customfields_query = $dbim->query('SELECT id, label
											FROM '.DB_PREFIX.'customfields');
		
		while ($customfields = $dbim->fetch_array($customfields_query))
		{		
			$customfields_delete->assign_var('customfield', $customfields);
			$customfields_delete->use_block('customfields');
		}
	}
		
	$customfields_delete->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'custom_fields').' - '.$lm->language('admin', 'custom_fields_delete'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'custom_fields').' - '.$lm->language('admin', 'custom_fields_delete'), 'admin.php?cmd=customfields_delete');
}
?>