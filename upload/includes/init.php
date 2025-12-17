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

// Check for installation
if (@filesize('./includes/config.php') == 0)
{
	// Nope, go to setup
	header('Location: ./setup/index.php'); 
	exit;
}

// Be off with you evil fiend
ini_set('magic_quotes_gpc', '0');

$debug = 1; // Enable debug to show runtime errors while investigating admin white screen

if ($debug == 1)
{
	// Show all errors during debugging
	@ini_set('display_errors', '1');
	error_reporting(E_ALL);

	// Ask UIM to dump compiled templates for debugging
	$GLOBALS['OD_DEBUG_COMPILE'] = true;

	// Start execution time counter (continued in uim_template->assign_globals())
	$time = microtime(); 
	$time = explode(' ',$time); // Kabooooom
	$time = $time[1] + $time[0]; 
	$start_time = $time;

	// Add a shutdown handler to capture fatal errors to a log for offline inspection
	register_shutdown_function(function() {
		$err = error_get_last();
		if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR])) {
			$log = "[".date('c')."] FATAL: {$err['message']} in {$err['file']} on line {$err['line']}\n";
			@file_put_contents(__DIR__ . '/../od_debug.log', $log, FILE_APPEND);
		}
	});

	// Also set error and exception handlers to log other issues
	set_error_handler(function($errno, $errstr, $errfile, $errline) {
		$log = "[".date('c')."] ERROR ({$errno}): {$errstr} in {$errfile} on line {$errline}\n";
		@file_put_contents(__DIR__ . '/../od_debug.log', $log, FILE_APPEND);
		// Let default handler run as well
		return false;
	});

	set_exception_handler(function($ex) {
		$log = "[".date('c')."] EXCEPTION: {$ex->getMessage()} in {$ex->getFile()} on line {$ex->getLine()}\n";
		@file_put_contents(__DIR__ . '/../od_debug.log', $log, FILE_APPEND);
	});
}

// Include required files
// General
require('./includes/config.php');
require('./includes/global.php');
require('./includes/helper.php');

// Core modules
require('./modules/core/dbim.php');
require('./modules/core/ehm.php');
require('./modules/core/lm.php');
require('./modules/core/uim.php');
require('./modules/core/fcm.php');
require('./modules/core/fldm.php');
require('./modules/core/uam.php');
require('./modules/core/sm.php');

// Define any constants
// Error types
define('FATAL', E_USER_ERROR);
define('ERROR', E_USER_WARNING);
define('WARNING', E_USER_NOTICE);

// Initialise modules (order is important)

// EHM
$ehm = new ehm(1); // Debug level 1 recommended for live environments

// Make sure setup directory has been deleted

if (file_exists('./setup'))
{
	// Abort startup to avoid running the application while /setup exists.
	// trigger_error with E_USER_ERROR is deprecated in PHP 8.4+; exit with a clear message instead.
	header('HTTP/1.1 500 Internal Server Error', true, 500);
	echo '[INIT] You must delete the /setup directory.';
	exit;
}

// DBIM
$dbim = new dbim();
$dbim->connect($config['database']['username'], $config['database']['password'], $config['database']['server'], $config['database']['name'], $config['database']['persistant']);

// Get the site config
$config_result = $dbim->query('SELECT * 
								FROM '.DB_PREFIX.'config 
								LIMIT 1');
$site_config = $dbim->fetch_array($config_result);
$site_config['debug'] = $debug; // It will get overwritten otherwise

// Define page title prefix
define('TITLE_PREFIX', $site_config['site_name'].' - ');

// LM
$lm = new lm();

// UIM
$uim = new uim_main();

// FCM
$fcm = new fcm();

// FLDM
$fldm = new fldm();

// UAM
$uam = new uam();

// SM
$sm = new sm();
$sm->page_init();

?>