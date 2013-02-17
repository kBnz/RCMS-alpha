<?php
/**
 * Smarty plugin
 * @category CMS
 * @package  Smarty
 * @author   金川 正樹 <kanagawa@diverta.co.jp>
 *  例:{assign_topics_ext ext_columns=$row.ext_columns id='02' ext_type='url' var='value'}
 */
function smarty_function_assign_topics_ext($params, &$smarty) {
    global $cn;

    //セットされる要素
    $ext_data = $params['ext_data']; //取得する拡張データの各項目のデータ
    $ext_type = $params['ext_type']?$params['ext_type']:"value"; //取得する拡張データのタイプ
    $pieces   = $params['pieces']?$params['pieces']:"/"; //複数選択のデータのセパレート文字列
    $var = $params['var']; //代入する変数名
    $return_val = "";

    $id = $params['id']; //取得する拡張データのID
    $ext_columns = $params['ext_columns']; //その記事の拡張項目全体のデータ
    $print = $params['print']; //trueの場合はassignと同時にprintする
    $return_flg = $params['return_flg']; //trueの場合はassignと同時にreturnする
    if($var == "" && !$params['return_flg']){$print = 1;} //変数名がセットされていない場合でリターンんしない場合もprintする

    //必要な要素の整形
    $ext_col_nm = "ext_col_".sprintf('%02d', $params['id']);
    //本来は$ext_columns["straight"]の内容をセットしてほしいけど、わかりにくいので
    if($ext_columns["group"] && $ext_columns["straight"]){
        $ext_columns = $ext_columns["straight"];
    }

    //$idを親とするグループ化された塊を取得する
    if($ext_type == "group"){
        $group = array();
        if(is_array($ext_columns)){

            //親のIDを取得
            foreach($ext_columns as $ext_data){
                if(substr($ext_data["ext_col_nm"],-2) == $id){
                    $parent_id = $ext_data["topics_group_ext_id"];
                }
            }
            foreach($ext_columns as $ext_data){
                if($ext_data["ext_group_id"] == $parent_id || $ext_data["topics_group_ext_id"] == $parent_id){
                    $group[$ext_data["repeatCnt"]][substr($ext_data["ext_col_nm"],-2)] = $ext_data;
                }
            }
        
        }
        $return_val = $group;
    }

    //該当するデータがあるか?
    if($id && $ext_columns){
        $data_exists_flg = 0;
        foreach($ext_columns as $ext_data){
            if($ext_data["ext_col_nm"] == $ext_col_nm){$data_exists_flg = 1;break;}
        }
        if(!$data_exists_flg){$smarty->assign($var, "");return ;}
    }

    if($ext_data["type"] == 4 && $ext_type == "url"){
        $return_val = $ext_data["file_url"];
    }elseif($ext_data["type"] == 9 && $ext_type == "url"){
        $return_val = $ext_data["file_url"];
    }elseif($ext_data["type"] == 4 && $ext_type == "url_L"){
        $return_val = $ext_data["file_url_L"];
    }elseif($ext_data["type"] == 4 && $ext_type == "url_S"){
        $return_val = $ext_data["file_url_S"];
    }elseif($ext_data["type"] == 9 && $ext_type == "filesize"){
        $return_val = getFileSize($ext_data["file_url"],1);
    }elseif($ext_data["type"] == 4 && $ext_type == "filesize"){
        $return_val = getFileSize($ext_data["file_url"],1);
    }elseif($ext_data["type"] == 4 && $ext_type == "filesize_L"){
        $return_val = getFileSize($ext_data["file_url_L"],1);
    }elseif($ext_data["type"] == 4 && $ext_type == "filesize_S"){
        $return_val = getFileSize($ext_data["file_url_S"],1);
    }elseif($ext_data["type"] == 2 && $ext_type == "value"){
        $return_val = $ext_data["options"][$ext_data["value"]];
    }elseif($ext_data["type"] == 2 && $ext_type == "key"){
        $return_val = $ext_data["value"];
    }elseif($ext_data["type"] == 5 && $ext_type == "value"){
        if(is_array($ext_data["value"])){
            $return_val = implode($pieces,$ext_data["value"]);
        }else{
            $return_val = $ext_data["value"];
        }
    }elseif($ext_data["type"] == 5 && $ext_type == "array"){
        if(is_array($ext_data["value"])){
            $return_val = $ext_data["value"];
        }else{
            $return_val = array($ext_data["value"]);
        }
    }elseif($ext_data["type"] == 10 && $ext_type == "table"){ //テーブルの場合

        $table_data = "";
        for($row_key = 1;$row_key <=$ext_data["options"]["rows"];$row_key++){

            //データがあるか確認
            $data_flg = 0;
            for($col_key = 1;$col_key <=$ext_data["options"]["cols"];$col_key++){
                if($ext_data["value"][$row_key][$col_key] != '' || $ext_data["options"][$row_key."-".$col_key] != ''){
                    $data_flg = 1;
                    break;
                }
            }
            if($data_flg==0){continue;}

            $table_data .= "<tr>";

            for($col_key = 1;$col_key <=$ext_data["options"]["cols"];$col_key++){

                if($ext_data["options"][$row_key."-".$col_key."TH"]){$table_data .= "<th>";}else{$table_data .= "<td>";}

                if($ext_data["options"][$row_key."-".$col_key."LOCK"]){
                    if($ext_data["options"][$row_key."-".$col_key]){$table_data .= $ext_data["options"][$row_key."-".$col_key];}
                }else{
                    $table_data .= $ext_data["value"][$row_key][$col_key];
                }

                if($ext_data["options"][$row_key."-".$col_key."TH"]){$table_data .= "</th>";}else{$table_data .= "</td>";}

            }

            $table_data .= "</tr>";
        }
        if($table_data){
            $return_val  = "<table><tbody>";
            $return_val .= $table_data;
            $return_val .= "</tbody></table>";
        }

    }elseif($ext_type == "cells"){
        $return_val = $ext_data;

    }elseif($ext_data["type"] == 6 && $ext_type == "value"){
        //WYSYWIGの場合
        $return_val = $ext_data["value"];
    }elseif($ext_data["type"] == 12 && $ext_type == "value"){
        //都道府県
        global $arrTdfk;
        $return_val = $arrTdfk[$ext_data["value"]];
    }elseif($ext_data["type"] == 7 && $ext_type == "url"){
        $return_val = $ext_data["value"]["url"];
    }elseif($ext_data["type"] == 7 && $ext_type == "value"){
        $return_val = $ext_data["value"]["title"];
    }elseif($ext_data["type"] == 20 && $ext_type == "data"){
        //関連情報の場合
        $module_nm = $ext_data['options']['module'];
        $id = $ext_data['value'];

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
        elseif ($module_nm == "blog_header") {
            $rs = BlogUtil::getHeader($cn, $id);
        }
        $return_val = $rs;
    }elseif($ext_type == "gmap_url"){
        $return_val = "/direct/location/googlemap2/gmap_x=".$ext_data["value"]["gmap_x"]."&gmap_y=".$ext_data["value"]["gmap_y"]."&gmap_zoom=".$ext_data["value"]["gmap_zoom"]."&gmap_type=".$ext_data["value"]["gmap_type"]."&height=".$params["height"]."&width=".$params["width"];
    }elseif($ext_type == "audio"){
        $return_val = '
        <div id="id'.md5($ext_data["file_url"]).'"></div>
<script type="text/javascript">
google.load("swfobject", "2.2");
</script>
<script type="text/javascript">
swfobject.embedSWF("/js/player_mp3/player_mp3_maxi.swf","id'.md5($ext_data["file_url"]).'",200,20,"8","/js/swfobject2/expressInstall.swf",{mp3:"'.$ext_data["file_url"].'",showvolume:1});
</script>
';
    }elseif($ext_data["type"] == 21 && $ext_type == "value"){
        //HTML
        $return_val = $ext_data["value"];
    }elseif($ext_data["type"] == 25 && $ext_type == "player"){
        //動画
        if(!$params["height"]){$height = "480px";}
        if(!$params["width"]){$width = "360px";}
        $return_val = '
<div id="mediaplayer'.md5($ext_data["file_url"]).'"></div>

<script type="text/javascript" src="/js/jwplayer/jwplayer.js"></script>
<script type="text/javascript">
	jwplayer("mediaplayer'.md5($ext_data["file_url"]).'").setup({
		flashplayer: "/js/jwplayer/player.swf",
		file: "'.$ext_data["file_url"].'",
		//image: "preview.jpg",
		width:"'.$width.'",
		height:"'.$height.'"
	});
</script>
';

    }elseif($ext_type == "value"){
        if(is_array($ext_data["value"])){
            $return_val = $ext_data["value"];
        }else{
            $return_val = nl2br($ext_data["value"]);
        }
    }elseif($ext_type == "title"){
        $return_val = $ext_data["title"];
    }elseif($ext_type == "url"){
        $return_val = $ext_data["url"];
    }

    $smarty->assign($var, $return_val );
    if($print){
        print  $return_val;
    }
    if($return_flg){
        return  $return_val;
    }

}
?>
