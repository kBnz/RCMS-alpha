<?php
/**
 * Smarty plugin
 * @category CMS
 * @package  Smarty
 * @author   金子 和正 <kazumasa@diverta.co.jp>
 */
function smarty_modifier_pg_dateformat($string, $format=null) {
    if(!$format){$format = "%Y/%m/%d(%%w%%) %H:%M";}
    return pg_dateformat($format, $string);
}
?>
