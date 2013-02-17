<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty rcms_file_size modifier plugin
 *
 * Type:     modifier<br>
 * Name:     rcms_file_size<br>
 * Purpose:  get file size
 * @author   RCMS
 * @param string
 * @return int
 */
function smarty_modifier_rcms_file_size($path,$format = 1)
{
        return getFileSize($path,$format);
}

/* vim: set expandtab: */

?>
