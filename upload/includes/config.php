(<?php
// Basic configuration for development (PDO + SQLite)

// Table prefix used throughout the app
if (!defined('DB_PREFIX')) define('DB_PREFIX', 'downloads_');

$config = array();
$config['database'] = array(
	'username' => '',
	'password' => '',
	'server'   => '', // Not used for SQLite
	'name'     => '', // Not used for SQLite
	'persistant'=> false,
);

// Additional settings can be loaded/overridden by the installer or environment
?>
