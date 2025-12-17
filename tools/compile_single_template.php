<?php
// Compile a single template file without requiring site globals
require __DIR__ . '/../upload/modules/core/uim.php';
$tplfile = __DIR__ . '/../upload/templates/olate/admin/start.tpl.php';
$contents = file_get_contents($tplfile);
$rc = new ReflectionClass('uim_template');
$tpl = $rc->newInstanceWithoutConstructor();
$tpl->dir = 'templates/olate';
$tpl->file = 'admin/start.tpl.php';
$tpl->template = $contents;
// make sure parse functions available
$tpl->parse($tpl->template);
echo $tpl->template;
