<?php
/**
* Smarty plugin
*
* @category CMS
* @package  Smarty
* @author   horiguchi@diverta.co.jp
*
* 使用例
* {rcms_get_image_size path=$path var=hoge}
* {$hoge.width}     幅
* {$hoge.height}    高さ
* {$hoge.type}      ファイルの種類 (1:gif, 2:jpeg, 3:png, 4:swf, 5:psd, 6:bmp...)  @see http://php.net/manual/en/function.exif-imagetype.php 
* {$hoge.size_str}  width="幅" height="高さ"
* {$hoge.mime}      mimeタイプ
* {$hoge.bits}      各カラービット数
* {$hoge.channels}  カラーモード(3:RGB, 4:CMYK)
*/
function smarty_function_rcms_get_image_size($params, &$smarty) {

    $path = ROOT_DIR."/html".$params["path"];
    $var  = $params["var"] ? $params["var"] : "rcms_get_image_size";
    $result = false;

    if( is_public_file(ROOT_DIR."/html",$path) && is_file($path) ){
        $image_info = getimagesize($path);
        if($image_info[0] > 0 && $image_info[1] > 0){
            // 画像の情報が取得出来た場合
            $result = array(
                "width"    => $image_info[0],
                "height"   => $image_info[1],
                "type"     => $image_info[2],
                "size_str" => $image_info[3],
                "mime"     => $image_info["mime"],
                "bits"     => $image_info["bits"],
                "channels" => $image_info["channels"],
            );
        }else{
            // 画像の情報が取得できなかった場合（未対応ファイル等）
            $result = false;
        }
    }else{
        // ファイルにアクセス出来ない場合
        $result = false;
    }
    
    $smarty->assign($var, $result);
}