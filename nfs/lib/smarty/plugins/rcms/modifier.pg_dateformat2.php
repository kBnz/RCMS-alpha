<?php
/**
 * Smarty plugin
 * @category CMS
 * @package  Smarty
 * @author   金子 和正 <kazumasa@diverta.co.jp>
 */
function smarty_modifier_pg_dateformat2($string, $format=null) {
    global $requestContext;

    if(!$format){$format = "%Y/%m/%d(%%w%%) %H:%M";}

    //UNIX時間を取得
    if (!preg_match('|^([0-9]{4})([-/])([0-9]{1,2})\2([0-9]{1,2})(?: ([0-9]{2}):([0-9]{2}):([0-9]{2})(\.[0-9]*)?)?$|', $string, $rs)) {
        return "";
    }
    $year   = $rs[1];
    $month  = $rs[3];
    $day    = $rs[4];
    $hour   = isset($rs[5]) ? $rs[5] : 0;
    $minute = isset($rs[6]) ? $rs[6] : 0;
    $second = isset($rs[7]) ? $rs[7] : 0;
    $utime = mktime($hour, $minute, $second, $month, $day, $year);

    $passed_time = time() - $utime;

    if($passed_time < 24 * 60 * 60){
        if($passed_time < 1 * 60){
            return "<span style=\"color:#5FAF23;font-weight:bold;\">".date("H:i:s",$utime)."(".RCMS_Translate::getInstance($requestContext['locale'])->translate("/label/update_seconds_ago", ceil($passed_time)).")</span>";
        }elseif($passed_time < 1 * 60 * 60){
            return "<span style=\"color:#5FAF23;font-weight:bold;\">".date("H:i:s",$utime)."(".RCMS_Translate::getInstance($requestContext['locale'])->translate("/label/update_minutes_ago", ceil($passed_time/60)).")</span>";
        }else{
            return "<span style=\"color:#5FAF23;font-weight:bold;\">".date("H:i:s",$utime)."(".RCMS_Translate::getInstance($requestContext['locale'])->translate("/label/update_hours_ago", ceil($passed_time/60/60)).")</span>";
        }
    }else{
        return pg_dateformat($format, $string);
    }
}
