<?php
// Compile all templates to detect parse-time PHP syntax errors
$base = __DIR__ . '/..';
require $base . '/upload/modules/core/uim.php';
$tpl_dir = $base . '/upload/templates/olate';
$out_dir = __DIR__ . '/compiled_templates';
if (!is_dir($out_dir)) mkdir($out_dir);
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($tpl_dir));
$errors = [];
foreach ($files as $file) {
    if (!$file->isFile()) continue;
    $rel = substr($file->getPathname(), strlen($tpl_dir)+1);
    // only .tpl.php files
    if (!preg_match('/\.tpl\.php$/', $file->getFilename())) continue;
    $r = new ReflectionClass('uim_template');
    $o = $r->newInstanceWithoutConstructor();
    $o->dir = $tpl_dir;
    $o->file = $rel;
    // Read file
    $o->get_file();
    $template = $o->template;
    // Parse template (uses parse methods)
    $o->parse($template);

    // Write compiled PHP
    $out_file = $out_dir . '/' . str_replace('/', '__', $rel) . '.php';
    file_put_contents($out_file, "<?php\n" . $template);

    // Lint
    $cmd = 'php -l "' . $out_file . '" 2>&1';
    $res = shell_exec($cmd);
    if (strpos($res, 'Errors parsing') !== false || strpos($res, 'Parse error') !== false) {
        $errors[$rel] = $res;
    }
}

if (empty($errors)) {
    echo "No template parse errors found.\n";
} else {
    foreach ($errors as $k => $v) {
        echo "Error in template: $k\n";
        echo $v . "\n";
    }
}
