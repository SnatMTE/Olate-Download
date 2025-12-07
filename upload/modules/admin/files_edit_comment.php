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

if ($uam->permitted('acp_files_edit_comment'))
{
	validate_types($_REQUEST, array('id' => 'INT', 'submit' => 'INT', 'name' => 'STR', 'email' => 'STR', 'comment' => 'STR'));
	
	// Template
	$edit = $uim->fetch_template('admin/files_edit_comment');
	
	if (!empty($_REQUEST['id']) && !isset($_REQUEST['submit']))
	{	
		$comment_result = $dbim->query('SELECT id, name, email, comment
											FROM '.DB_PREFIX.'comments
											WHERE (id = '.$_REQUEST['id'].')');
											
		$comment = $dbim->fetch_array($comment_result);
		$edit->assign_var('comment', $comment);
		
		if (!empty($_REQUEST['redir']))
		{
			if ($_REQUEST['redir'] == 'files_edit_file' && !empty($_REQUEST['file_id']))
			{
				$edit->assign_var('redir', $_REQUEST['redir']);
				$edit->assign_var('file_id', $_REQUEST['file_id']);
			}
		}
	}
	elseif (!empty($_REQUEST['id']) && isset($_REQUEST['submit']))
	{
		$comment_result = $dbim->query('UPDATE '.DB_PREFIX.'comments
											SET name = "'.$_REQUEST['name'].'",
												email = "'.$_REQUEST['email'].'",
												comment = "'.$_REQUEST['comment'].'"
											WHERE (id = '.$_REQUEST['id'].')');
									
		$success = true; // For redirect EOF
		$edit->assign_var('success', true);
		
		// Do we need to redirect?
		if (!empty($_REQUEST['redir']))
		{
			if ($_REQUEST['redir'] == 'files_edit_file' && !empty($_REQUEST['file_id']))
			{
				header('Location: admin.php?cmd=files_edit_file&action=file_select&file_id='.intval($_REQUEST['file_id']));
				exit;
			}
		}
	}
	else
	{
		$edit->assign_var('empty', true);
	}
	
	$edit->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'comments_edit_existing'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'comments_edit_existing'), 'admin.php?cmd=files_edit_comment&id='.$_REQUEST['id']);
}
?>