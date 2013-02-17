<?php
function smarty_function_rcms_includelayout($params, &$smarty) {
    $no = $params['no'];
    $ret = $smarty->fetch("page/admin/layout/page_edit_layout_{$no}.html");
    $ret = preg_replace('/\r\n/', '', $ret);
    $ret = preg_replace('/\r/', '', $ret);
    $ret = preg_replace('/\n/', '', $ret);
    return $ret;
}
