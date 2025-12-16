<?php
/**
 * Diagnostic wrapper to include includes/init.php and capture fatal errors via shutdown handler
 * Temporary — remove after use
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

register_shutdown_function(function() {
    $err = error_get_last();
    if ($err) {
        header('Content-Type: text/plain; charset=utf-8');
        echo "Shutdown Error Detected:\n";
        echo "type: " . $err['type'] . "\n";
        echo "message: " . $err['message'] . "\n";
        echo "file: " . $err['file'] . "\n";
        echo "line: " . $err['line'] . "\n";
    } else {
        echo "No shutdown error detected. Includes/init.php likely loaded successfully.\n";
    }
});

// Attempt to include init.php
try {
    require('./includes/init.php');
    echo "includes/init.php included successfully.\n";
} catch (Throwable $t) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "Throwable caught: " . $t->getMessage() . "\n";
}

?>