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

// Initialisation
require('./includes/init.php');

// Start sessions
session_start();
$_SESSION['valid_user'] = true;

// Show categories
$fcm->show_cats();

validate_types($_REQUEST, array('file' => 'INT', 'email' => 'STR'));

if (empty($_REQUEST['email']) || empty($_REQUEST['description']) || empty($_REQUEST['file']))
{
	// Show report form
	$report = $uim->fetch_template('files/report');
	$report->assign_var('file_id', $_REQUEST['file']);
}
else
{
	if (!isset($_SESSION['report_timestamp']) || ((time() - $site_config['flood_interval']) > $_SESSION['report_timestamp']))
	{
		$_REQUEST['description'] = str_replace('\r\n', '\n', $_REQUEST['description']);
		
		// You've got mail (no need to translate this text)
		$message = "Hello,\n\nA user (".$_REQUEST['email'].") has reported the following problem on file #".$_REQUEST['file']." at ".$site_config['url']."details.php?file=".$_REQUEST['file']." :\n\n----------\n"
				. $_REQUEST['description']
				. "\n----------";
		
		mail($site_config['admin_email'], 'Reported File', $message, 'From: '.$site_config['admin_email']);
		
		// Set a session variable with the time - flood prevention
		$_SESSION['report_timestamp'] = time();
		
		$report = $uim->fetch_template('files/report');
		$report->assign_var('result', 1);
	}
	else
	{
		$report = $uim->fetch_template('files/report');
		$report->assign_var('result', 2);
	}
}

$report->show();

// End table
$end = $uim->fetch_template('global/end');
$end->show();

// Show everything
$uim->generate(TITLE_PREFIX.$lm->language('frontend', 'report_problem'));
?>