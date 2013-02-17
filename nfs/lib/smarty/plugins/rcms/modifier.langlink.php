<?php
/**
 * Smarty plugin
 * @category CMS
 * @package  Smarty
 * @author   金子 和正 <kazumasa@diverta.co.jp>
 */
function smarty_modifier_langlink($lang) {
    global $requestContext;
    $url = $_SERVER['REQUEST_URI'];
    $thisLang = $requestContext['locale']->getSiteLanguage();
    if ($thisLang) {
        // 先頭にlangが入っていたら取り除く
        $url = preg_replace("!^/$thisLang!", '', $url);
    }
    // _lang=xxを取り除く、?だけ残らないようにする
    $url = preg_replace('!(_lang=[a-z]+&?|\?_lang=[a-z]+&?$)!', '', $url);

    // 主言語だったら言語は省略する
    if ($lang != UpdateHistory::getPrimaryLanguage()) {
        $url = "/$lang$url";
    }
    return $url;
}
?>
