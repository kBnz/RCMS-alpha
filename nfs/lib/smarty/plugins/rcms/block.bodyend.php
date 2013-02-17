<?php
function smarty_block_bodyend($params, $content, &$smarty, &$repeat) {
    if(!isset($content)){return;} //空の時は返り値を見てないみたい

    $bodyends = $smarty->get_template_vars('_bodyend_');
    if (!$bodyends) {
        $bodyends = array();
    }
    $bodyends[] = trim($content);
    $smarty->assign('_bodyend_', $bodyends);
    return "";
}
?>