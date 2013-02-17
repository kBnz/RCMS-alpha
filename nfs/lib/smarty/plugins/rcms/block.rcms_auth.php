<?php
/**
 * @param target
 * @param not
 */
function smarty_block_rcms_auth($params, $content, &$smarty, &$repeat) {
    if(!isset($content)){return;} //空の時は返り値を見てないみたい

    $targets = preg_split('/\s*\|\|\s/', $params['target']);

    $user = RCMSUser::getUser();

    $ok = false;
    foreach ($targets as $target) {
        // actionとtargetに分割
        if (!preg_match('/^(?P<actions>.*?):(?P<target>.*)$/', $target, $rs)) {
            throw new Exception('rcms_authの引数が間違っています');
        }

        // 複数アクションのorに対応
        $actions = explode('|', $rs['actions']);
        
        foreach ($actions as $action) {
            if ($user->getResourceAuth($rs['target'], $action)->isAble()) {
                $ok = true;
                break;
            }
        }

        if ($ok) {
            break;
        }
    }

    // [肯定の場合] 権限があるときに中身を出力する
    // [否定の場合] 権限がないときに中身を出力する
    $not = (bool)$params['not'];
    if ($ok && !$not || !$ok && $not) {
        return $content;
    }

    return '';
}
