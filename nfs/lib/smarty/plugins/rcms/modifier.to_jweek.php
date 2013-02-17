<?php
// 曜日を日本語に変換
function smarty_modifier_to_jweek($a)
{
    global $arrJweek;
    return $arrJweek[$a];
}

?>
