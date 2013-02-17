<?php
/**
 * Smarty plugin
 * @category CMS
 * @package  Smarty
 * @author   ‰Á“¡ Œ’‘¾ <kenta@diverta.co.jp>
 */
function smarty_modifier_lang_img_path($img_path) {
    global $requestContext;
    $url = $_SERVER['REQUEST_URI'];
    $thisLang = strtolower($requestContext['locale']->getSiteLanguage());

    $infoImg_path = pathinfo($img_path);

    if(substr($_SERVER["PHP_SELF"],0,12)=="/management/"){
        //ŠÇ—‰æ–Ê‚Å‚Ìˆ—‚È‚ç
        if ($thisLang == "ja") {
            return $img_path;
        }
    }else{
        // ŽåŒ¾Œê‚¾‚Á‚½‚ç‚»‚Ì‚Ü‚Ü
        if ($thisLang == UpdateHistory::getPrimaryLanguage()) {
            return $img_path;
        }
    }

    if(file_exists(ROOT_DIR."/html".$infoImg_path['dirname']."/".$thisLang."/".$infoImg_path['basename'])){
        //‚»‚ÌŒ¾Œê‚Ì‰æ‘œ‚ª‚ ‚éê‡
        return $infoImg_path['dirname']."/".$thisLang."/".$infoImg_path['basename'];
    }elseif(file_exists(ROOT_DIR."/html".$infoImg_path['dirname']."/en/".$infoImg_path['basename'])){
        //‰pŒê‚Ì‰æ‘œ‚ª‚ ‚ê‚Î‚»‚¿‚ç‚ðo‚·
        return $infoImg_path['dirname']."/en/".$infoImg_path['basename'];
    }else{
        //‘¼‚É‚È‚¯‚ê‚ÎA‚»‚Ì‚Ü‚Ü
        return $img_path;
    }
}
?>
