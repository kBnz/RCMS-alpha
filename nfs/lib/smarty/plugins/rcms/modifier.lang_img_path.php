<?php
/**
 * Smarty plugin
 * @category CMS
 * @package  Smarty
 * @author   ���� ���� <kenta@diverta.co.jp>
 */
function smarty_modifier_lang_img_path($img_path) {
    global $requestContext;
    $url = $_SERVER['REQUEST_URI'];
    $thisLang = strtolower($requestContext['locale']->getSiteLanguage());

    $infoImg_path = pathinfo($img_path);

    if(substr($_SERVER["PHP_SELF"],0,12)=="/management/"){
        //�Ǘ���ʂł̏����Ȃ�
        if ($thisLang == "ja") {
            return $img_path;
        }
    }else{
        // �匾�ꂾ�����炻�̂܂�
        if ($thisLang == UpdateHistory::getPrimaryLanguage()) {
            return $img_path;
        }
    }

    if(file_exists(ROOT_DIR."/html".$infoImg_path['dirname']."/".$thisLang."/".$infoImg_path['basename'])){
        //���̌���̉摜������ꍇ
        return $infoImg_path['dirname']."/".$thisLang."/".$infoImg_path['basename'];
    }elseif(file_exists(ROOT_DIR."/html".$infoImg_path['dirname']."/en/".$infoImg_path['basename'])){
        //�p��̉摜������΂�������o��
        return $infoImg_path['dirname']."/en/".$infoImg_path['basename'];
    }else{
        //���ɂȂ���΁A���̂܂�
        return $img_path;
    }
}
?>
