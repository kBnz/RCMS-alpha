<?php
// tdfk_cdを名称に変換
function smarty_modifier_rcms_tdfk($tdfk_cd) {
    global $arrTdfk;
    return $arrTdfk[$tdfk_cd];
}
?>
