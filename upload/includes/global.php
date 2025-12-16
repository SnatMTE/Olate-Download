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

// OD native functions not yet enslaved to a class

// Fatal handler (avoids using trigger_error with E_USER_ERROR which is deprecated in PHP 8.4+)
function od_fatal($msg)
{
	header('HTTP/1.1 500 Internal Server Error', true, 500);
	echo $msg;
	exit;
}

// Format date (as the name suggests)
function format_date($date)
{
	global $site_config;
	
	$date = date($site_config['date_format'], $date);
	
	return $date;
} 

// Check the variables are the expected type
// Yoink...Idea taken from vBulletin
function validate_types(&$array, $names)
{
	// Get rid of magic quotes (polyfill for PHP 7/8 where magic quotes functions were removed)
	if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
	{
		$array = array_map('stripslashes', $array);
	}
	
	// Cycle through array
	foreach ($names as $name => $type)
	{		
		// Set types
		if (isset($array["$name"]))
		{
			switch ($type)
			{
				// Set as integer
				case 'INT':
					$array["$name"] = intval($array["$name"]);
					break;
				// Set as float
				case 'FLOAT':
					$array["$name"] = floatval($array["$name"]);
					break;
				// string - trim data, strip slashes, banish HTML
				case 'STR':
					$array["$name"] = mysql_real_escape_string(htmlspecialchars(strip_tags(trim($array["$name"]))));
					break;
				// string with html
				case 'STR_HTML':
					$array["$name"] = mysql_real_escape_string(trim($array["$name"]));
					break;
				// Do nothing, i.e. arrays, etc.
				default:
			}
		}
	}
	return $array;
}

// Function to determine whether to use FCKeditor or not
function use_fckeditor()
{
	global $site_config;
	
	if (file_exists('FCKeditor/fckeditor.php'))
	{
		if ($site_config['use_fckeditor'])
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}
?>