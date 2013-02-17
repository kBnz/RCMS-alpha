<?php
// tdfk_cdを名称に変換
function smarty_modifier_rcms_tdfkshort($tdfk_cd) {
    global $arrTdfkShort;
    return $arrTdfkShort[$tdfk_cd];
}
?>
