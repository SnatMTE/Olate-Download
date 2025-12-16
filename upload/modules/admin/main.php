<?php
/**********************************
* Olate Download 3.4.0
* https://github.com/SnatMTE/Olate-Download/
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
		
if ($uam->permitted('acp_view'))
{		
	// Template
	$main = $uim->fetch_template('admin/main');
	
	// Count active files
	$count_result = $dbim->query('SELECT COUNT(*) AS files
									FROM '.DB_PREFIX.'files
									WHERE (status = 1)');	
	$count = $dbim->fetch_array($count_result);
	$main->assign_var('total_files', $count['files']);
	
	// Count inactive files
	$count_result = $dbim->query('SELECT COUNT(*) AS files
									FROM '.DB_PREFIX.'files
									WHERE (status = 0)');	
	$count = $dbim->fetch_array($count_result);
	$main->assign_var('total_inactive_files', $count['files']);
	
	// Count downloads
	$count_result = $dbim->query('SELECT COUNT(*) AS downloads
									FROM '.DB_PREFIX.'stats');	
	$count = $dbim->fetch_array($count_result);
	$main->assign_var('total_downloads', $count['downloads']);
	
	// Count pending comments
	$count_result = $dbim->query('SELECT COUNT(*) AS comments
									FROM '.DB_PREFIX.'comments');	
	$count = $dbim->fetch_array($count_result);
	$main->assign_var('total_comments', $count['comments']);
	
	// Count pending comments
	$count_result = $dbim->query('SELECT COUNT(*) AS comments
									FROM '.DB_PREFIX.'comments
									WHERE (status = 0)');	
	$count = $dbim->fetch_array($count_result);
	$main->assign_var('pending_comments', $count['comments']);
	
	// Count users
	$count_result = $dbim->query('SELECT COUNT(*) AS users
									FROM '.DB_PREFIX.'users');	
	$count = $dbim->fetch_array($count_result);
	$main->assign_var('total_users', $count['users']);
	
	// http class - checks for updates
	require('./includes/http.php');
	$http = new http();
	
	// Construct fields
	$http->add_field('product', 1);
	
	// Make request (points to project releases)
	$http->post_page('https://github.com/SnatMTE/Olate-Download/releases');
	$latest_version = $http->get_content();
	
	if ($latest_version == $site_config['version'] || empty($latest_version))
	{
		// Running latest version
		$main->assign_var('up_to_date', true);
	}
	
	$main->assign_var('latest_version', $latest_version);
	
	$main->show();
}
else
{
	// User is not permitted
	$no_permission = $uim->fetch_template('admin/no_permission');
	$no_permission->show();
}
		
$end = $uim->fetch_template('global/end');
$end->show();
		
$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'index'), false);
?>