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

if ($uam->permitted('acp_customfields_add'))
{
	// Template
	$customfields_add = $uim->fetch_template('admin/customfields_add');
	
	// Make any changes
	if (isset($_REQUEST['submit']))
	{
		validate_types($_REQUEST, array('label' => 'STR', 'value' => 'STR'));
		
		$dbim->query('INSERT INTO '.DB_PREFIX.'customfields
						SET label = "'.$_REQUEST['label'].'", 
							value = "'.$_REQUEST['value'].'"');
	
		$success = true;
		$customfields_add->assign_var('success', $success);
	}
		
	$customfields_add->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'custom_fields').' - '.$lm->language('admin', 'custom_fields_add'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'custom_fields').' - '.$lm->language('admin', 'custom_fields_add'), 'admin.php?cmd=customfields_add');
}
?>