<?php
/**
 * Smarty plugin
 * @category CMS
 * @package  Smarty
 * @author   金子 和正 <kazumasa@diverta.co.jp>
 */

/*
* Smarty plugin
* -------------------------------------------------------------
* File:     compiler.imageurl.php
* Type:     compiler
* Name:     imageurl
* Purpose:  イメージのトップURLを返す
*
* -------------------------------------------------------------
*/

function smarty_compiler_imageurl($tag_arg, &$smarty) {
    global $logger ;
    return "echo '". IMAGES_URL. "'" ;
}

?>
