<?php
/**
 * Diagnostic wrapper to execute admin.php and capture errors via shutdown handler
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
        return;
    }
    echo "No shutdown error detected. admin.php likely ran without fatal errors.\n";
});

// Prepopulate request to safe values
if (!isset($_REQUEST['cmd'])) {
    $_REQUEST['cmd'] = 'main';
}
// Ask the template system to dump the compiled PHP to a file for debugging
$GLOBALS['OD_DEBUG_COMPILE'] = true;

try {
    ob_start();
    include('./admin.php');
    $output = ob_get_clean();
    header('Content-Type: text/plain; charset=utf-8');
    echo "admin.php output:\n";
    echo "-----------------\n";
    // Limit output to first 20KB to avoid huge responses
    echo substr($output, 0, 20480);
} catch (Throwable $t) {
    header('Content-Type: text/plain; charset=utf-8');
    $buffer = '';
    // Grab any partial output
    if (ob_get_length() !== false) {
        $buffer = ob_get_clean();
    }
    echo "Throwable caught: " . $t->getMessage() . "\n";
    echo "File: " . $t->getFile() . "\n";
    echo "Line: " . $t->getLine() . "\n";
    echo "Trace:\n" . $t->getTraceAsString() . "\n";
    if (!empty($buffer)) {
        echo "--- Partial output (truncated) ---\n";
        echo substr($buffer, 0, 20480);
    }
}

?>