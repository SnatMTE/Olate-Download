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
	
if ($uam->permitted('acp_files_approve_comments'))
{
	validate_types($_REQUEST, array('id' => 'INT'));
	
	// Template
	$approve = $uim->fetch_template('admin/files_approve_comments');
	
	if (empty($_REQUEST['id']))
	{	
		$comments_result = $dbim->query('SELECT id, file_id, timestamp, name, email, comment, status
											FROM '.DB_PREFIX.'comments
											WHERE (status = 0)
											ORDER BY timestamp ASC');
		
		// Yoo-hoo, anyone there?
		if ($dbim->num_rows($comments_result) != 0)
		{
			while ($comment = $dbim->fetch_array($comments_result))
			{		
				// Get details of file comment belongs to
				$file = $fldm->get_details($comment['file_id']);
				$approve->assign_var('file', $file);
				
				$approve->assign_var('comment', $comment);
				$approve->assign_var('date', format_date($comment['timestamp']));
				$approve->assign_var('time', date('G:i:s', $comment['timestamp']));
					
				// fCode Formatting
				// [b] - bold
				$comment['comment'] = str_replace('[b]', '<strong>',$comment['comment']);
				$comment['comment'] = str_replace('[/b]', '</strong>',$comment['comment']);
				// [u] - underline
				$comment['comment'] = str_replace('[u]', '<ins>',$comment['comment']);
				$comment['comment'] = str_replace('[/u]', '</ins>',$comment['comment']);
				// [i] - italic
				$comment['comment'] = str_replace('[i]', '<em>',$comment['comment']);
				$comment['comment'] = str_replace('[/i]', '</em>',$comment['comment']);
				// [url] - urls
				$comment['comment'] = preg_replace('/\[url\](.*)\[\/url\]/si','<a href="$1">$1</a>',$comment['comment']);
					
				$approve->assign_var('text', nl2br($comment['comment']));
				$approve->use_block('comment');
			}
		}
		else
		{		
			// Where did everyone go?
			$approve->assign_var('empty', true);
		}
	}
	else
	{
		// Approve it
		$dbim->query('UPDATE '.DB_PREFIX.'comments
						SET status = 1
						WHERE (id = '.$_REQUEST['id'].')
						LIMIT 1');
						
		$success = true; // For redirect EOF
		$approve->assign_var('success', true);						
	}
	
	$approve->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'comments_approve'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'files').' - '.$lm->language('admin', 'comments_approve'), 'admin.php?cmd=files_approve_comments');
}
?>