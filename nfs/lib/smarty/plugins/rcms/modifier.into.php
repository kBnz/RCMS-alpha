<?php
function smarty_modifier_into($str, $tag) {
    $len = mb_strlen($str);
    $s = array();
    for ($i = 0 ; $i < $len ; $i++) {
        $s[] = mb_substr($str, $i, 1);
    }
    return join($s, $tag);
}
?>
