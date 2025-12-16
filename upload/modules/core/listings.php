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

class listing
{
	var $categories = array();
	var $cat_heirachy = array();
	var $all_cats = array();
	
	var $file_results = array();
	
	var $where_cat = '';
	var $where_file = '';
	
	function listing($parent_id = 0, $where_cat = '', $where_file = '')
	{
		// Set variables
		$this->where_cat = $where_cat;
		$this->where_file = $where_file;
		
		// Add parent to all cats array if > 0
		if ($parent_id > 0)
		{
			$this->all_cats[] = intval($parent_id);
		}
		
		// Get hierachy
		$this->cat_heirachy = $this->get_cats($parent_id);
	}
	
	function get_cats($parent_id = 0)
	{
		global $dbim;
		
		$sql = 'SELECT id 
				FROM '.DB_PREFIX.'categories 
				WHERE parent_id = '.$parent_id;
		
		if ($this->where_cat != '')
		{
			$sql .= ' AND ('.$this->where_cat.')';
		}
		
		// Sort by category order
		$sql .= ' ORDER BY sort ASC';
		
		$result = $dbim->query($sql);
		
		$this_level = array();
		
		while ($row = $dbim->fetch_array($result))
		{
			$id = $row['id'];
			
			$this->all_cats[] = intval($id);
			
			$cat_sql = 'SELECT COUNT(*) AS count 
						FROM '.DB_PREFIX.'categories 
						WHERE parent_id = '.$row['id'];
			
			if ($this->where_cat != '')
			{
				$cat_sql .= ' AND ('.$this->where_cat.')';
			}	
			
			$cat_res = $dbim->query($cat_sql);
			$cat_row = $dbim->fetch_array($cat_res);
			
			if ($cat_row['count'] > 0)
			{
				$children = $this->get_cats($id);
			}
			else
			{
				$children = '';
			}
			
			$this_level[$id] = $children;
		}
		
		return $this_level;
	}
	
	function get_files($category_id)
	{
		global $dbim;
		
		if (empty($this->file_results[$category_id]))
		{
			$sql = 'SELECT * 
					FROM '.DB_PREFIX.'files
					WHERE category_id = '.$category_id;
			
			if ($this->where_file != '')
			{
				$sql .= ' AND ('.$this->where_file.')';
			}	
			
			$this->file_results[$category_id]['result'] = $dbim->query($sql);
		}
		
		$row = $dbim->fetch_array($this->file_results[$category_id]['result']);
		
		return $row;
	}
	
