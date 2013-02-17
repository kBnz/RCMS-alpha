<?php
/**
 * 指定ファイルを</head>の直前に挿入する
 */
function smarty_function_head_include($params, &$smarty) {
    $files = $smarty->get_template_vars('_headinclude_');
    if (!$files) {
        $files = array();
    }
    $file = $params['file'];
    if (!in_array($file, $files)) {
        $files[] = $file;
    }
    $smarty->assign('_headinclude_', $files);
    return "";
}
