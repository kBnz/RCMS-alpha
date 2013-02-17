<?php

function smarty_function_assign_topics_extrel($params, &$smarty) {
    global $cn;
    $lang = isset($params['lang']) ? $params['lang'] : $smarty->get_template_vars("mylang");
    $var = $params['var'];

    // 指定の方法は2つ
    // 1.直接拡張のデータを設定
    // 2.noとorderを指定

    $ext = $params['ext'];
    if ($params['no'] && $params['topics']) {
        $no = $params['no'];
        $order = isset($params['order']) ? (int)$params['order'] : null;
        $topicData = $params['topics'];
        if (is_array($params['topics']['ext_columns']['straight'])) {
            for ($i = 0, $cnt = count($params['topics']['ext_columns']['straight']) ; $i < $cnt ; $i++) {
                if ($params['topics']['ext_columns']['straight'][$i]['no'] == $no) {
                    $repeatCnt = $params['topics']['ext_columns']['straight'][$i]['repeatCnt'];
                    if ($order == $repeatCnt) {
                        $ext = $params['topics']['ext_columns']['straight'][$i];
                        break;
                    }
                }
            }
        }
    }
    $rs = null;

    $module_nm = $ext['options']['module'];
    $id = $ext['value'];
    if ($module_nm && $id) {

        #$rs['id'] = $id;
        #$rs['module_nm'] = $module_nm;

        // ここからが課題 (統一インターフェースがないので個別にやるしかない)
        if ($module_nm == "topics") {
            $o = new Topics($cn);
            $rs = $o->getTopicsDetail($id, $lang);
        }
        elseif ($module_nm == "staticcontents") {
            $o = new Staticcontents();
            $rs = $o->getStaticData($id);
        }
        elseif ($module_nm == "member") {
            $o = new MemberUtil($cn);
            $rs = $o->getMemberDetail($id);
            $rs['subData'] = $o->getMemberSubData($id);
        }
        elseif ($module_nm == "location") {
            $rs = Location::GetLocationDetail($cn, $id, $lang);
        }
    }

    $smarty->assign($var, $rs);
}