	function list_cat_file_div($self_query_string = false, $cat_link = false, 
								$file_link = false, $cat_level = false, 
								$files_for = array(), $show_options_links = true)
	{
		global $fcm, $fldm, $lm, $uim;
		
		// Variables
		$link_to = $_SERVER['PHP_SELF'];
		$enable_show_files = true;
		
		if ((strlen($self_query_string) > 0) && (substr($self_query_string, -5, 5) != '&amp;'))
		{
			$self_query_string .= '&amp;';
		}
		
		if ($cat_level === false)
		{
			$cat_level =& $this->cat_heirachy;
		}
		
		if ($files_for == -1)
		{
			$enable_show_files = false;
		}
		
		// What categories do we need to list files for?
		if ($files_for === false)
		{
			$files_for = array();
		}
		elseif (!is_array($files_for))
		{
			$files_for = ereg_replace('(,){2,}', ',', $files_for);
			$files_for = ereg_replace('^(0,)+', '', $files_for);
			$files_for = explode(',', $files_for);
			$files_for = array_map('intval', $files_for);
		}
		
		// Template
		$parent_template = $uim->fetch_template('admin/list_cat_file_div');
		
		foreach ($cat_level as $category_id => $children)
		{
			// Category info - used quite a bit
			$category = $fcm->get_cat($category_id);
			$file_count = $fcm->count_files($category_id, $this->where_file);
			
			// Are we displaying files (0/1) or are there no files (2)
			if ($file_count > 0)
			{
				if (in_array($category_id, $files_for))
				{
					$display_files = 1;
				}
				else
				{
					$display_files = 0;
				}
			}
			else
			{
				$display_files = 2;
			}
			
			// Links on the categories?
			if (!empty($cat_link['link']))
			{
				$link = $cat_link['link'];
				
				if (!empty($cat_link['query']))
				{
					$link .= '?'.$cat_link['query'];
				}
				
				$link = str_replace('#cat_id#', $category_id, $link);
				
				$cat_label = '<a href="'.$link.'">'.$category['name'].'</a>';
			}
			else
			{
				$cat_label = $category['name'];
			}
			
			// Showing files?
			if ($enable_show_files !== false)
			{
				if ($display_files == 1)
				{
					// Get copy of array, if not everything breaks with recursing
					$files_for_local = $files_for;
					
					// Find category and remove
					$cat_key = array_search($category_id, $files_for_local);
					unset($files_for_local[$cat_key]);
					
					// Build link
					$files_for_string = implode(',', $files_for_local);
					$files_for_string = $this->trim_files_for($files_for_string);
					
					$show_files_link = '<a href="'.$link_to.'?'.$self_query_string.'files_for='.$files_for_string.'">'.$lm->language('admin', 'hide_files').'</a>';
				}
				elseif ($display_files == 0)
				{
					// Build link
					$files_for_string = implode(',', $files_for);
					$files_for_string .= ','.$category_id;
					$files_for_string = $this->trim_files_for($files_for_string);
					
					$show_files_link = '<a href="'.$link_to.'?'.$self_query_string.'files_for='.$files_for_string.'">'.$lm->language('admin', 'show_files').'</a>';
				}
				else
				{
					$show_files_link = $lm->language('admin', 'no_files');
				}
				
				$separator = '/';
			}
			else
			{
				$separator = '';
				$show_files_link = '';
			}
			
			// Show toggle children visibility?
			if (is_array($children) || $display_files == 1)
			{
				$toggle_link = '<a href="javascript:collapse_custom('.$category['id'].", '".$lm->language('admin', 'show_children')."', '".$lm->language('admin', 'hide_children').'\')" id="button'.$category['id'].'">'.$lm->language('admin', 'hide_children').'</a>';
			}
			else
			{
				$toggle_link = $lm->language('admin', 'no_children');
			}
			
			// Output the options if needed
			if (!empty($show_files_link) || !empty($toggle_link) && $show_options_links !== false)
			{
				$options_links = '('.$show_files_link.$separator.$toggle_link.')';
			}
			else
			{
				$options_links = '';
			}
			
			// Has children or files?
			if (is_array($children) || $display_files == 1)
			{
				// Tell template we have children stuff
				$parent_template->assign_var('has_children', true);
			}
			else
			{
				// Tell template we have children stuff
				$parent_template->assign_var('has_children', false);
			}
			
			if (is_array($children))
			{
				// Recurse... oh what a pain in the backside :(
				if ($enable_show_files)
				{
					$children_return = $this->list_cat_file_div($self_query_string, $cat_link, $file_link, $children, $files_for, $show_options_links);
				}
				else
				{
					$children_return = $this->list_cat_file_div($self_query_string, $cat_link, $file_link, $children, -1, $show_options_links);
				}
				
				// Get output and assign
				$children_output = $children_return->show(true);
				
				$parent_template->assign_var('children_output', $children_output);
			}
			else
			{
				$parent_template->assign_var('children_output', '');
			}
			
			// Display files?
			if ($display_files == 1)
			{
				// Tell template we have files
				$parent_template->assign_var('has_files', true);
				
				while ($file_row = $this->get_files($category['id']))
				{
					// Are the files to be linkable?
					if (!empty($file_link['link']))
					{
						$link = $file_link['link'];
						
						if (!empty($file_link['query']))
						{
							$link .= '?'.$file_link['query'];
						}
						
						$link = str_replace('#file_id#', $file_row['id'], $link);
						
						$parent_template->assign_var('has_link', true);
						$parent_template->assign_var('link_url', $link);
					}
					
					$parent_template->assign_var('file_label', $file_row['name']);
					$parent_template->use_block('file');
				}
			}
			else
			{
				// Tell template we're not doing files this time
				$parent_template->assign_var('has_files', false);
			}
			
			// Check $file_count is integer
			$file_count = intval($file_count);
			
			// Assign variables and then use block
			$parent_template->assign_var('category', $category);
			$parent_template->assign_var('file_count', $file_count);
			
			$parent_template->assign_var('cat_label', $cat_label);
			$parent_template->assign_var('options_links', $options_links);
			$parent_template->use_block('category');
			$parent_template->clear_block('file');
		}
		return $parent_template;
	}
	
