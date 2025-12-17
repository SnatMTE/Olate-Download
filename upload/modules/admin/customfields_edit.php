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

if ($uam->permitted('acp_customfields_edit'))
{	
	if ($_REQUEST['action'] == 'select' && isset($_REQUEST['id']))
	{
		validate_types($_REQUEST, array('id' => 'INT'));
		
		// Template
		$customfields_edit = $uim->fetch_template('admin/customfields_edit');
		
		$customfields_query = $dbim->query('SELECT id, label, value
											FROM '.DB_PREFIX.'customfields
											WHERE (id = '.$_REQUEST['id'].')');
		$customfields = $dbim->fetch_array($customfields_query);
		$customfields_edit->assign_var('customfield', $customfields);
	}
	elseif ($_REQUEST['action'] == 'edit' && isset($_REQUEST['id']))
	{
		// Template
		$customfields_edit = $uim->fetch_template('admin/customfields_edit');
		
		validate_types($_REQUEST, array('label' => 'STR', 'value' => 'STR_HTML'));
		
		$dbim->query('UPDATE '.DB_PREFIX.'customfields
						SET label = "'.$_REQUEST['label'].'", 
							value = "'.$_REQUEST['value'].'"
						WHERE (id = '.$_REQUEST['id'].')');
	
		$success = true;
		$customfields_edit->assign_var('success', $success);
	}
	else
	{
		// Template
		$customfields_edit = $uim->fetch_template('admin/customfields_edit_select');
		
		$customfields_query = $dbim->query('SELECT id, label
											FROM '.DB_PREFIX.'customfields');
		
		while ($customfields = $dbim->fetch_array($customfields_query))
		{		
			$customfields_edit->assign_var('customfield', $customfields);
			$customfields_edit->use_block('customfields');
		}
	}
		
	$customfields_edit->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'custom_fields').' - '.$lm->language('admin', 'custom_fields_edit'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'custom_fields').' - '.$lm->language('admin', 'custom_fields_edit'), 'admin.php?cmd=customfields_edit');
}
?>