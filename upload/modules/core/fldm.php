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

// File Listing Display Module
class fldm
{
	// Get files
	function get_files($sort = 'date', $category_id = false,  $limit = false, $date_active = false)
	{
		global $dbim;
		
		$files_query = 'SELECT c.name as cat_name, c.id as cat_id, f.id, f.name, f.category_id, f.description_small, f.description_big, f.downloads, f.size, f.date, f.status, f.convert_newlines, f.activate_at 
						FROM '.DB_PREFIX.'files f, '.DB_PREFIX.'categories c 
						WHERE (f.category_id = c.id)
							AND (f.status = 1)';
		
		if ($date_active === true)
		{
			$files_query .= ' AND (f.activate_at <= '.time().')';
		}
		
		// Add categoryid
		if ($category_id)
		{
			$files_query .= ' AND (c.id = '.intval($category_id).')';			
		}

		// Sorting
		$files_query .= ' ORDER BY f.'.$sort;

		// Limit - sanitize numeric offset/count to prevent injection
		if ($limit)
		{
			if (strpos($limit, ',') !== false)
			{
				list($offset, $count) = explode(',', $limit, 2);
				$files_query .= ' LIMIT '.intval($offset).','.intval($count);
			}
			else
			{
				$files_query .= ' LIMIT '.intval($limit);
			}
		}

		$files_result = $dbim->query($files_query);
		
		$files = array();
		
		while ($file = $dbim->fetch_array($files_result))
		{
			// Format the date
			$file['date'] = format_date($file['date']);
			
			$files[] = $file;
		}
		
		return $files;
	}
	
	// Get file details
	function get_details($file_id, $hide_status = 0, $date_active = false)
	{
		global $dbim;
		
		$status_conditional = '';
		
		// We might not want to hide any statuses
		if ($hide_status !== false)
		{
			$status_conditional .= 'AND (f.status != '.$hide_status.')';
		}
		
		if ($date_active === true)
		{
			$status_conditional .= ' AND (f.activate_at <= '.time().')';
		}
		
		$sql = 'SELECT c.name as cat_name, c.id as cat_id, f.id, f.name, f.category_id, f.description_small, f.description_big, f.downloads, f.size, f.date, f.status, f.convert_newlines, f.views, f.rating_votes, f.rating_value, f.keywords, f.agreement_id, f.password, f.activate_at
				FROM '.DB_PREFIX.'files f
				LEFT JOIN '.DB_PREFIX.'categories c ON f.category_id = c.id
				WHERE (f.id = '.$file_id.')
						'.$status_conditional;
		
		$details_result = $dbim->query($sql);
		
		echo ($date_active) ? $sql : '';
		
		$details = $dbim->fetch_array($details_result);
		
		if (!empty($details))
		{
			$details['size'] = (float)$details['size'];
		}
		
		return $details;
	}
	
