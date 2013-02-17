<?php
// 西暦を和暦に変換
function smarty_modifier_to_wareki($year)
{
    global $arrYear_Wareki;
    $w = $arrYear_Wareki[$year];
    // 平成20年(2008) を 平成20年にする
    $w = preg_replace('/\([0-9]+\)$/', '', $w);
    return $w;
}

?>
