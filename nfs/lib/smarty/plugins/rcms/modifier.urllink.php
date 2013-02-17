<?php
/**
 * Smarty urllink modifier plugin
 *
 * Type:     modifier<br>
 * Name:     urllink<br>
 * @param string
 * @return string
 */
function smarty_modifier_urllink($string,$enable_flg)
{
    if(!$enable_flg){return $string;}

    $regex = '/(http:\/\/|https:\/\/|ftp:\/\/|mailto:)([a-zA-Z0-9\-_.~\/?@&=+%#:]+)/i';
    return preg_replace($regex, "<a href='\\1\\2' target=\"_blank\">\\1\\2</a>", $string);
}
?>
