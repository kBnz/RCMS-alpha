<?php
/**
 * 管理画面の送信ボタンをデコレーションする
 */
function smarty_block_buttonbox($params, $content, &$smarty) {
    if(!isset($content)){return;} //空の時は返り値を見てないみたい

    $content = preg_replace('/<button([^>]*)id="([^"]*)"([^>]*)>([^<]+)<\/button>/', '<li id="$2"><rcms_button$1$3>$4</rcms_button></li>', $content);
    $content = preg_replace('/(<button[^>]*>)([^<]+)(<\/button>)/', '<li>$1$2$3</li>', $content);
    $content = str_replace(array("<rcms_button","</rcms_button>"),array("<button","</button>"),$content);
    return '<div class="buttonbox"><ul>' . $content . '</ul></div><div class="clear"></div>';
}
?>