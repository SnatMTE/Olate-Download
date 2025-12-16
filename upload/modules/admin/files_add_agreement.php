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

if ($uam->permitted('acp_files_add_agreement'))
{		
	// Template
	$agreement_add = $uim->fetch_template('admin/files_add_agreement');
	
	// Make any changes
	if (isset($_REQUEST['submit']))
	{
		validate_types($_REQUEST, array('name' => 'STR', 'contents' => 'STR_HTML'));
		
		$dbim->query('INSERT INTO '.DB_PREFIX.'agreements
						SET name = "'.$_REQUEST['name'].'", 
							contents = "'.$_REQUEST['contents'].'"');
		
		$success = true; // For redirect EOF
		$agreement_add->assign_var('success', true);
	}
	
	// Use FCKeditor or not?
	if (use_fckeditor())
	{
		$agreement_add->assign_var('use_fckeditor', true);
		
		// Module
		include_once ('FCKeditor/fckeditor.php');
		
		// Contents field
		$fck_contents = new FCKeditor('contents');
		$fck_contents->BasePath = $site_config['url'].'FCKeditor/';
		$fck_contents->ToolbarSet = 'od';
		$fck_contents->Width = '90%';
		$fck_contents->Height = '300';
		$contents_html = $fck_contents->CreateHtml();
		$agreement_add->assign_var('contents_html', $contents_html);
	}
	else
	{
		$agreement_add->assign_var('use_fckeditor', false);
	}
	
	$agreement_add->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'agreement_add'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'agreement_add'), 'admin.php?cmd=files_add_agreement');
}
?>