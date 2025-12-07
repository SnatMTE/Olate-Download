<?php
// Simple safe replacer: backups files and replaces $dbim->query( with $dbim->pquery(
// Usage: php tools\replace_db_calls.php

$root = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'upload';
$iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$changed = [];
$files = 0;
foreach ($iter as $file) {
    if (!$file->isFile()) continue;
    $path = $file->getPathname();
    if (substr($path, -4) !== '.php') continue;
    $contents = file_get_contents($path);
    if (strpos($contents, '$dbim->query(') !== false) {
        $files++;
        $bak = $path . '.bak';
        if (!file_exists($bak)) {
            file_put_contents($bak, $contents);
        }
        $new = str_replace('$dbim->query(', '$dbim->pquery(', $contents);
        if ($new !== $contents) {
            file_put_contents($path, $new);
            $changed[] = $path;
            echo "Updated: $path\n";
        }
    }
}

echo "\nFiles scanned: $files\n";
echo "Total updated: " . count($changed) . "\n";

if (count($changed) > 0) {
    echo "Updated files list:\n";
    foreach ($changed as $c) echo " - $c\n";
}

echo "Done. Review backups (*.bak) before committing.\n";

?>