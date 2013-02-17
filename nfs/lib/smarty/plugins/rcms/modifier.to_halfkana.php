<?php
function smarty_modifier_to_halfkana($string, $char_set = 'UTF-8')
{
  return mb_convert_kana($string, "ka", $char_set);
}
?>
