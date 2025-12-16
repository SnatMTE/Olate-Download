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

// "Why make your own templating system" you may ask? Why not use Smarty or any of the 
// others out there? Well, my friend, because they suc...um, aren't that good for our
// purposes.

// User Interface Module: Main Class
class uim_main
{	
	// Declare some vars
	var $theme, $meta_data;
	
	// Constructor - get the theme
	function uim_main($theme = false)
	{		
		global $site_config;
		
		if ($theme != false) 
		{			
			$this->theme = $theme;			
		}
		else 
		{			
			$this->theme = $site_config['template'];		
		}
		// Initialize meta-data to a safe default to avoid undefined property notices
		$this->meta_data = array();		
		
		// Start output buffering for the generate() call
		ob_start();
	}

	// PHP 5+/8+ constructor shim — call legacy constructor for backward compatibility
	function __construct($theme = false)
	{
		$this->uim_main($theme);
	} 
	
	// Get the template file requested
	function fetch_template($template)
	{		
		// Spawn a new object
		$dir = 'templates/'.$this->theme;
		$file = $template.'.tpl.php';
		$template_object = new uim_template($dir, $file);
		
		return $template_object;		
	}	
			
	// Do the actual generation of the content
	function generate($title, $refresh = false)
	{
		// Need data from this
		global $site_config;
		
		$core = new uim_template('templates/'.$this->theme, '/global/core.tpl.php');
		
		// Assign page variables
		$core->assign_var('page_content', ob_get_contents());
		$core->assign_var('page_title', $title);
		$core->assign_var('site_config', $site_config);
		
		// Assign the refresh url if it's given
		if ($refresh)
		{
			$core->assign_var('page_refresh', $refresh);
		}
		
		// Any meta data?
		if (is_array($this->meta_data))
		{
			// Check each types
			foreach ($this->meta_data as $meta_type => $meta_tags)
			{
				if (is_array($meta_tags))
				{
					foreach ($meta_tags as $tag['name'] => $tag['value'])
					{
						// Type of meta tag?
						$tag['type'] = $meta_type;
						
						// Template
						$core->assign_var('tag', $tag);
						$core->use_block('meta_tags');
					}
				}
			}
		}
		
		ob_end_clean();
		
		// And show
		$core->show();
	}
	
	// Used for adding meta tags at top of page
	function add_meta_data($name, $value, $type = 'standard')
	{
		if ($type == 'http-equiv' || $type == 'standard')
		{
			$this->meta_data[$type][$name] = $value;
		}
	}
}

// User Interface Module: Template Class
class uim_template
{	
	// Declare vars
	var $file, $dir, $vars, $lang, $template, $blocks;
	
	// Constructor
	function uim_template($dir, $file)
	{		
		$this->dir = $dir;
		$this->file = $file;
		
		// Initialize template storage to safe defaults
		$this->vars = array();
		$this->blocks = array();
		$this->template = '';
		
		// Get the contents
		$this->get_file();
		
		// Assign global vars
		$this->assign_globals();
	}

	// PHP 5+/8+ constructor shim — call legacy constructor for backward compatibility
	function __construct($dir, $file)
	{
		$this->uim_template($dir, $file);
	}
	
	// Assign variable for parsing later
	function assign_var($name, $value)
	{		
		$this->vars["$name"] = $value;		
	}
	
	// Assign variables for parsing later
	function assign_vars($vars) 
	{
		// Accept only arrays
		if (!is_array($vars)) {
			return;
		}
		// Use foreach to iterate safely
		foreach ($vars as $name => $value) {
			$this->assign_var($name, $value);
		}
	}
	function parse(&$template) 
	{		
		// Guard against null or non-string templates (avoid deprecated warnings in PHP 8.4+)
		if (!is_string($template) && !is_numeric($template)) {
			return;
		}
		// Ensure it's a string for string operations
		$template = (string)$template;
		
		// Declare things to parse, and what to check for
		// If present, parse, if not, don't
		$types = array('vars' => '{$',
						'inserts' => '{insert:',
						'lang' => '{lang:',
						'conditionals' => '{if:',
						'blocks' => '{block:');
		
		foreach ($types as $type => $search)
		{ 			
			$parse_func = 'parse_'.$type;
			
			if (strpos($template, $search) !== false) 
			{ 			
				$this->$parse_func($template); 			
			} 			
		} 		
	} 