	// Get file agreement
	function get_agreement($agreement_id)
	{
		global $dbim;
		
		if ($agreement_id)
		{
			$agreement_result = $dbim->query('SELECT contents 
												FROM '.DB_PREFIX.'agreements 
												WHERE (id = '.$agreement_id.')');
		}
		
		$agreement = $dbim->fetch_array($agreement_result);
		
		return $agreement['contents'];
	}
	
	// Create page box
	function make_page_box($files, $link, $amount = false)
	{
		global $uim, $site_config;
		
		// Find the current page
		if (isset($_GET['page']) && is_numeric($_GET['page']))
		{
			$current_page = max(1, intval($_GET['page']));
		}
		else
		{
			$current_page = 1;
		}
		
		// Find how many files there are	
		$total = count($files);
		
		if (!$amount)
		{
			$amount = $site_config['page_amount'];
		}
		
		// Work out how many pages (I hate maths)
		$pages = ceil($total/$amount);
		
		// Get the template
		$page_box = $uim->fetch_template('global/pagination');
		$page_box->assign_var('link', $link);
		
		// Assign the current page
		$page_box->assign_var('current_page', $current_page);
		
		// We don't want a page box if there is only < 1 pages
		if ($pages > 1)
		{
			// Go through each number
			for ($page = 1; $page <= $pages; $page++)
			{			
				$page_box->assign_var('page', $page);
				$page_box->use_block('page');
			}
	
			// Show selector
			return $page_box->show(true);
		}
		else
		{
			return '';
		}
	}
	
	// Display comments
	function display_toolbox($file_id, $current_data = false, $page = false)
	{	
		global $dbim, $uim, $site_config;
				// Sanitize inputs
		$file_id = intval($file_id);
		$page = (isset($page) && is_numeric($page)) ? max(1, intval($page)) : 1;
				// If search isn't enabled, only show the rate box
		if (!$site_config['enable_comments'])
		{
			$rate = $uim->fetch_template('files/rate');
			$rate->assign_var('file_id', $file_id);
			$rate->show();
		}
		else
		{
			// Amount of search results per page
			$amount = $site_config['page_amount'];
			
			// Get all results for the page box
			$comments_result = $dbim->query('SELECT id, file_id, timestamp, name, email, comment, status
												FROM '.DB_PREFIX.'comments 
												WHERE (file_id = '.$file_id.') 
													AND (status = 1)
												ORDER BY timestamp ASC');
												
			$results = array();
		
			while ($result = $dbim->fetch_array($comments_result))
			{
				$results[] = $result;
			}		
			
			// page was sanitized above; compute offset
			$offset = ($page - 1) * $amount;
			
			
			// Get all results for the page box
			$comments_result = $dbim->query('SELECT id, file_id, timestamp, name, email, comment, status
												FROM '.DB_PREFIX.'comments 
												WHERE (file_id = '.$file_id.') 
													AND (status = 1)
												ORDER BY timestamp ASC
												LIMIT '.$offset.','.$amount);					
												
			$toolbox = $uim->fetch_template('files/toolbox');
			
			while ($comment = $dbim->fetch_array($comments_result))
			{
				$toolbox->assign_var('comment', $comment);
				$toolbox->assign_var('date', format_date($comment['timestamp']));
				$toolbox->assign_var('time', date('G:i:s', $comment['timestamp']));
				
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
				
				$toolbox->assign_var('text', nl2br($comment['comment']));
				$toolbox->use_block('comment');
			}
			
			// If some data is given, assign it
			if ($current_data)
			{
				$toolbox->assign_var('data', $current_data);
			}	
			
			// Generate pagebox
			$pagination = $this->make_page_box($results,'details.php?file='.$file_id.'&', $amount);
			$toolbox->assign_var('pagination', $pagination);
			
			$toolbox->show();
			
			// End
			$toolbox_end = $uim->fetch_template('files/toolbox_end');
			$toolbox_end->assign_var('file_id', $file_id);
			$toolbox_end->show();
		}
	}
	
	// Convert filesize from bytes to whatever
	function format_size($filesize)
	{
		global $site_config, $lm;
		
		// Get filesize as integer
		$filesize = floatval($filesize);
		
		// Inititalise return var
		$return = array();
		
		// Pick a suitable unit or use specific one?
		if ($site_config['filesize_format'] == '--')
		{
			// Gigabyte
			if ($filesize >= pow(1024,3))
			{
				$return['size'] = round($filesize / pow(1024,3), 2);
				$return['unit'] = $lm->language('admin', 'file_size_format_gb');
			}
			// Megabyte
			elseif ($filesize >= pow(1024,2))
			{
				$return['size'] = round($filesize / pow(1024,2), 2);
				$return['unit'] = $lm->language('admin', 'file_size_format_mb');
			}
			// Kilobyte
			elseif ($filesize >= 1024)
			{
				$return['size'] = round($filesize / 1024, 2);
				$return['unit'] = $lm->language('admin', 'file_size_format_kb');
			}
			else
			{
				$return['size'] = $filesize;
				$return['unit'] = $lm->language('admin', 'file_size_format_b');
			}
		}
		else
		{
			// Gigabyte
			if ($site_config['filesize_format'] == $lm->language('admin', 'file_size_format_gb'))
			{
				$return['size'] = round($filesize / pow(1024,3), 2);
				$return['unit'] = $lm->language('admin', 'file_size_format_gb');
			}
			// Megabyte
			elseif ($site_config['filesize_format'] == $lm->language('admin', 'file_size_format_mb'))
			{
				$return['size'] = round($filesize / pow(1024,2), 2);
				$return['unit'] = $lm->language('admin', 'file_size_format_mb');
			}
			// Kilobyte
			elseif ($site_config['filesize_format'] == $lm->language('admin', 'file_size_format_kb'))
			{
				$return['size'] = round($filesize / 1024, 2);
				$return['unit'] = $lm->language('admin', 'file_size_format_kb');
			}
			else
			{
				$return['size'] = $filesize;
				$return['unit'] = $lm->language('admin', 'file_size_format_b');
			}
		}
		
		
		return $return;
	}
}
?>