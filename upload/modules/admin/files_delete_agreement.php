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

if ($uam->permitted('acp_files_delete_agreement'))
{
	// Template
	$agreement_delete = $uim->fetch_template('admin/files_delete_agreement');
	
	if (isset($_REQUEST['submit']))
	{
		if ($_REQUEST['id'] != 0)
		{
			validate_types($_REQUEST, array('id' => 'INT'));
			
			$check_result = $dbim->query('SELECT id, agreement_id
											FROM '.DB_PREFIX.'files
											WHERE (agreement_id = '.$_REQUEST['id'].')');
									
			if ($dbim->num_rows($check_result) == 0)
			{
				if (empty($_REQUEST['confirm_yes']) && empty($_REQUEST['confirm_no']))
				{
					// Load template
					$agreement_delete = $uim->fetch_template('admin/generic_yes_no');
					
					// Variables
					$agreement_delete->assign_var('title', $lm->language('admin', 'agreement_delete'));
					$agreement_delete->assign_var('desc', $lm->language('admin', 'are_you_sure_list'));
					$agreement_delete->assign_var('action', 'admin.php?cmd=files_delete_agreement&submit=1&id='.$_REQUEST['id']);
					
					// Get agreement name
					$result = $dbim->query('SELECT name 
											FROM '.DB_PREFIX.'agreements 
											WHERE id = '.$_REQUEST['id'].'
											LIMIT 1');
					
					$row = $dbim->fetch_array($result);
					
					// Add file to items list
					$text = str_replace('_NAME_', $row['name'], $lm->language('admin', 'agreement_delete_list_desc'));
					
					$agreement_delete->assign_var('text', $text);
					$agreement_delete->use_block('items');
				}
				elseif (!empty($_REQUEST['confirm_yes']))
				{
					// There are no files in the category
					$dbim->query('DELETE FROM '.DB_PREFIX.'agreements
									WHERE (id = '.$_REQUEST['id'].')
									LIMIT 1');
									
					$success = true; // For redirect EOF
					$agreement_delete->assign_var('result', 1);
				}
				else
				{
					$success = true; // For redirect EOF
					$agreement_delete->assign_var('result', 4);
				}
			}
			else
			{	
				// Can't delete if there are files in the category
				$agreement_delete->assign_var('result', 2);
			}
		}
		else
		{
			// Not selected an agreement
			$agreement_delete->assign_var('result', 3);
		}
	}
	else
	{
		// Get the agreements
		$agreements_result = $dbim->query('SELECT id, name, contents
											FROM '.DB_PREFIX.'agreements');
											
		while ($agreement = $dbim->fetch_array($agreements_result))
		{
			$agreement_delete->assign_var('agreement', $agreement);
			$agreement_delete->use_block('agreements');
		}
	}
	
	$agreement_delete->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' -  '.$lm->language('admin', 'agreement_delete'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' -  '.$lm->language('admin', 'agreement_delete'), 'admin.php?cmd=files_delete_agreement');
}
?>