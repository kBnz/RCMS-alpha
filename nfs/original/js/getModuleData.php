<?php
if(substr($_SERVER["SERVER_ADDR"],0,11)=="192.168.25."){define("NO_ACCESSCOUNT","1");}

//基本ファイルのインクルード
require_once("default.php");

$module_type = $_REQUEST['module_type'] ;       // モジュールタイプの取得
$contents_type = $_REQUEST['contents_type'] ;   // コンテンツタイプの取得
$data_kbn = $_REQUEST['data_kbn'] ;             // データ区分

$conf_type = Page::getConf_type($rcmsdb);

$data = array() ;
switch ($data_kbn) {
    case 'temp' :
        if (!$conf_type[$module_type]) {
            break ;
        }
        if (!$contents_type) {
            // デフォルト値決定
            $def = array_slice($conf_type[$module_type], 0, 1) ;
            $values = array_values($def) ;
            foreach ($values['0']['tpl'] as $key => $val) {
                array_push($data, array(mb_convert_encoding($key,"UTF-8","auto"), mb_convert_encoding($val,"UTF-8","auto"))) ;
            }
        }
        else {
            // コンテンツタイプ指定の場合
            foreach ($conf_type[$module_type] as $key => $val) {
                if ($val['type'] == $contents_type) {
                    $ix = $key ;
                }
            }
            foreach ($conf_type[$module_type][$ix]['tpl'] as $key => $val) {
                array_push($data, array(mb_convert_encoding($key,"UTF-8","auto"), mb_convert_encoding($val,"UTF-8","auto"))) ;
            }
        }
        break ;
    case 'type' :
        if (!$conf_type[$module_type]) {
            break ;
        }
        foreach ($conf_type[$module_type] as $key => $val) {
            array_push($data, array(mb_convert_encoding($val['type'],"UTF-8","auto"), mb_convert_encoding($val['name'],"UTF-8","auto"))) ;
        }
        break ;
    case 'full':
        //print_r($conf_type);
        foreach ($conf_type as $module_type => $conts) {
            if (!$page_used[$module_type]) {
                continue;
            }
            $data[$module_type] = array();
            $data[$module_type]['name'] = $contName[$module_type];
            $data[$module_type]['contents'] = array();
            foreach ($conts as $cont) {
                $data[$module_type]['contents'][$cont['type']] = array();
                $data[$module_type]['contents'][$cont['type']]['name'] = $cont['name'];
                $params = null;
                try {
                    $act = RCMSAction::factory($cont['file']);
                    $params = $act->getParamMeta();
                }
                catch (NotFoundModulePHP $e) {
                    $params = array();
                }
                $data[$module_type]['contents'][$cont['type']]['params'] = $params;
                $data[$module_type]['contents'][$cont['type']]['tpl'] = array();
                foreach ($cont['tpl'] as $tplId=>$name) {
                    $data[$module_type]['contents'][$cont['type']]['tpl'][$tplId] = array('name' => $name);
                }
            }
        }
        break;
/*
    case 'css' :
        if (!$conf_type[$module_type]) {
            break ;
        }
        if (!$contents_type) {
            // デフォルト値決定
            $def = array_slice($conf_type[$module_type], 0, 1) ;
            $values = array_values($def) ;
            foreach ($values['0']['css'] as $key => $val) {
                array_push($data, array(mb_convert_encoding($key,"UTF-8","auto"), mb_convert_encoding($val,"UTF-8","auto"))) ;
            }
        }
        else {
            // コンテンツタイプ指定の場合
            foreach ($conf_type[$module_type] as $key => $val) {
                if ($val['type'] == $contents_type) {
                    $ix = $key ;
                }
            }
            foreach ($conf_type[$module_type][$ix]['css'] as $key => $val) {
                array_push($data, array(mb_convert_encoding($key,"UTF-8","auto"), mb_convert_encoding($val,"UTF-8","auto"))) ;
            }
        }
        break ;
*/
}

echo json_encode($data);
