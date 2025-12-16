<?php
$s = file(__DIR__ . '/../upload/modules/core/uim.php');
$open = 0;
foreach ($s as $i => $l) {
    $delta = substr_count($l, '{') - substr_count($l, '}');
    if ($delta !== 0) {
        echo 'Line '.($i+1)." delta=".$delta." open_before=".$open."\n";
    }
    $open += $delta;
    if ($open < 0) echo "Negative at " . ($i+1) . "\n";
}
echo "Final open=" . $open . "\n";

// Also print the first and last 20 lines for context
echo "--- First 40 lines ---\n";
for ($i=0;$i<40;$i++){ echo ($i+1) . ': ' . (isset($s[$i])?$s[$i]:'') ; }

echo "--- Last 60 lines ---\n";
$n = count($s);
for ($i=max(0,$n-60); $i<$n; $i++){ echo ($i+1) . ': ' . $s[$i]; }
