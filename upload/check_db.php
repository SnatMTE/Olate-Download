<?php
/**
 * Diagnostic script to check PDO drivers and DB connectivity
 * Temporary — remove after use
 */
require('./includes/config.php');

header('Content-Type: text/plain; charset=utf-8');

echo "PHP version: " . phpversion() . "\n";

if (class_exists('PDO')) {
    echo "PDO: available\n";
    $drivers = PDO::getAvailableDrivers();
    echo "PDO drivers: " . implode(', ', $drivers) . "\n";
} else {
    echo "PDO: NOT available\n";
}

// Try connect via PDO MySQL if driver exists
if (in_array('mysql', PDO::getAvailableDrivers())) {
    try {
        $dsn = 'mysql:host=' . $config['database']['server'] . ';dbname=' . $config['database']['name'] . ';charset=utf8mb4';
        $pdo = new PDO($dsn, $config['database']['username'], $config['database']['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        echo "PDO MySQL: connected successfully\n";
        echo "PDO server version: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
    } catch (PDOException $e) {
        echo "PDO MySQL connection failed: " . $e->getMessage() . "\n";
    }
} else {
    echo "PDO MySQL driver not present\n";
}

// Try using compatibility mysql_connect wrapper if available
if (function_exists('mysql_connect')) {
    $res = @mysql_connect($config['database']['server'], $config['database']['username'], $config['database']['password']);
    if ($res === false) {
        echo "mysql_connect() via wrapper failed: " . mysql_error() . "\n";
    } else {
        echo "mysql_connect() via wrapper succeeded\n";
    }
} else {
    echo "mysql_connect() wrapper not defined\n";
}

?>