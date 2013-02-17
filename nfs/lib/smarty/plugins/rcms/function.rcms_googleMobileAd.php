<?php
/**
 * Googleの携帯用広告出力
 * (例) {rcms_googleMobileAd format="mobile_double"}
 *  mobile_single - 1つ広告を出す場合(通常)
 *  mobile_double  - 2つ広告を出す場合
 */


function smarty_function_rcms_googleMobileAd($params, &$smarty) {

    if(!is_mobile() || !$GLOBALS['google_client']){return "";}
    return RCMS_Google::getAd($params['slotname']);

}
