<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty preg_replace modifier plugin
 *
 * Type:     modifier<br>
 * Name:     replace<br>
 * Purpose:  simple search/replace
 * @link http://smarty.php.net/manual/en/language.modifier.replace.php
 *          preg_replace (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_rcms_replace($string, $search, $replace)
{
    //前処理とか必要そうなので、必要であれば、書くかも
    //$stringは''で囲むべし
    return preg_replace($search, $replace, $string);
}

/* vim: set expandtab: */

?>
