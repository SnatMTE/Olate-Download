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

// Inititalisation
include_once('includes/init.php');

// Categories/left bar
$fcm->show_cats();

validate_types($_REQUEST, array('language' => 'STR'));

if ($site_config['allow_user_lang'])
{
	// Template
	$template = $uim->fetch_template('general/set_language');
	
	if (!empty($_REQUEST['language']))
	{
		// Check language exists in database
		$search = $dbim->query('SELECT COUNT(*) AS count
								FROM '.DB_PREFIX.'languages
								WHERE id = '.intval($_REQUEST['language']));
		
		$row = $dbim->fetch_array($search);
		
		if ($row['count'] > 0)
		{
			setcookie('OD3_language', intval($_REQUEST['language']), time() + 60*60*24*365);
			$success = true;
			$template->assign_var('success', true);
		}
	}
	else
	{
		// Fetch language list
		$languages = $lm->list_languages();
		
		foreach ($languages as $language)
		{
			$template->assign_var('language', $language);
			$template->use_block('languages');
		}
		
		// Get current language
		$current = array(
			'name' => $lm->language['config']['full_name'],
			'id' => $lm->language_row['id']
		);
		
		$template->assign_var('current', $current);
	}
	
	// Show template
	$template->show();
}
else 
{
	$template = $uim->fetch_template('general/set_language_disabled');
	$template->show();
}

// End the page and generate
$end = $uim->fetch_template('global/end');
$end->show();

if (!isset($success) || $success != true)
{
	$uim->generate(TITLE_PREFIX . $lm->language('frontend', 'change_language'));
}
else 
{
	$uim->generate(TITLE_PREFIX . $lm->language('frontend', 'change_language'), 'language.php');
}

?>