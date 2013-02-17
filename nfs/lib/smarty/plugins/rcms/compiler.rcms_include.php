<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {rcms_include} compiler function plugin
 *
 * Type:     compiler function<br>
 * Name:     rcms_include<br>
 * Purpose:  includeのwrapper。includeするファイルの優先度を設けた。使用法はincludeと同じ。
 * @param Smarty_Compiler
 */
function smarty_compiler_rcms_include($tag_attrs, &$compiler)
{
    $_params = $compiler->_parse_attrs($tag_attrs);

    $include_file = $_params['file'];

    // v2管理画面ではv2用のテンプレートを優先
    if( is_admin_v2() ){
        $v2_admin_path = preg_replace("/([^\/]+.html)$/", "v2/$1", $include_file); // hoge/bar/foo.html → hoge/bar/v2/foo.html
        $include_file  = find_lib_path($v2_admin_path)? $v2_admin_path : $include_file;
    }

    unset($_params['file']);

    $arg_list = array();
    foreach ($_params as $key => $val) {
        $arg_list[] = "'" . $key ."' => " .$val;
    }
/*
    $smarty_include_tpl_file =
        "(file_exists(SITE_TEMPLATE_PATH . '/' . " . $include_file . ") ? (SITE_TEMPLATE_PATH . '/') : (file_exists(SERVER_TEMPLATE_PATH . '/' . " . $include_file . ") ? (SERVER_TEMPLATE_PATH . '/') : '')) . " . $include_file;

    $param = "array('smarty_include_tpl_file' => " . $smarty_include_tpl_file . ", 'smarty_include_vars' => array(".implode(',', (array)$arg_list)."))";
*/

    $param = "array('smarty_include_tpl_file' => \$this->findResourcePath(" . $include_file . "), 'smarty_include_vars' => array(".implode(',', (array)$arg_list)."))";
    return "\$this->_smarty_include(". $param .");";
}

/* vim: set expandtab: */
