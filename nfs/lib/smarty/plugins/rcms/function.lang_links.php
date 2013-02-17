<?php
function smarty_function_lang_links($params, &$smarty) {
    $langs = $smarty->get_template_vars('i18n_supported_languages');
    if (!$langs || count($langs) == 1) {
        // 多言語サポート無し
        return '';
    }

    $req_lang = $smarty->get_template_vars('i18n_request_language');
    $cds = array();
    foreach ($langs as $lang) {
        $cds[] = $lang['lang'];
    }
    $cds_expression = '(' . join('|', $cds) . ')';

    // クエリから_langを取り除く
    $url = preg_replace('!(?<=/|\?|&)_lang=' . $cds_expression . '&?!', '', $_SERVER['REQUEST_URI']);
    // パスの先頭にある言語コードを取り除く
    $url = preg_replace('!^/' . $cds_expression . '/!', '/', $url);
    // お尻に?&が残っていたら取り除く
    $url = preg_replace('![?|&]$!', '', $url); 

    $html = array();
    foreach ($langs as $lang) {
        if ($lang['lang'] != $req_lang) {
            $html[] = '<link rel="alternate" hreflang="' . $lang['lang'] . '" title="" href="/' . $lang['lang'] . $url . '" >';
        }
    }
    return join("\n", $html);
}