	function trim_files_for($files_for)
	{
		// Remove excess commas
		$files_for = ereg_replace('^(,)+', '', $files_for);
		$files_for = ereg_replace('(,)+$', '', $files_for);
		$files_for = ereg_replace('(,){2,}', ',', $files_for);
		
		return $files_for;
	}
	
	function list_cat_file_check($self_query_string = false, $cat_level = false, 
									$files_hide = array(), $show_options_links = true)
	{
		global $fcm, $fldm, $lm, $uim;
		
		// Variables
		$link_to = $_SERVER['PHP_SELF'];
		$enable_show_files = true;
		
		if ((strlen($self_query_string) > 0) && (substr($self_query_string, -5, 5) != '&amp;'))
		{
			$self_query_string .= '&amp;';
		}
		
		if ($cat_level === false)
		{
			$cat_level =& $this->cat_heirachy;
		}
		
		// What categories do we need to list files for?
		if ($files_hide === false)
		{
			$files_hide = array();
		}
		elseif (!is_array($files_hide))
		{
			$files_hide = ereg_replace('(,){2,}', ',', $files_hide);
			$files_hide = ereg_replace('^(0,)+', '', $files_hide);
			$files_hide = explode(',', $files_hide);
			$files_hide = array_map('intval', $files_hide);
		}
		
		// Template
		$parent_template = $uim->fetch_template('admin/list_cat_file_check');
		
		foreach ($cat_level as $category_id => $children)
		{
			// Category info - used quite a bit
			$category = $fcm->get_cat($category_id);
			$file_count = $fcm->count_files($category_id, $this->where_file);
			
			// Are we displaying files (0/1) or are there no files (2)
			if ($file_count > 0)
			{
				if (!in_array($category_id, $files_hide))
				{
					$display_files = 1;
				}
				else
				{
					$display_files = 0;
				}
			}
			else
			{
				$display_files = 2;
			}
			
			// Links on the categories?
			$cat_label = $category['name'];
			
			// Showing files?
			if ($enable_show_files !== false)
			{
				if ($display_files == 1)
				{
					// Get copy of array, if not everything breaks when recursing
					$files_hide_local = $files_hide;
					
					// Find category and remove
					$cat_key = array_search($category_id, $files_hide_local);
					unset($files_hide_local[$cat_key]);
					
					// Build link
					$files_hide_string = implode(',', $files_hide_local);
					$files_hide_string = $this->trim_files_for($files_hide_string);
					
					$show_files_link = '<a href="'.$link_to.'?'.$self_query_string.'files_hide='.$files_hide_string.'">'.$lm->language('admin', 'hide_files').'</a>';
				}
				elseif ($display_files == 0)
				{
					// Build link
					$files_hide_string = implode(',', $files_hide);
					$files_hide_string .= ','.$category_id;
					$files_hide_string = $this->trim_files_hide($files_hide_string);
					
					$show_files_link = '<a href="'.$link_to.'?'.$self_query_string.'files_hide='.$files_hide_string.'">'.$lm->language('admin', 'show_files').'</a>';
				}
				else
				{
					$show_files_link = $lm->language('admin', 'no_files');
				}
				
				$separator = '/';
			}
			else
			{
				$separator = '';
				$show_files_link = '';
			}
			
			// Show toggle children visibility?
			if (is_array($children) || $display_files == 1)
			{
				$toggle_link = '<a href="javascript:collapse_custom('.$category['id'].", '".$lm->language('admin', 'show_children')."', '".$lm->language('admin', 'hide_children').'\')" id="button'.$category['id'].'">'.$lm->language('admin', 'hide_children').'</a>';
			}
			else
			{
				$toggle_link = $lm->language('admin', 'no_children');
			}
			
			// Output the options if needed
			if (!empty($show_files_link) || (!empty($toggle_link) && $show_options_links !== false))
			{
				$options_links = '('.$show_files_link.$separator.$toggle_link.')';
			}
			else
			{
				$options_links = '';
			}
			
			// Has children or files?
			if (is_array($children) || $display_files == 1)
			{
				// Tell template we have children stuff
				$parent_template->assign_var('has_children', true);
			}
			else
			{
				// Tell template we have children stuff
				$parent_template->assign_var('has_children', false);
			}
			
			if (is_array($children))
			{
				// Recurse... oh what a pain in the backside :(
				if ($enable_show_files)
				{
					$children_return = $this->list_cat_file_check($self_query_string, $children, $files_hide, $show_options_links);
				}
				else
				{
					$children_return = $this->list_cat_file_check($self_query_string, $children, -1, $show_options_links);
				}
				
				// Get output and assign
				$children_output = $children_return->show(true);
				
				$parent_template->assign_var('children_output', $children_output);
			}
			
			// Display files?
			if ($display_files == 1)
			{
				// Tell template we have files
				$parent_template->assign_var('has_files', true);
				
				while ($file_row = $this->get_files($category['id']))
				{
					$date = format_date($file_row['date']);
					
					$parent_template->assign_var('file_id', $file_row['id']);
					$parent_template->assign_var('file_label', $file_row['name']);
					$parent_template->assign_var('file_date', $date);
									
					$parent_template->use_block('file');
				}
			}
			else
			{
				// Tell template we're not doing files this time
				$parent_template->assign_var('has_files', false);
			}
			
			// Check $file_count is integer
			$file_count = intval($file_count);
			
			// Assign variables and then use block
			$parent_template->assign_var('category', $category);
			$parent_template->assign_var('file_count', $file_count);
			
			if ($show_options_links !== false)
			{
				$parent_template->assign_var('options_links', $options_links);
			}
			
			$parent_template->assign_var('cat_label', $cat_label);
			$parent_template->use_block('category');
		}
		return $parent_template;
	}
	
