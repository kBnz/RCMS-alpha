<?php
/**
 * ファイルアップロード系の部品
 */
function smarty_block_fileupload($params, $content, &$smarty) {
    if(!isset($content)){return;} //空の時は返り値を見てないみたい

    global $requestContext;

    $id = $params["id"];
    $default = $params["default"];
    $default_L = $params["default_L"]?$params["default_L"]:$params["default"];
    $hidden_nm = $params["hidden_nm"];
    $file_type = $params["file_type"];
    $multi = $params["multi"]?"true":"false";
    $url = $params["url"];
    $max_file_size = $params["max_file_size"] ? $params["max_file_size"] : (int)RCMSConf::getValue('MAX_FILE_SIZE');
    $max_file_size *= 1024 * 1024; // MBに変換

    $js = '
<script type="text/javascript">
    DelayDoFunction("div","file_upload'.$id.'",function(){file_upload(\''.$id.'\',\''.$url.'\',\''.$hidden_nm.'\',\''.$file_type.'\','.$multi.',{\'sizeLimit\': '.$max_file_size.'});file_upload_ch_obj(j$(\'#filesUploaded_preview'.$id.'\'),\''.$hidden_nm.'\');
    });
</script>
';

    //javascript部分をヘッダに置く
    $heads = $smarty->get_template_vars('_headblock_');
    if (!$heads) {
        $heads = array();
    }
    $heads[] = $js;
    $smarty->assign('_headblock_', $heads);

    $rtn = '
        <div id="file_upload'.$id.'">
            <div id="filesUploaded_preview'.$id.'">
            <input type="hidden" name="'.$hidden_nm.'" value="'.str_replace("s_","",$default).'">
            <a id="filesUploaded_file'.$id.'" href="'.$default.'" rel="'.$default_L.'" target="_blank">Loading..</a></div><br>
            <div id="fileQueue'.$id.'"></div>
            <input type="file" name="Filedata" id="uploadify'.$id.'" />
            <div id="filesUploaded_msg'.$id.'"></div>
        </div>'
    ;

    if ($default){
        $rtn.= '<input name="del_file_'.$id.'" type="checkbox" value="1" onChange="j$(\'#file_upload'.$id.'\').toggle();" />'.RCMS_Translate::getInstance($requestContext['locale'])->translate("/label/delete_btn");
    }
    return $rtn;
}
