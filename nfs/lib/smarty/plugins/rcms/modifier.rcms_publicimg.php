<?php
/**
 * 公開・非公開・制限公開のicon画像タグを取得する
 */
function smarty_modifier_rcms_publicimg($flg) {
    $flg = (string)$flg;
    $tag = "";
    if (in_array($flg, array('1', 'public', '公開'))) {
        $tag = '<img src="/images/management/check.gif" title="public" />';
    }
    elseif (in_array($flg, array('0', 'private', '非公開'))) {
        $tag = '<img src="/images/management/batu.gif" title="private" />';
    }
    //elseif (in_array($flg, array('2', 'restrict', '制限'))) {
    else {
        $tag = '<img src="/images/management/sankaku.gif" title="restrict" />';
    }
    return $tag;
}
?>
