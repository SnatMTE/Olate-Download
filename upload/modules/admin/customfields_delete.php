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
			$result = $dbim->pquery('SELECT label
									FROM '.DB_PREFIX.'customfields
									WHERE id = ?',
									array($_REQUEST['id']));
			
			$row = $dbim->fetch_array_p($result);
			
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
			$dbim->pquery('DELETE FROM '.DB_PREFIX.'customfields
							WHERE (id = ?)',
							array($_REQUEST['id']));
			
			$dbim->pquery('DELETE FROM '.DB_PREFIX.'customfields_data
							WHERE (field_id = ?)',
							array($_REQUEST['id']));
		
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
		
		$customfields_query = $dbim->pquery('SELECT id, label
											FROM '.DB_PREFIX.'customfields', array());
		
		while ($customfields = $dbim->fetch_array_p($customfields_query))
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