<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty rcms_get_photo_path modifier plugin
 *
 * Type:     modifier<br>
 * Name:     rcms_file_size<br>
 * Purpose:  get file size
 * @author   RCMS
 * @param string
 * @return int
 */
function smarty_modifier_rcms_get_photo_path($contents,$no = 1)
{
    $matches = array();
    $pattern = '/<img(.+?)src=("|\')(.+?)("|\')(.+?)>/si';
    preg_match_all($pattern, $contents, $matches);
    foreach ($matches[3] as $key => $val) {
        if ($key + 1 == $no) {
            return $val;
        }
    }
    return false;
}

/* vim: set expandtab: */

?>
