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