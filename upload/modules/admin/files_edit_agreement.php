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

if ($uam->permitted('acp_files_edit_agreement'))
{
	validate_types($_REQUEST, array('action' => 'STR', 'id' => 'INT', 'name' => 'STR', 'contents' => 'STR_HTML'));
	
	if ($_REQUEST['action'] == 'select')
	{
		// Template
		$agreement_edit = $uim->fetch_template('admin/files_edit_agreement');
		$_REQUEST['contents'] = ereg_replace("\"","\"\"",$_REQUEST['contents']);
		
		$agreement_result = $dbim->query('SELECT id, name, contents
											FROM '.DB_PREFIX.'agreements
											WHERE (id = '.$_REQUEST['id'].')');										
		$agreement_row = $dbim->fetch_array($agreement_result);
		$agreement_edit->assign_var('agreement', $agreement_row);
		
		// Use FCKeditor or not?
		if (use_fckeditor())
		{
			$agreement_edit->assign_var('use_fckeditor', true);
			
			// Module
			include_once ('FCKeditor/fckeditor.php');
			
			// Contents field
			$fck_contents = new FCKeditor('contents');
			$fck_contents->BasePath = $site_config['url'].'FCKeditor/';
			$fck_contents->ToolbarSet = 'od';
			$fck_contents->Width = '90%';
			$fck_contents->Height = '300';
			$fck_contents->Value = $agreement_row['contents'];
			$contents_html = $fck_contents->CreateHtml();
			$agreement_edit->assign_var('contents_html', $contents_html);
		}
		else
		{
			$agreement_edit->assign_var('use_fckeditor', false);
		}
	}
	elseif ($_REQUEST['action'] == 'edit' && isset($_REQUEST['id']))
	{
		// Template
		$agreement_edit = $uim->fetch_template('admin/files_edit_agreement');
		
		$dbim->query('UPDATE '.DB_PREFIX.'agreements
						SET name = "'.$_REQUEST['name'].'", 
							contents = "'.$_REQUEST['contents'].'"
						WHERE (id = '.$_REQUEST['id'].')');
						
		$success = true; // For redirect EOF
		$agreement_edit->assign_var('success', true);
	}
	// Default template - select agreement
	else
	{
		// Template
		$agreement_edit = $uim->fetch_template('admin/files_edit_agreement_select');
		
		// Get the agreements
		$agreements_result = $dbim->query('SELECT id, name, contents
											FROM '.DB_PREFIX.'agreements');
											
		while ($agreement = $dbim->fetch_array($agreements_result))
		{
			$agreement_edit->assign_var('agreement', $agreement);
			$agreement_edit->use_block('agreements');
		}
	}
	
	$agreement_edit->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'agreement_edit'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'agreement_edit'), 'admin.php?cmd=files_edit_agreement');
}
?>