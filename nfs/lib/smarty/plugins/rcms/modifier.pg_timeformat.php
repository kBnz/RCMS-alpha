<?php
function smarty_modifier_pg_timeformat($string) {
    // 00:00形式のみ対応しています。
    $s = '';
    if (preg_match("/^([0-9]+):([0-9]+)/", $string, $rs)) {
        $s = sprintf("%02d:%02d", (int)$rs[1], (int)$rs[2]);
    }
    return $s;
}
?>
