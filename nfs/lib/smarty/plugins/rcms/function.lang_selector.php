<?php
/*
 * 多言語用の横リンク作成
 */
function smarty_function_lang_selector($params, &$smarty) {
    global $auth;

    $meta = $params['meta'];
    $link = $params['link'];

    // 副言語がないなら何もしない
    if (count($meta['langs']) < 2) {
        return "";
    }

    $tr = RCMS_Translate::getInstance();
    $strHasCreated = $tr->translate('/label/has_created');           // 作成済み
    $strHasNotCreated = $tr->translate('/label/has_not_created');    // 未作成
    $strApprovalWaiting = $tr->translate('/label/approval_waiting'); // 承認待ち

    $list = array();
    foreach ($meta['langs'] as $lang) {
        $className = '';
        if ($lang['lang'] == $meta['lang']) {
            $className = 'current ';
        }

        $theLink = $link . '&_doc_lang=' . $lang['lang'];
        switch ($lang['status']) {
            case 0:
                $li = '<li class="' . $className . 'none"><a href="' . $theLink . '">' . $lang['disp_lang'] . '<span>('.$strHasNotCreated.')</span></a></li>';  // 未作成
                break;
            case 1:
                if ($lang['latest']) {
                    $className .= ' latest';
                }
                else {
                    $className .= ' not_latest';
                }
                $li = '<li class="' . $className . '"><a href="' . $theLink . '">' . $lang['disp_lang'] . '<span>('.$strHasCreated.')</span></a></li>'; // 作成済
                break;
            case 2:
                $li = '<li class="' . $className . 'waiting"><a href="' . $theLink . '&_doc_waiting=1">' . $lang['disp_lang'] . '<span>('.$strApprovalWaiting.')</span></a></li>'; // 承認待ち
                break;
        }
        $list[] = $li;
    }
    $bag = $meta['bag'];

    $ul =
        '<div class="doc_lang_status_box doc_lang_status">' .
            '<ul>' .  join("\n", $list);
            $ul .='<li class="addOnetime">';

            //$ul .= '<select><option value="">' . $tr->translate('/label/smarty/mygengo') . '</option>';
            //$ul .= '<option value="">' . $tr->translate('/label/smarty/google_translate') . '</option>';
        if($auth["onetime"]["insert"]){
            //$ul .= '<option value="">' . $tr->translate('/label/smarty/lang_selector_onetime_link') . '</option>';
            $ul .='<a href="/management/onetime/onetime_edit/?target=' . urlencode($bag->getTarget()->raw()) . '">' . $tr->translate('/label/smarty/lang_selector_onetime_link') . '</a>';
        }
            //$ul .='</select>';

    $ul .=
              '</li>' .
            '</ul>' .
            '<div class="clear"></div>' .
        '</div>';
    return $ul;
}
