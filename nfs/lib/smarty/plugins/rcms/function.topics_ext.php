<?php
/**
 * Smarty plugin
 * @category CMS
 * @package  Smarty
 * @author   ‹àì ³Ž÷ <kanagawa@diverta.co.jp>
 */
function smarty_function_topics_ext($params, &$smarty) {

    $ext_data = $params['ext_data'];
    $ext_type = $params['ext_type'];
    $pieces   = $params['pieces']?$params['pieces']:"/";

    $id = $params['id'];
    $ext_columns = $params['ext_columns'];
    $ext_col_nm = "ext_col_".sprintf('%02d', $params['id']);

		if($id && $ext_columns){
        $data_exists_flg = 0;
        foreach($ext_columns as $ext_data){
            if($ext_data["ext_col_nm"] == $ext_col_nm){$data_exists_flg = 1;break;}
        }
        if(!$data_exists_flg){return ;}
		}

    if($ext_data["type"] == 4 && $ext_type == "url"){
        return $ext_data["file_url"];
    }elseif($ext_data["type"] == 4 && $ext_type == "url_L"){
        return $ext_data["file_url_L"];
    }elseif($ext_data["type"] == 2 && $ext_type == "value"){
        return $ext_data["options"][$ext_data["value"]];
    }elseif($ext_data["type"] == 2 && $ext_type == "key"){
        return nl2br($ext_data["value"]);
    }elseif($ext_data["type"] == 5 && $ext_type == "value"){
        if(is_array($ext_data["value"])){
            return implode($pieces,$ext_data["value"]);
        }else{
            return $ext_data["value"];
        }
    }elseif($ext_type == "value"){
        return nl2br($ext_data["value"]);
    }elseif($ext_type == "title"){
        return $ext_data["title"];
    }
}
?>
