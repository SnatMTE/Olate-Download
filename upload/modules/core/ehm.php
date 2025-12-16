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

// Error Handling Module
class ehm
{
	// There are 4 levels of debugging:
	//   0) No error information shown. Friendly interface.
	//   1) Standard error information shown. Friendly interface.
	//   2) Standard error information shown with full var dump. Friendly interface.
	//   3) Standard error information shown. Basic interface.
	var $debug_level = 0;

	// Constructor
	function ehm($debug_level = 0)
	{
		// Populate class variables
		$this->debug_level = $debug_level;
		
		// Knock the PHP error handling out of the damn way
		set_error_handler(array($this, 'handle_error'));
	}
	
	// Handle captured errors - whatever happens, don't panic
	function handle_error($type, $string, $file, $line, $vars)
	{
		// Retrieve global variables
		global $dbim;
		global $config;
		
		// Decide which type of error it is, and handle appropriately
		switch ($type) 
		{
			// Panic here
			case FATAL:
				// Select debug level
				switch ($this->debug_level)
				{
					default:
					case 0:
						echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN""http://www.w3.org/TR/html4/loose.dtd">'."\n";
						echo '<html>'."\n";
						echo '<head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><title>Olate Download - Error</title>'."\n";
						echo '<style type="text/css">body {margin-left: 5px; margin-top: 5px; margin-right: 5px; margin-bottom: 5px; font-family: "Trebuchet MS"; font-size: 14px;}.error_title {font-size: 18px; font-weight: bold; color: white;}.error_footer {font-size: 12px;}</style>'."\n";
						echo '</head>'."\n";
						echo '<body>'."\n";
						echo '<table width="100%"  border="0" cellspacing="0" cellpadding="5">'."\n";
						echo '<tr><td bgcolor="#5575B7"><span class="error_title">Error</span></td></tr>'."\n";
						echo '<tr><td><span>An error has ocurred. Enable debug level 1 or higher to view more details.</span></td></tr>'."\n";
						echo '</table>'."\n";
						echo '<table width="100%"  border="0" cellspacing="0" cellpadding="1">'."\n";
						echo '<tr><td height="10" bgcolor="#DDDDDD"><span class="error_footer"><strong>Type:</strong> '.$type.' <strong>Debug Level:</strong> 0 </span></td></tr>'."\n";
						echo '</table>'."\n";
						echo '</body>'."\n";
						echo '</html>'."\n";
						// Stop application
						exit;
					case 1:
						echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN""http://www.w3.org/TR/html4/loose.dtd">'."\n";
						echo '<html>'."\n";
						echo '<head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><title>Olate Download - Error</title>'."\n";
						echo '<style type="text/css">body {margin-left: 5px; margin-top: 5px; margin-right: 5px; margin-bottom: 5px; font-family: "Trebuchet MS"; font-size: 14px;}.error_title {font-size: 18px; font-weight: bold; color: white;}.error_footer {font-size: 12px;}</style>'."\n";
						echo '</head>'."\n";
						echo '<body>'."\n";
						echo '<table width="100%"  border="0" cellspacing="0" cellpadding="5">'."\n";
						echo '<tr><td bgcolor="#5575B7"><span class="error_title">Error</span></td></tr>'."\n";
						echo '<tr><td><span>'.$string.'</span></td></tr>'."\n";
						echo '</table>'."\n";
						echo '<table width="100%"  border="0" cellspacing="0" cellpadding="1">'."\n";
						echo '<tr><td height="10" bgcolor="#DDDDDD"><span class="error_footer"><strong>Type:</strong> '.$type.' <strong>File:</strong> '.$file.' <strong>Line:</strong> '.$line.' <strong>Debug Level:</strong> 1 </span></td></tr>'."\n";
						echo '</table>'."\n";
						echo '</body>'."\n";
						echo '</html>'."\n";
						// Stop application
						exit;
					case 2:
						echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN""http://www.w3.org/TR/html4/loose.dtd">'."\n";
						echo '<html>'."\n";
						echo '<head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><title>Olate Download - Error</title>'."\n";
						echo '<style type="text/css">body {margin-left: 5px; margin-top: 5px; margin-right: 5px; margin-bottom: 5px; font-family: "Trebuchet MS"; font-size: 14px;}.error_title {font-size: 18px; font-weight: bold; color: white;}.error_footer {font-size: 12px;}</style>'."\n";
						echo '</head>'."\n";
						echo '<body>'."\n";
						echo '<table width="100%"  border="0" cellspacing="0" cellpadding="5">'."\n";
						echo '<tr><td bgcolor="#5575B7"><span class="error_title">Error</span></td></tr>'."\n";
						echo '<tr><td><span>'.$string.'<br /><br /><strong>Variable Dump:</strong></span><pre>'."\n";
						print_r($vars);
						echo '</td></tr>'."\n";
						echo '</table>'."\n";
						echo '<table width="100%"  border="0" cellspacing="0" cellpadding="1">'."\n";
						echo '<tr><td height="10" bgcolor="#DDDDDD"><span class="error_footer"><strong>Type:</strong> '.$type.' <strong>File:</strong> '.$file.' <strong>Line:</strong> '.$line.' <strong>Debug Level:</strong> 2 </span></td></tr>'."\n";
						echo '</table>'."\n";
						echo '</body>'."\n";
						echo '</html>'."\n";
						// Stop application
						exit;			
					case 3:							
						echo '<pre>An error occurred:' . "\n\n";
						echo '<strong>Debug Level:</strong> 3' . "\n\n";
						echo '<strong>Type:</strong> ' . $type."\n";
						echo '<strong>String:</strong> ' . $string."\n";
						echo '<strong>File:</strong> ' . $file."\n";
						echo '<strong>Line:</strong> ' . $line."\n";
						echo '----------------------'."\n";
						echo '<strong>Vars:</strong>'."\n";
						print_r($vars);
						// Stop application
						exit;
				}
			case ERROR:
				echo '<pre><b>ERROR</b> ['.$type.'] '.$string.'<br />'."\n";
				break;
			case WARNING:
				echo '<pre><b>WARNING</b> ['.$type.'] '.$string.'<br />'."\n";
				break;
		}
	}
}
?>