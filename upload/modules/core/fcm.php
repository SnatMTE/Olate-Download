<?php
/**********************************
* Olate Download 3.5.0
* https://github.com/SnatMTE/Olate-Download/
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 200 $
* @package od
*
* Original Author: Olate Download
* Updated by: Snat
* Last-Edited: 2025-12-16
*/

// File Category Module
class fcm 
{
	// Gets the root categories with one level of children
	function get_root_cats()
	{
		global $dbim;
		
		// Get all root categories (parent_id = the big daddy, otherwise known as the big 0)
		$cat_result = $dbim->query('SELECT id, name, description, sort 
									FROM '.DB_PREFIX.'categories 
									WHERE (parent_id = 0)
									ORDER BY sort ASC');
		
		while ($category = $dbim->fetch_array($cat_result))
		{
			$categories[$category['name']] = $category;
			
			// Now have children						
			$child_result = $dbim->query('SELECT id, name, description, sort 
											FROM '.DB_PREFIX.'categories 
											WHERE (parent_id = '.$category['id'].')
											ORDER BY sort ASC');
			
			while ($child = $dbim->fetch_array($child_result))
			{
				$categories[$category['name']]['children'][$child['name']] = $child;
			}	
		}
	
		return $categories;		
	}
	
	// Get all categories
	function get_all_cats()
	{
		global $dbim;
		
		// Get all root categories (parent_id = the big daddy, otherwise known as the big 0)
		$cat_result = $dbim->query('SELECT id, name, description, sort 
									FROM '.DB_PREFIX.'categories 
									ORDER BY sort ASC');
		
		while ($category = $dbim->fetch_array($cat_result))
		{
			$categories[$category['name']] = $category;
		}
	
		return $categories;		
	}		
	
	// Retrieves a category
	function get_cat($category_id)
	{
		global $dbim, $lm;
		
		if ($category_id != 0)
		{
			$cat_result = $dbim->query('SELECT id, parent_id, name, description, keywords 
										FROM '.DB_PREFIX.'categories 
										WHERE (id = '.$category_id.')');
									
			$category = $dbim->fetch_array($cat_result);
		}
		else
		{
			$category = array(
				'id' => 0,
				'parent_id' => false,
				'name' => $lm->language('admin', 'private_files'),
				'description' => $lm->language('admin', 'private_files')
			);
		}
		
		return $category;
	}	
	
	// Retrieves a categories direct children
	function get_children($category_id)
	{
		global $dbim;
		
		$child_result = $dbim->query('SELECT id, name, description 
										FROM '.DB_PREFIX.'categories 
										WHERE (parent_id = '.$category_id.')
										ORDER BY sort ASC');
		
		while ($child = $dbim->fetch_array($child_result))
		{
			$children[] = $child;
		}
		
		return $children;
	}
	
	// Shows the root categories, with one level of children
	function show_cats()
	{
		global $uim, $dbim, $site_config;
		
		// Listings module
		require_once 'modules/core/listings.php';
		$listing = new listing();
		
		// Are we only showing categories that have files in them or in their children
		if ($site_config['filter_cats'] == 1)
		{
			$cat_heirachy = $listing->filter_cats('activate_at <= "'.time().'"', $listing->cat_heirachy);
		}
		else
		{
			$cat_heirachy = $listing->cat_heirachy;
		}
		
		// Create the template
		$template = $uim->fetch_template('global/categories');

		// Go through each category
		foreach ($cat_heirachy as $category_id => $children)
		{
			$category = $this->get_cat($category_id);
			
			$category['file_count'] = intval($this->count_files($category_id, 'status = 1', true, true));
			
			// Now children, be good
			if (is_array($children))
			{
				$template->assign_var('has_children', true);
				
				// Go through children
				foreach ($children as $child_id => $grandchildren)
				{
					$child = $this->get_cat($child_id);
					
					$child['file_count'] = intval($this->count_files($child_id, 'status = 1', true, true));
					
					$template->assign_var('child', $child);
					$template->use_block('cat_child');							
				}
			}
			else
			{
				$template->assign_var('has_children', false);
			}
				
			$template->assign_var('category', $category);
			$template->use_block('cat_row');
				
			// Clear the child block, so they dont show
			// in the next category
			$template->clear_block('cat_child');
		}
		
		$template->show();
	}
	
	// Alias to listing::list_cat_combo_box()
	function generate_category_list(&$template, $variable, $block, $parent_id = 0)
	{
		// Include module
		require_once('modules/core/listings.php');
		$listing = new listing();
		
		// Call function
		$listing->list_cat_combo_box($template, $variable, $block);
	}
	
	// Count files in a category - return false if no files
	function count_files($category_id, $where_clause = '', $date_active = false, $recursive = false)
	{
		global $dbim;
		
		if ($recursive === true)
		{
			$row['count'] = intval($this->count_files_recursive($category_id, $where_clause, $date_active));
		}
		else 
		{
			// Query
			$sql = 'SELECT COUNT(id) AS count 
					FROM '.DB_PREFIX.'files 
					WHERE category_id = '.$category_id;
			
			if ($where_clause != '')
			{
				$where_condition = '('.$where_clause.')';
				$sql .= ' AND '.$where_condition;
			}
			
			if ($date_active === true)
			{
				$date_condition = '(activate_at <= "'.time().'")';
				$sql .= ' AND '.$date_condition;
			}
			
			$result = $dbim->query($sql);
			$row = $dbim->fetch_array($result);
		}
		
		// Return something...
		if ($row['count'] > 0)
		{
			return $row['count'];
		}
		else
		{
			return false;
		}
	}
	
	// Recursive file counting
	function count_files_recursive($category_id, $where_clause = '', $date_active = false)
	{
		global $dbim;
		
		require_once('modules/core/listings.php');
		
		$listing = new listing($category_id);
		
		if (sizeof($listing->all_cats) > 0)
		{
			$sql = 'SELECT COUNT(*) AS count FROM '.DB_PREFIX.'files 
					WHERE category_id IN ('.implode(',',array_map('intval', $listing->all_cats)).')';
			
			if ($where_clause != '')
			{
				$where_condition = '('.$where_clause.')';
				$sql .= ' AND '.$where_condition;
			}
			
			if ($date_active === true)
			{
				$date_condition = '(activate_at <= "'.time().'")';
				$sql .= ' AND '.$date_condition;
			}
			
			$result = $dbim->query($sql);
			$row = $dbim->fetch_array($result);
			
			$file_count = $row['count'];
		}
		
		return $file_count;
	}
}
?>