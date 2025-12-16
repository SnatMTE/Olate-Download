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

// Initialisation
require('./includes/init.php');

// Amount of search results per page
$amount = $site_config['page_amount'];

// Show categories
$fcm->show_cats();

// Start sessions
session_start();
$_SESSION['valid_user'] = true;

if ($site_config['enable_search'])
{
	// Get template
	$search_template = $uim->fetch_template('search/search');
	
// Validate query parameter (early) and prepare safe version
	if (isset($_REQUEST['query']) && !empty($_REQUEST['query']))
	{
		validate_types($_REQUEST, array('query' => 'STR'));
		$safe_query = $_REQUEST['query'];
		// Get all results for the page box (unsliced)
		$search_result = $dbim->query('SELECT id, name, description_small, description_big, date
						FROM '.DB_PREFIX.'files 
						WHERE MATCH (name, description_small, description_big) 
							AGAINST ("'.$safe_query.'" IN BOOLEAN MODE)
								AND (category_id != 0)
									AND (status != 0)');
	}
	else
	{
		$safe_query = '';
	}
	
	$results = array();
	
	while ($result = $dbim->fetch_array($search_result))
	{
		$results[] = $result;
	}
	
	// Checks
	if ($safe_query !== '')
	{
		// Has a page been given
		$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? max(1, intval($_GET['page'])) : 1;
		
		// Get result (sliced)
		$offset = ($page - 1) * $amount;
		$search_result = $dbim->query('SELECT id, name, description_small, description_big, date
						FROM '.DB_PREFIX.'files 
						WHERE MATCH (name, description_small, description_big) 
							AGAINST ("'.$safe_query.'" IN BOOLEAN MODE)
								AND (category_id != 0)
									AND (status != 0)
						LIMIT '.$offset.','.$amount);
		// Display
		while ($result = $dbim->fetch_array($search_result))
		{
			$search_template->assign_var('result', $result);
			$search_template->assign_var('date', format_date($result['date']));
			$search_template->use_block('search');
		}
		
		$submitted = true;
		
		$search_template->assign_var('query', $_REQUEST['query']);
		$search_template->assign_var('num_results', $dbim->num_rows($search_result));
		$search_template->assign_var('submitted', $submitted);
	}
	
	if (isset($submitted))
	{
		// Show pagebox
		$pagination = $fldm->make_page_box($results,'search.php?query='.urlencode($safe_query).'&amp;', $amount);
		$search_template->assign_var('pagination', $pagination);
	}
	
	// Show template
	$search_template->assign_var('query', $safe_query);
	$search_template->show();
	
	// End
	$search_end = $uim->fetch_template('search/search_end');
	$search_end->show();
	
	$end = $uim->fetch_template('global/end');
	$end->show();
	
	// Show everything
	$uim->generate(TITLE_PREFIX.'Search');
}
else
{
	// Get template
	$search_template = $uim->fetch_template('search/search_disabled');
	
	// Show template
	$search_template->show();
	
	// End table
	$end = $uim->fetch_template('global/end');
	$end->show();
	
	// Show everything
	$uim->generate(TITLE_PREFIX.$lm->language('frontend', 'search'));
}
?>