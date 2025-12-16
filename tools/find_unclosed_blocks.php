<?php
$file = __DIR__ . '/../upload/modules/core/uim.php';
$s = file($file);
$n = count($s);
for ($i=0; $i<$n; $i++) {
    if (preg_match('/^\s*(function|class)\b/', $s[$i])) {
        $type = trim($s[$i]);
        $open = substr_count($s[$i], '{') - substr_count($s[$i], '}');
        $found = false;
        for ($j=$i+1; $j<$n; $j++) {
            $open += substr_count($s[$j], '{') - substr_count($s[$j], '}');
            if ($open === 0) { $found = true; break; }
            if ($open < 0) break; // unexpected
        }
        if (!$found) {
            echo "Unclosed after line " . ($i+1) . ": " . trim($s[$i]) . "\n";
        }
    }
}
?>