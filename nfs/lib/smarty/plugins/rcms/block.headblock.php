<?php
function smarty_block_headblock($params, $content, &$smarty, &$repeat) {
    if(!isset($content)){return;} //空の時は返り値を見てないみたい

    $heads = $smarty->get_template_vars('_headblock_');
    if (!$heads) {
        $heads = array();
    }
    $heads[] = trim($content);
    $smarty->assign('_headblock_', $heads);
    return "";
}
?>