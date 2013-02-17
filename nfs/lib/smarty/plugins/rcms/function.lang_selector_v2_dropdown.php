<?php
/*
 * 多言語用の横リンク作成
 */
function smarty_function_lang_selector_v2_dropdown($params, &$smarty) {
    global $auth;

    $meta = $params['meta'];
    $link = $params['link'];
    $currentLang = null;
    $bag = $meta['bag'];

    // 副言語がないなら何もしない
    if (!USE_MULTILANG) {
        return "";
    }
    //新規追加時は主言語だけ表示
     if (!$bag) {
         $ul =
             '<button type="button" id="lang_selector" class="rcms_ui_select"><span class="icon-lang"></span>' . $meta['primary_lang_nm'] . '</button>' .
            '<div class="clear"></div>';
         return $ul;
    }

    $tr = RCMS_Translate::getInstance();
    $hasCreated = '<span class="icon-checked"></span>';           // 作成済み
    $hasNotCreated = '<span class="icon-unchecked"></span>';        // 未作成

    $strHasCreated = $tr->translate('/label/has_created');           // 作成済み
    $strHasNotCreated = $tr->translate('/label/has_not_created');    // 未作成
    $strApprovalWaiting = $tr->translate('/label/approval_waiting'); // 承認待ち

    $list = array();
    foreach ($meta['langs'] as $lang) {
        $className = '';
        if ($lang['lang'] == $meta['lang']) {
            $className = 'current ';
            $currentLang = $lang['disp_lang'];
        }

        $theLink = $link . '&_doc_lang=' . $lang['lang'];
        switch ($lang['status']) {
            case 0:
                $li = '<li class="' . $className . 'none">' . $hasNotCreated . '<a href="' . $theLink . '"> ' . $lang['disp_lang'] . '</a><span class="label">' . $strHasNotCreated . '</span></li>';  // 未作成
                break;
            case 1:
                if ($lang['latest']) {
                    $className .= ' latest';
                }
                else {
                    $className .= ' not_latest';
                }
                $li = '<li class="' . $className . '">' . $hasCreated .'<a href="' . $theLink . '"> ' . $lang['disp_lang'] . '</a><span class="label created">' . $strHasCreated . '</span></li>'; // 作成済
                break;
            case 2:
                $li = '<li class="' . $className . 'waiting">' . $hasCreated . '<a href="' . $theLink . '&_doc_waiting=1"> ' . $lang['disp_lang'] . '</a><span class="label waiting">' . $strApprovalWaiting . '</span></li>'; // 承認待ち
                break;
        }
        $list[] = $li;
    }

    $ul =
        '<button type="button" id="lang_selector" class="rcms_ui_select"><span class="icon-lang"></span>' . $currentLang . '</button>' .
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
        '<div class="clear"></div>';
    return $ul;
}