	// Filters categories on whether files contained match condition in $where_file
	function filter_cats($where_file = false, $cat_level = false, $filter = true)
	{
		global $dbim, $fcm;
		
		// Get cat level if it's not set
		if ($cat_level === false)
		{
			$cat_level = $this->cat_heirachy;
		}
		
		$cat_list = array();
		
		foreach ($cat_level as $category_id => $children)
		{
			$category = $fcm->get_cat($category_id);
			
			$sql = 'SELECT COUNT(*) as count
					FROM '.DB_PREFIX.'files
					WHERE category_id = '.$category_id;
			
			if ($where_file !== false && $where_file != '')
			{
				$sql .= ' AND ('.$where_file.')';
			}
			
			$result = $dbim->query($sql);
			$row = $dbim->fetch_array($result);
			
			$count = $row['count'];
			
			if (is_array($children))
			{
				$filter_list = $this->filter_cats($where_file, $children, false);
				
				if (sizeof($filter_list) > 0)
				{
					$filter_list[] = $category_id;
				}
			}
			
			if ($count > 0)
			{
				$filter_list[] = $category_id;
			}
			
			$cat_list = array_merge($cat_list, $filter_list);
		}
		
		$cat_list = array_unique($cat_list);
		
		if ($filter !== true)
		{
			return $cat_list;
		}
		else
		{
			return $this->category_remove($cat_level, $cat_list);
		}
	}
	
