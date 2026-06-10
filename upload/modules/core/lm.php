<?php
/**********************************
* Olate Download 3.5.0
* https://github.com/SnatMTE/Olate-Download/
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 197 $
* @package od
*
* Original Author: Olate Download
* Updated by: Snat
* Last-Edited: 2025-12-16
*/

// Language Module
class lm
{
	// Declare variables
	var $languagename;
	var $language;
	var $language_row;
	
	// Constructor - get current language from database
	function lm()
	{
		// Need to use DBIM & $site_config
		global $dbim, $site_config;
		
		$this->load_language();
	}
	
	// PHP 5+/8+ constructor shim — call legacy constructor for backward compatibility
	function __construct()
	{
		$this->lm();
	}
	
	// Return current language
	function current_language()
	{
		return $this->languagename;
	}
	
	// Return language data
	function get_config($name)
	{
		// Be defensive: ensure language config exists
		if (!isset($this->language) || !isset($this->language['config']) || !isset($this->language['config'][$name])) {
			return '';
		}
		return $this->language['config'][$name];
	}
	
	// Return text for $language['text']['section']['$name']
	function language($section, $name)
	{
		// Be defensive: if language data is missing or malformed return empty string
		if (!isset($this->language) || !isset($this->language['text']) || !isset($this->language['text'][$section]) || !isset($this->language['text'][$section][$name])) {
			return '';
		}
		return $this->language['text'][$section][$name];
	}
	
	// Fetches array of available languages
	function list_languages($version_check = false)
	{
		global $site_config, $dbim;
		
		// Split up OD version
		$site_version = explode('.', $site_config['version']);
		
		// Are we only returning up-to-date languages for this version of OD?
		if ($version_check === true)
		{
			$language_sql = 'SELECT * 
								FROM '.DB_PREFIX.'languages
								WHERE version_major = ? AND version_minor = ?
								ORDER BY site_default DESC, name ASC';
			$language_result = $dbim->pquery($language_sql, array($site_version[0], $site_version[1]));
		}
		else 
		{
			$language_sql = 'SELECT * 
								FROM '.DB_PREFIX.'languages
								ORDER BY site_default DESC, name ASC';
			$language_result = $dbim->pquery($language_sql, array());
		}
		
		// Initialise variables
		$languages = array();
		$deleted_default = false;
		
		while ($language = $dbim->fetch_array_p($language_result))
		{
			// No point returning a language which doesn't actually exist
			if (file_exists('languages/'.$language['filename']))
			{
				// Add language to array of language
				$languages[] = $language;
			}
			else
			{
				$dbim->pquery('DELETE FROM '.DB_PREFIX.'languages
								WHERE id = ?',
								array($language['id']));
				
				if ((bool)$language['site_default'])
				{
					$deleted_default = true;
				}
			}
		}
		
		if ($deleted_default)
		{
			// Get first element of $languages array
			reset($languages);
			$current = current($languages);
			$key = key($languages);
			
			// Update database to set this language to be default
			$dbim->pquery('UPDATE '.DB_PREFIX.'languages
							SET site_default = 1
							WHERE id = ?',
							array($current['id']));
			
			$languages[$key]['site_default'] = 1;
		}
		
		return $languages;
	}
	
	function load_language()
	{
		global $dbim, $site_config;
		
		$site_version = explode('.', $site_config['version']);
		
		$use_default = true;
		
		// Get current language
		if (!empty($_COOKIE['OD3_language']))
		{
			validate_types($_COOKIE, array('OD3_language', 'INT'));
			
			// Check cookie language is valid
			$result = $dbim->pquery('SELECT *
									FROM '.DB_PREFIX.'languages
									WHERE (id = ?) 
											AND (version_major = ?) 
											AND (version_minor = ?)',
									array($_COOKIE['OD3_language'], $site_version[0], $site_version[1]));
			
			if ($dbim->num_rows_p($result) > 0)
			{
				// It is, so fetch row
				$row = $dbim->fetch_array_p($result);
				
				// Language file exists?
				if (file_exists('languages/'.$row['filename']))
				{
					// Load it in
					include('languages/'.$row['filename']);
					$this->languagename = $language['config']['full_name'];
					$this->language = $language;
					$this->language_row = $language_row;
					
					$use_default = false;
				}
				else 
				{
					// No file, so fall back on default
					$use_default = true;
				}
			}
			else 
			{
				// No such language in DB, so fall back on default
				$use_default = true;
			}
		}
		
		// Default language
		if ($use_default !== false)
		{
			$language_res = $dbim->pquery('SELECT *
											FROM '.DB_PREFIX.'languages
											WHERE 
												(site_default = 1) 
												AND (version_major = ?) 
												AND (version_minor = ?)
											LIMIT 1',
											array($site_version[0], $site_version[1]));
			
			if ($dbim->num_rows_p($language_res) > 0)
			{
				// Language exists
				$language_row = $dbim->fetch_array_p($language_res);
			}
			else 
			{
				// No default language, fall back on any up-to-date language
				$language_res = $dbim->pquery('SELECT *
												FROM '.DB_PREFIX.'languages
												WHERE
													(version_major = ?) 
													AND (version_minor = ?)
												LIMIT 1',
												array($site_version[0], $site_version[1]));
				
				if ($dbim->num_rows_p($language_res) > 0)
				{
					// Success, we have a language
					$language_row = $dbim->fetch_array_p($language_res);
				}
				else 
				{
					// No languages at all for this version!!
					od_fatal('[LM] No up to date languages are available to be used');
				}
			}
			
			if (file_exists('languages/'.$language_row['filename']))
			{
				include('languages/'.$language_row['filename']);
				$this->languagename = $language['config']['full_name'];
				$this->language = $language;
				$this->language_row = $language_row;
			}
			else 
			{
				od_fatal('[LM] There are no languages available to be used');
			}
		}
		
		unset($language);
	}
}
?>