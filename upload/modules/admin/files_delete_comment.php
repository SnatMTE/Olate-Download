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

if ($uam->permitted('acp_files_delete_comment'))
{
	validate_types($_REQUEST, array('id' => 'INT'));
	
	// Template
	$delete = $uim->fetch_template('admin/files_delete_comment');
	
	if (!empty($_REQUEST['id']))
	{	
		$dbim->query('DELETE FROM '.DB_PREFIX.'comments
						WHERE (id = '.$_REQUEST['id'].')
						LIMIT 1');
						
		$success = true; // For redirect EOF
		$delete->assign_var('success', true);
		
		// Redirect?
		if (!empty($_REQUEST['redir']))
		{
			if ($_REQUEST['redir'] == 'files_edit_file' && !empty($_REQUEST['file_id']))
			{
				header('Location: admin.php?cmd=files_edit_file&action=file_select&file_id='.intval($_REQUEST['file_id']));
			}
		}
	}
	
	$delete->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'comments_delete_existing'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'comments_delete_existing'), 'admin.php?cmd=files_approve_comments');
}
?>