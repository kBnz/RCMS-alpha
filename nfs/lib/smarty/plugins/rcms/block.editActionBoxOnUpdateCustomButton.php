<?php
function smarty_block_editActionBoxOnUpdateCustomButton($params, $content, &$smarty, &$repeat) {
    if (!isset($content)) {
        return;
    }
    $list = $smarty->get_template_vars('_editActionBoxOnUpdateCustomButton_');
    if (!$list) {
        $list = array();
    }
    $list[] = trim($content);
    $smarty->assign('_editActionBoxOnUpdateCustomButton_', $list);
    return "";
}
?>