	// Variable pArsing (hehe) with the joys of reg exps
	function parse_vars(&$template) 
	{		
		// Find all normal vars
		preg_match_all('/{\\$([a-zA-Z0-9\-_]+)}/', $template, $tpl_vars);
		
		// Now go through each one
		foreach ($tpl_vars['1'] as $var)
		{
			// Make the php out of it
			$template = str_replace('{$'.$var.'}', '<?php echo $this->vars[\''.$var.'\']; ?>', $template);
		}
		
		// Now find all arrays
		preg_match_all('/{\\$([a-zA-Z0-9\-_\-\[\-\]]+)}/', $template, $tpl_vars);

		// And go through each one
		foreach ($tpl_vars['1'] as $var)
		{
			// Find the main var, and the index
			// by splitting it up using the :
			$array = explode('[', $var);
			$main_var = $array['0'];
			$var_index = $array['1'];
			
			// var_index will still have ] on the end
			// It's getting hot in here, so take off all your ]
			$var_index = substr($var_index, '0', strlen($var_index) - 1);

		// Now make the php out of it with a safe isset check to avoid undefined array key notices
		$template = str_replace('{$'.$main_var.'['.$var_index.']}',
						'<?php echo (isset($this->vars[\''.$main_var.'\'][\''.$var_index.'\']) ? $this->vars[\''.$main_var.'\'][\''.$var_index.'\'] : ""); ?>',
									$template);
		}
	}
	
	// Insert parsing
	function parse_inserts(&$template)
	{		
		// Find all matches
		preg_match_all('/{insert:([a-zA-Z0-9\-_\-\/]+)}/', $template, $tpl_inserts);
		
		// Go through each one,
		// and show it
		foreach ($tpl_inserts['1'] as $insert)
		{		
			// Make the code
			$template = str_replace('{insert:'.$insert.'}',
									'<?php $this->insert_template(\''.$insert.'\'); ?>',
									$template);				
		}		
	}
	
	// Language parsing
	function parse_lang(&$template)
	{		
		global $lm;
		
		// Now find all arrays
		preg_match_all('/{lang:([a-zA-Z0-9\-_\-:]+)}/', $template, $lang_vars);
		
		// And go through each one
		foreach ($lang_vars['1'] as $lang)
		{			
			// Find the lang section
			// and name
			$array = explode(':', $lang); // Boom
			$lang_section = $array['0'];
			$lang_name = $array['1'];
			
			// Now make the php out of it
			$template = str_replace('{lang:'.$lang_section.':'.$lang_name.'}',
									'<?php echo $lm->language(\''.$lang_section.'\', \''.$lang_name.'\'); ?>',
									$template);
		}		
	}
	
	// Conditionals parsing
	function parse_conditionals(&$template)
	{		
		// First on the menu, sir, the dish of the day: {if:}'s
		preg_match_all('/{if:(.+)}/', $template, $conditionals_if);
		
		foreach ($conditionals_if['1'] as $condition) 
		{			
			// Quote unquoted array indices in conditions (e.g., $a[key] -> $a['key']) to avoid undefined constant notices
			$cond_safe = preg_replace_callback('/\$([a-zA-Z0-9_]+)\[([a-zA-Z0-9_]+)\]/', function($m){ return '$'.$m[1]."['".$m[2]."']"; }, $condition);
			// Replace array variable accesses in conditions with safe isset() accessors to avoid undefined array key notices
			$cond_safe = preg_replace_callback('/\$([a-zA-Z0-9_]+)\[\'([a-zA-Z0-9_]+)\'\]/', function($m){
				return '(isset($this->vars[\''.$m[1].'\'][\''.$m[2].'\']) ? $this->vars[\''.$m[1].'\'][\''.$m[2].'\'] : false)';
			}, $cond_safe);
			
			// Make the php
			$template = str_replace('{if:'.$condition.'}',
							'<?php if ('.$cond_safe.') { ?>', $template); 			
			// Make the php
			$template = str_replace('{elseif:'.$condition.'}',
									'<?php } elseif ('.$condition.') { ?>', $template);
		}
		
		// And finally you're waiter today will be else & endif
		$template = str_replace('{else}', '<?php } else { ?>', $template);
		$template = str_replace('{endif}', '<?php } ?>', $template);		
	}
	
	// Block parsing
	function parse_blocks(&$template) 
	{		
		// Just go through and replace {block}'s
		// with the content stored in the $block array
		preg_match_all('/{block:([a-zA-Z0-9\-_\-:]+)}/', $this->template, $blocks);
		
		foreach ($blocks['1'] as $block)
		{	
			if (isset($this->blocks) && array_key_exists($block, $this->blocks))
			{
				$template = preg_replace('/{block:'.$block.'}.+{\/block:'.$block.'}/s',
										$this->blocks["$block"], $template);
			}
			else
			{
				$template = preg_replace('/{block:'.$block.'}.+{\/block:'.$block.'}/s',
										'', $template);
			}
		}		
	}
	
