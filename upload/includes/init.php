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

// Check for installation
if (@filesize('./includes/config.php') == 0)
{
	// Nope, go to setup
	header('Location: ./setup/index.php'); 
	exit;
}

// Be off with you evil fiend
@ini_set('magic_quotes_gpc', '0');

// Enable full error reporting for testing (show all errors/warnings/notices)
@ini_set('display_errors', '1');
@ini_set('display_startup_errors', '1');
@ini_set('log_errors', '0');
error_reporting(E_ALL);

$debug = 0;

// Simple exception handler to display uncaught exceptions during testing
set_exception_handler(function ($e) {
	echo "<pre>Uncaught Exception: ".get_class($e)." - ".htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')."\n";
	echo htmlspecialchars($e->getTraceAsString(), ENT_QUOTES, 'UTF-8');
	echo "</pre>";
});

// Show fatal errors on shutdown (useful for development)
register_shutdown_function(function () {
	$err = error_get_last();
	if ($err !== null) {
		echo "<pre>Fatal error: ".htmlspecialchars($err['message'], ENT_QUOTES, 'UTF-8')." in ".$err['file']." on line ".$err['line']."</pre>";
	}
});

if ($debug == 1)
{
	// Start execution time counter (continued in uim_template->assign_globals())
	$time = microtime(); 
	$time = explode(' ',$time); // Kabooooom
	$time = $time[1] + $time[0]; 
	$start_time = $time;
}

// Include required files
// General
require('./includes/config.php');
require('./includes/global.php');

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
	trigger_error('[INIT] You must delete the /setup directory.', FATAL);
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