	function category_remove($cat_list, $cat_keep)
	{
		if (is_array($cat_list))
		{
			foreach ($cat_list as $category_id => $children)
			{
				if (!in_array($category_id, $cat_keep))
				{
					unset($cat_list[$category_id]);
				}
				else
				{
					$cat_list[$category_id] = $this->category_remove($children, $cat_keep);
				}
			}
		}
		
		return $cat_list;
	}
	
	function list_cat_combo_box(&$template, $variable, $block_name, $cat_level = false, $level = 0)
	{
		global $fcm;
		
		// We need something to recurse through, but what?
		if ($cat_level === false)
		{
			$cat_level = $this->cat_heirachy;
		}
		
		// How deep are we into the recursion? (needed for the number of dashes)
		$level++;
		
		foreach ($cat_level as $category_id => $children)
		{
			// Build prefix
			$prefix = str_repeat('-', $level).'&nbsp;';
			
			$category_details = $fcm->get_cat($category_id);
			
			// Prepare variable to assign to template
			$category['name'] = $prefix.$category_details['name'];
			$category['id'] = $category_id;
			
			// Template bits
			$template->assign_var($variable, $category);
			$template->use_block($block_name);
			
			// Do we need to recurse any deeper?
			if (is_array($children) && count($children) > 0)
			{
				$this->list_cat_combo_box($template, $variable, $block_name, $children, $level);
			}
		}
	}
	
	function list_cat_check($cat_heirachy = false)
	{
		global $uim, $fcm;
		
		if ($cat_heirachy === false)
		{
			$cat_heirachy = $this->cat_heirachy;
		}
		
		$template = $uim->fetch_template('admin/list_cat_check');
		
		if (is_array($cat_heirachy))
		{
			foreach ($cat_heirachy as $category_id => $children)
			{
				$category = $fcm->get_cat($category_id);
				
				$label = $category['name'];
				$template->assign_var('cat_label', $label);
				
				$file_count = $fcm->count_files($category_id);
				$template->assign_var('file_count', $file_count);
				
				if (is_array($children))
				{
					$template->assign_var('has_children', true);
					$children_template = $this->list_cat_check($children);
					$children_output = $children_template->show(true);
					$template->assign_var('children_output', $children_output);
				}
				
				$template->assign_var('category', $category);
				$template->use_block('category');
			}
		}
		
		return $template;
	}
	
	function list_cat_combo_box_html($select_name, $cat_level = false, $html = '', $open_close_select = false, $level = 0)
	{
		global $fcm;
		
		// We need something to recurse through, but what?
		if ($cat_level === false)
		{
			$cat_level = $this->cat_heirachy;
		}
		
		// How deep are we into the recursion? (needed for the number of dashes)
		$level++;
		
		if ($open_close_select)
		{
			echo '<select name="'.$select_name."\">\n";
		}
		
		foreach ($cat_level as $category_id => $children)
		{
			// Build prefix
			$prefix = str_repeat('- ', $level).'&nbsp;';
			
			$category_details = $fcm->get_cat($category_id);
			
			// Prepare variable to assign to template
			$name = $prefix.$category_details['name'];
			$id = $category_id;
			
			// Add it to html
			echo '<option value="'.$id.'">'.$name."</option>\n";
			
			// Do we need to recurse any deeper?
			if (is_array($children) && count($children) > 0)
			{
				$this->list_cat_combo_box_html($select_name, $children, $html, false, $level);
			}
		}
		
		if ($open_close_select)
		{
			echo "</select>\n";
		}
	}
}



?>