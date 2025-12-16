<?php
$s = file_get_contents(__DIR__ . '/../upload/modules/core/uim.php');
$tokens = token_get_all($s);
// Print last 200 tokens with index
$start = max(0, count($tokens)-200);
for ($i=$start; $i<count($tokens); $i++) {
    $t = $tokens[$i];
    if (is_array($t)) {
        echo $i.': '.token_name($t[0])." (line {$t[2]}) => '".str_replace("\n","\\n",$t[1])."'\n";
    } else {
        echo $i.': CHAR => "'.$t.""."\n";
    }
}
