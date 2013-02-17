<?php
/**
 * Smarty plugin
 * @category CMS
 * @package  Smarty
 * @author   金子 和正 <kazumasa@diverta.co.jp>
 */
function smarty_modifier_params($params, $pref = "") {
    $arr = array();
    foreach ($params as $key => $val) {
        $arr[] = $key . "=" . $val;
    }
    if ($arr) {
        return $pref . join("&", $arr);
    }
    else {
        return "";
    }
}
?>
