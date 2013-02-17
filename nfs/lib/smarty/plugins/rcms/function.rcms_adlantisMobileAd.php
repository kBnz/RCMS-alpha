<?php
/**
 * Adlantisの携帯用広告出力
 * (例) {rcms_adlantisMobileAd zone_id="NTAyNg%3D%3D%0A" title_color="103f63" text_color="5c5c5c" align="left"}
 *  mobile_single - 1つ広告を出す場合(通常)
 *  mobile_double  - 2つ広告を出す場合
 */


function smarty_function_rcms_adlantisMobileAd($params,&$smarty) {

    if(!is_mobile() || !$params['zone_id']){return "";}
    return RCMS_Adlantis::getAd($params['zone_id'],$params['title_color'],$params['text_color'],$params['align']);

}
