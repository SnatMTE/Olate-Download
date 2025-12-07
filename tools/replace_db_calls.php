<?php
// Replacer for $dbim->query(...) -> $dbim->pquery(...)
// Usage:
//  - Dry run (default): php tools\replace_db_calls.php
//  - Show would-change files: php tools\replace_db_calls.php --list
//  - Apply changes (creates .bak backups): php tools\replace_db_calls.php --apply

// Safe by default: dry-run unless --apply provided
$apply = in_array('--apply', $argv, true);
$listOnly = in_array('--list', $argv, true);

$root = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'upload');
if ($root === false) {
    fwrite(STDERR, "Could not locate upload/ directory\n");
    exit(2);
}

$iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$changed = [];
$scanned = 0;

// Regex matches $dbim->query   (   with optional whitespace between name and '('
$pattern = '/\$dbim->query\s*\(/';
$replacement = '$dbim->pquery(';

foreach ($iter as $file) {
    if (!$file->isFile()) continue;
    $path = $file->getPathname();
    if (substr($path, -4) !== '.php') continue;
    $contents = file_get_contents($path);
    if (preg_match($pattern, $contents)) {
        $scanned++;
        $new = preg_replace($pattern, $replacement, $contents);
        if ($new !== $contents) {
            $changed[] = $path;
            if ($listOnly) continue;
            if ($apply) {
                $bak = $path . '.bak';
                if (!file_exists($bak)) {
                    file_put_contents($bak, $contents);
                }
                file_put_contents($path, $new);
                echo "Updated: $path\n";
            } else {
                echo "Would update: $path\n";
            }
        }
    }
}

echo "\nMatches found: $scanned\n";
echo "Files that would be/are changed: " . count($changed) . "\n";
if (count($changed) > 0) {
    echo "List:\n";
    foreach ($changed as $c) echo " - $c\n";
}

if (!$apply) {
    echo "\nDry-run mode (no files modified). To apply changes run with --apply.\n";
} else {
    echo "\nApplied changes; backups saved as *.bak. Review before committing.\n";
}

?>