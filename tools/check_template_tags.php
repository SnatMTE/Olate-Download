<?php
$tpldir = __DIR__ . '/../upload/templates/olate';
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($tpldir));
foreach ($rii as $f) {
    if (!$f->isFile()) continue;
    $s = file_get_contents($f->getPathname());
    $ifc = substr_count($s, '{if:');
    $endif = substr_count($s, '{endif}');
    $blocks_open = substr_count($s, '{block:');
    $blocks_close = substr_count($s, '{/block:');
    if ($ifc != $endif || $blocks_open != $blocks_close) {
        echo $f->getPathname() . " ifs:$ifc endifs:$endif blocks_open:$blocks_open blocks_close:$blocks_close\n";
    }
}
