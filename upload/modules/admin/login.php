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

// If the form hasn't been submitted, redirect them
if (!isset($_REQUEST['submit']))
{
	header('Location: admin.php');
}
else
{
	validate_types($_REQUEST, array('error' => 'STR', 'username' => 'STR', 'username' => 'STR'));
	
	if (!$user = $uam->user_check($_REQUEST['username'], $_REQUEST['password']))
	{
		$error = urlencode($uam->auth_error);
		header('Location: admin.php?error='.$error);
	}
	else
	{
		$uam->user_login($user['id'], $user['username'], $user['group_id']);
		
		// Do they want to be remembered?
		if (isset($_POST['remember']) && $_POST['remember'])
		{
			// Get the data ready
			$hash = md5($user['id'].$user['username'].$user['group_id']);
			$data = serialize(array($user['id'], $user['username'], $user['group_id']));
			
			// Set our cookie for 1 week
			setcookie('OD3_AutoLogin',$hash.'::'.$data, time() + 604800);
		}
		
		// Start admin cp
		$start = $uim->fetch_template('admin/start');
		$start->show();
		
		// Are we redirecting?
		if (!empty($_SESSION['admin_redirect']))
		{
			$redirect = $_SESSION['admin_redirect'];
			unset($_SESSION['admin_redirect']);
		}
		else
		{
			$redirect = 'admin.php';
		}
		
		$message = str_replace('_REDIRECT_', $redirect, $lm->language('admin', 'logged_in_desc'));
		
		// Show message
		$logged_in = $uim->fetch_template('admin/logged_in');
		$logged_in->assign_var('message', $message);
		$logged_in->show();
		
		$end = $uim->fetch_template('global/end');
		$end->show();
		
		$uim->generate($lm->language('admin', 'logged_in'), $redirect);
	}
}
?>