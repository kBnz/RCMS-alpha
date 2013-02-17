<?php
function smarty_function_pager($params, &$smarty) {
    global $requestContext;
    $args = array_slice(func_get_args(), 1);

    $tr = RCMS_Translate::getInstance($requestContext['locale']);

    $info = $params['info'];
    $num = 10;

    //モバイルの場合　デザインの部分も必要
    if(is_mobile()){
        $info["param"] = str_replace("&","&amp;",$info["param"]);
        $info["param"] = str_replace("&amp;amp;","&amp;",$info["param"]);
    }
    //require_once $smarty->_get_plugin_filepath('shared','translate');

    // 0件の処理
    if (!$info['totalCnt']) {
        if (isset($params['msg'])) {
            $msg = $params['msg'];
        }
        else {
            //$msg = "該当するデータがありません。";
        }
        return htmlspecialchars($msg);
    }

    $start = $info['pageNo'] - ($info['pageNo'] - 1) % $num;
    $loop  = min($info['totalPageCnt'] + 1, $start + $num);

    // URLの作成
    if (preg_match('!^(/management/[^/]+/[^/]+/)!', $_SERVER['REQUEST_URI'], $rs)) {
        $url = $rs[1] . $info["param"];
    }
    else {
        $url = "/management/" . ($info["param"] ? "?" . $info["param"] : '');
    }
    $sep = "?";
    if ($info["param"] != '') {
        $sep = "&amp;";
    }

    // htmlの作成
    $buf = '<p class="page_links">';
    //$buf .= sprintf('<span class="allCnt">%d件中</span>', $info["totalCnt"]);
    //$buf .= sprintf('<span class="range">%d-%d件目</span>', $info["firstIndex"], $info["lastIndex"]);
    $buf .= '<span class="summary">';
    $buf .= $tr->translate('/label/pagination/summary', $info["totalCnt"], $info["firstIndex"], $info["lastIndex"]);
    $buf .= '</span>';

    if ($info["pageNo"] > 1) {
        $buf .= sprintf('<a href="%s" title="first page" class="first"><span>' . $tr->translate('/label/pagination/first') . '</span></a>', $url);
        $buf .= sprintf('<a href="%s%spageID=%d" title="previous page" class="back"><span>' . $tr->translate('/label/pagination/prev') . '</span></a>', $url, $sep, ($info["pageNo"] - 1));
    }

    for ($i = $start ; $i < $loop ; $i++) {
        if ($i != $info['pageNo']) {
            $buf .= sprintf('<a href="%s%spageID=%d" title="page %d" class="page"><span>%d</span></a>', $url, $sep, $i, $i, $i);
        }
        else {
            $buf .= sprintf('<span class="current">%d</span>', $i);
        }
    }

    if ($info["pageNo"] != $info["totalPageCnt"]) {
        $buf .= sprintf('<a href="%s%spageID=%d" title="next page" class="next"><span>' . $tr->translate('/label/pagination/next') . '</span></a>', $url, $sep, ($info["pageNo"] + 1));
        $buf .= sprintf('<a href="%s%spageID=%d" title="last page" class="last"><span>' . $tr->translate('/label/pagination/last') . '</span></a>', $url, $sep, ($info["totalPageCnt"]));
    }

    $buf .= "</p>";

    return $buf;
}
