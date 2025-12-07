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

if ($uam->permitted('acp_main_settings'))
{		
	// Template
	$settings = $uim->fetch_template('admin/main_settings');
	
	// Check existance of FCKeditor
	if (file_exists('FCKeditor/fckeditor.php'))
	{
		$settings->assign_var('wysiwyg_disabled', true);
	}
	else
	{
		$settings->assign_var('wysiwyg_disabled', false);
	}
	
	// Make any changes
	if (isset($_REQUEST['submit']))
	{
		validate_types($_REQUEST, array('site_name' => 'STR_HTML', 'url' => 'STR', 'admin_email' => 'STR', 'flood_interval' => 'INT', 'language' => 'STR', 
										'template' => 'STR', 'date_format' => 'STR', 'filesize_format' => 'STR', 'mirrors' => 'INT', 
										'page_amount' => 'INT', 'latest_files' => 'INT', 'enable_topfiles' => 'INT', 'top_files' => 'INT', 'enable_allfiles' => 'INT', 
										'enable_comments' => 'INT', 'approve_comments' => 'INT', 'enable_search' => 'INT', 'enable_ratings' => 'INT', 'enable_stats' => 'INT', 
										'enable_rss' => 'INT', 'enable_count' => 'INT', 'enable_useruploads' => 'INT', 'enable_leech_protection' => 'INT', 'uploads_allowed_ext' => 'STR',
										'filter_cats' => 'INT', 'enable_recommend_friend' => 'INT', 'enable_recommend_confirm' => 'INT', 'acp_check_extensions' => 'INT', 'allow_user_lang' => 'INT'));
	
		// Checkboxes are soooo annoying
		$_REQUEST['enable_topfiles'] = (!isset($_REQUEST['enable_topfiles'])) ? 0 : 1;
		$_REQUEST['enable_allfiles'] = (!isset($_REQUEST['enable_allfiles'])) ? 0 : 1;
		$_REQUEST['enable_comments'] = (!isset($_REQUEST['enable_comments'])) ? 0 : 1;
		$_REQUEST['approve_comments'] = (!isset($_REQUEST['approve_comments'])) ? 0 : 1;
		$_REQUEST['enable_search'] = (!isset($_REQUEST['enable_search'])) ? 0 : 1;
		$_REQUEST['enable_stats'] = (!isset($_REQUEST['enable_stats'])) ? 0 : 1;
		$_REQUEST['enable_rss'] = (!isset($_REQUEST['enable_rss'])) ? 0 : 1;
		$_REQUEST['enable_count'] = (!isset($_REQUEST['enable_count'])) ? 0 : 1;
		$_REQUEST['enable_useruploads'] = (!isset($_REQUEST['enable_useruploads'])) ? 0 : 1;
		$_REQUEST['enable_actual_upload'] = (!isset($_REQUEST['enable_actual_upload'])) ? 0 : 1;
		$_REQUEST['enable_mirrors'] = (!isset($_REQUEST['enable_mirrors'])) ? 0 : 1;
		$_REQUEST['enable_leech_protection'] = (!isset($_REQUEST['enable_leech_protection'])) ? 0 : 1;
		$_REQUEST['userupload_always_approve'] = (!isset($_REQUEST['userupload_always_approve'])) ? 0 : 1;
		$_REQUEST['filter_cats'] = (!isset($_REQUEST['filter_cats'])) ? 0 : 1;
		$_REQUEST['enable_recommend_friend'] = (!isset($_REQUEST['enable_recommend_friend'])) ? 0 : 1;
		$_REQUEST['enable_recommend_confirm'] = (!isset($_REQUEST['enable_recommend_confirm'])) ? 0 : 1;
		$_REQUEST['acp_check_extensions'] = (!isset($_REQUEST['acp_check_extensions'])) ? 0 : 1;
		$_REQUEST['use_fckeditor'] = (!isset($_REQUEST['use_fckeditor'])) ? 0 : 1;
		$_REQUEST['allow_user_lang'] = (!isset($_REQUEST['allow_user_lang'])) ? 0 : 1;
		
		// And finally, the SQL
		$dbim->query('UPDATE '.DB_PREFIX.'config 
						SET site_name = "'.htmlspecialchars($_REQUEST['site_name']).'", 
							url = "'.$_REQUEST['url'].'",
							flood_interval = "'.$_REQUEST['flood_interval'].'", 
							admin_email = "'.$_REQUEST['admin_email'].'", 
							language = "'.$_REQUEST['language'].'", 
							template = "'.$_REQUEST['template'].'", 
							date_format = "'.$_REQUEST['date_format'].'", 
							filesize_format = "'.$_REQUEST['filesize_format'].'", 
							mirrors = "'.$_REQUEST['mirrors'].'", 
							page_amount = "'.$_REQUEST['page_amount'].'", 
							latest_files = "'.$_REQUEST['latest_files'].'",
							enable_topfiles = "'.$_REQUEST['enable_topfiles'].'",  
							top_files = "'.$_REQUEST['top_files'].'", 
							enable_allfiles = "'.$_REQUEST['enable_allfiles'].'", 
							enable_comments = "'.$_REQUEST['enable_comments'].'", 
							approve_comments = "'.$_REQUEST['approve_comments'].'", 
							enable_search = "'.$_REQUEST['enable_search'].'", 
							enable_ratings = "'.$_REQUEST['enable_ratings'].'",
							enable_stats = "'.$_REQUEST['enable_stats'].'", 
							enable_rss = "'.$_REQUEST['enable_rss'].'",
							enable_count = "'.$_REQUEST['enable_count'].'",
							enable_useruploads  = "'.$_REQUEST['enable_useruploads'].'",
							enable_actual_upload  = "'.$_REQUEST['enable_actual_upload'].'",
							enable_mirrors  = "'.$_REQUEST['enable_mirrors'].'",
							enable_leech_protection  = "'.$_REQUEST['enable_leech_protection'].'",
							uploads_allowed_ext  = "'.$_REQUEST['uploads_allowed_ext'].'",
							userupload_always_approve  = "'.$_REQUEST['userupload_always_approve'].'",
							filter_cats  = "'.$_REQUEST['filter_cats'].'",
							enable_recommend_friend  = "'.$_REQUEST['enable_recommend_friend'].'",
							enable_recommend_confirm  = "'.$_REQUEST['enable_recommend_confirm'].'",
							acp_check_extensions  = "'.$_REQUEST['acp_check_extensions'].'",
							use_fckeditor  = "'.$_REQUEST['use_fckeditor'].'",
							allow_user_lang  = "'.$_REQUEST['allow_user_lang'].'"
						LIMIT 1');
							
		$success = true; // For redirect EOF
		$settings->assign_var('success', true);
		
		// Get the new values							
		foreach ($_REQUEST as $key => $value)
		{
			$settings->assign_var($key, $value);
		}
	}
	elseif (isset($_REQUEST['stats_reset']) && $_REQUEST['stats_reset'] == 1)
	{
		// Reset statistics
		$dbim->query('TRUNCATE TABLE '.DB_PREFIX.'stats');
		$dbim->query('UPDATE '.DB_PREFIX.'files
						SET downloads = 0,
							views = 0');
		
		$success = true; // For redirect EOF
		$settings->assign_var('reset', true);
	}
	else
	{
		// Get the values obtained in init.php							
		foreach ($site_config as $key => $value)
		{
			$settings->assign_var($key, $value);
		}
		
		// Get path
		$path = 'templates/';  
		
		// Using the opendir function
		$dir_handle = opendir($path); 
		
		// Running the while loop
		while ($file = readdir($dir_handle)) 
		{
			// . and .. are displayed so remove them
			if (($file != '.') && ($file != '..') && ($file != 'CVS') && ($file != '.svn'))
			{
				// Get the language config.php file so data can be displayed
				$settings->assign_var('template_name', $file);
				$settings->use_block('templates');
			}
		} 
		
		// Close directory
		closedir($dir_handle);
	}
	
	$settings->show();
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
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'main').' - '.$lm->language('admin', 'general_settings'), false);
}
else
{
	$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('admin', 'main').' - '.$lm->language('admin', 'general_settings'), 'admin.php?cmd=main_settings');
}
?>