	// Decide which block to use, find it and pArse (never gets old) it
	function use_block($name) 
	{		
		global $lm;
		
		preg_match_all('/{block:'.$name.'}(.+){\/block:'.$name.'}/s', $this->template, $blocks);

		foreach ($blocks['1'] as $block)
		{
			// Parse the block
			$this->parse($block);
			
			// Ensure vars is an array before extracting (avoid extract() fatal when null)
			if (!is_array($this->vars)) {
				$this->vars = array();
			}
			
			// Extract the vars
			extract($this->vars);
			
			// Start output buffering, because we're going to
			// eval it, and otherwise it would be outputted
			ob_start();
			eval ('?>'.$block);
			
			// Store output into the array, ready for the
			// parse_blocks function
			$this->blocks["$name"] .= ob_get_contents();

			// Clean the buffer
			ob_end_clean();
		}		
	}
	
	// Clear block to make available for next run
	function clear_block($name)
	{		
		unset($this->blocks[$name]);		
	}
	
	// Inserting specified template
	function insert_template($template) 
	{
		// Spawn an object
		$insert = new uim_template($this->dir, $template.'.tpl.php');
		
		// Give it our vars
		$insert->give_vars($this->vars);
		
		// And for my last trick..
		$insert->show();		
	}
	
	// Variable assignment
	function give_vars($vars) 
	{		
		// Ignore non-arrays
		if (!is_array($vars)) {
			return;
		}
		// Go through each one, and add it to
		// the vars array
		foreach ($vars as $name => $value) {
			$this->vars["$name"] = $value;
		}
	}
	function get_file()
	{		
		// Normalize path and read it in safely
		$path = rtrim($this->dir, '/') . '/' . ltrim($this->file, '/');
		if (!is_readable($path)) {
			// Template missing or unreadable — use empty template to avoid warnings
			$this->template = '';
			return;
		}
		$contents = file_get_contents($path);
		$this->template = ($contents === false) ? '' : $contents;
	}
	// Create global vars
	function assign_globals()
	{
		global $uam, $dbim, $start_time, $site_config;
		
		// Variables
		$global_vars = $site_config;
		
		if ($site_config['debug'] == 1)
		{
			// Calculate execution time. This is the best place to put this IMO
			$time = microtime(); 
			$time = explode(' ',$time); 
			$time = $time[1] + $time[0]; 
			$end_time = $time; 
			$total_time = round(($end_time - $start_time), 4);	
			$global_vars['exec_time'] = $total_time.' secs';	
			
			// Query count
			$global_vars['queries'] = $dbim->query_count;
		}
		
		// Current script
		$global_vars['request_uri'] = $_SERVER['REQUEST_URI'];
		$global_vars['php_self'] = $_SERVER['PHP_SELF'];
		
		// Count approved files
		$count_result = $dbim->query('SELECT COUNT(*) AS files
        								FROM '.DB_PREFIX.'files
        								WHERE status = 1');
		$count = $dbim->fetch_array($count_result);
		$global_vars['total_files'] = $count['files'];
		
		// Count downloads
		$count_result = $dbim->query('SELECT COUNT(*) AS downloads
		    							FROM '.DB_PREFIX.'stats');
		$count = $dbim->fetch_array($count_result);
		$global_vars['total_downloads'] = $count['downloads'];
		
		// Assign them
		$this->assign_var('global_vars', $global_vars);
		
		// Assign get/post vars
		$this->assign_vars(array('get_vars' => $_GET,
								 'post_vars' => $_POST));
								 
		// Assign user's permissions (always assign an array to avoid undefined variable in templates)
		if ($uam->user_authed() && isset($uam->permissions))
		{
			$this->assign_var('user_permissions', $uam->permissions);
		}
		else
		{
			$this->assign_var('user_permissions', array());
		}
		
		// And site config
		$this->assign_var('site_config', $site_config);
	}
	
	// Actually show the content
	function show($return = false)
	{
		global $lm; 
		
		// Parse it
		$this->parse($this->template);
		
		// Extract vars into local namespace
		// this is for conditionals, where people will type
		// things like {if: $var == 'value'} if it wasn't this
		// way, they'd have to type {if: $this->vars['var'] == 'value'}
		// not really very fun
		// Ensure vars is an array before extracting to avoid fatal extract() error
		if (!is_array($this->vars)) {
			$this->vars = array();
		}
		extract($this->vars);
		
		// Are we just dumping the PHP code?
		if ($return === 'php')
		{
			echo '<pre>'.htmlspecialchars($this->template).'</pre>';
			return;
		}
		
		// Catch output
		ob_start();
		
		// If debug compilation is requested, write compiled template to file for offline linting
		if (!empty($GLOBALS['OD_DEBUG_COMPILE'])) {
			$compiled_path = dirname(__DIR__, 2) . '/compiled_template.php';
			@file_put_contents($compiled_path, "<?php\n" . $this->template);
		}
		// Eval it
		eval('?>'.$this->template);
		
		// Return or flush?
		if ($return === true)
		{
			$buffer = ob_get_contents();
			ob_end_clean();
			return $buffer;
		}
		else
		{
			ob_end_flush();
		}
	}	
}
?>