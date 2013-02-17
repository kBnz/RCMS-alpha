<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty rcms_file_exists modifier plugin
 *
 * Type:     modifier<br>
 * Name:     rcms_file_exists<br>
 * Purpose:  get file exists
 * @author   RCMS
 * @param string
 * @return int
 */
function smarty_modifier_rcms_file_exists($path)
{
    //自分の配下以外のファイルは存在確認できないように
    if(substr($path,0,1) == "/" && is_public_file(ROOT_DIR."/html",ROOT_DIR."/html".$path) 
        && is_file(ROOT_DIR."/html".$path)){
        return true;
    }else{
        return false;
    }
}

/* vim: set expandtab: */
?>
