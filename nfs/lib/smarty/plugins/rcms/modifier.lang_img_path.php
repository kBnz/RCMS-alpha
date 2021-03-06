<?php
/**
 * Smarty plugin
 * @category CMS
 * @package  Smarty
 * @author   加藤 健太 <kenta@diverta.co.jp>
 */
function smarty_modifier_lang_img_path($img_path) {
    global $requestContext;
    $url = $_SERVER['REQUEST_URI'];
    $thisLang = strtolower($requestContext['locale']->getSiteLanguage());

    $infoImg_path = pathinfo($img_path);

    if(substr($_SERVER["PHP_SELF"],0,12)=="/management/"){
        //管理画面での処理なら
        if ($thisLang == "ja") {
            return $img_path;
        }
    }else{
        // 主言語だったらそのまま
        if ($thisLang == UpdateHistory::getPrimaryLanguage()) {
            return $img_path;
        }
    }

    if(file_exists(ROOT_DIR."/html".$infoImg_path['dirname']."/".$thisLang."/".$infoImg_path['basename'])){
        //その言語の画像がある場合
        return $infoImg_path['dirname']."/".$thisLang."/".$infoImg_path['basename'];
    }elseif(file_exists(ROOT_DIR."/html".$infoImg_path['dirname']."/en/".$infoImg_path['basename'])){
        //英語の画像があればそちらを出す
        return $infoImg_path['dirname']."/en/".$infoImg_path['basename'];
    }else{
        //他になければ、そのまま
        return $img_path;
    }
}
?>
