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
		
// Template
$updates_template = $uim->fetch_template('admin/od_updates');

// Socket
$fp = fsockopen('www.olate.co.uk', 80, $errno, $errstr, 3);

if (!$fp)
{
	// Failed, so error
	$updates_template->assign_var('error', $lm->language('admin', 'updates_unavailable'));
}
else
{
	// Build and post request, then recieve response
	$request = "GET /accounts/remote/updates.php HTTP/1.0\r\n";
	$request .= "Host: www.olate.co.uk\r\n\r\n";
	fputs($fp, $request);
	
	while (!feof($fp))
	{
		$response .= fgets($fp, 128);
	}
	
	// Bye bye headers...
	$split = explode("\r\n\r\n", $response);
	$latest_version = trim($split[1]);
	
	if (!ereg('^[0-9]+\.[0-9]+\.[0-9]+$', $latest_version))
	{
		$updates_template->assign_var('error', $lm->language('admin', 'updates_unavailable'));
	}
	else
	{
		// Get headers to check it worked
		$headers = explode("\r\n", $split[0]);
		
		if (strpos($headers[0], '200 OK') === false)
		{
			$updates_template->assign_var('error', $lm->language('admin', 'updates_unavailable'));
		}
		else
		{
			fclose($fp);
			
			if ($latest_version == $site_config['version'] || empty($latest_version))
			{
				// Running latest version
				$updates_template->assign_var('up_to_date', true);
			}
			
			if  (empty($latest_version))
			{
				// Failed -> error
				$updates_template->assign_var('error', $lm->language('admin', 'updates_unavailable'));
			}
			else
			{
				$updates_template->assign_var('latest_version', $latest_version);
			}
		}
	}
}

$updates_template->show();
		
$end = $uim->fetch_template('global/end');
$end->show();
		
$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('general', 'title').' - '.$lm->language('admin', 'updates'), false);
?>