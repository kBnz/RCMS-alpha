<?php
/**
  拡張項目の入力値の有無判定
*/
function smarty_modifier_isempty_extension($a) {
    return (bool)TopicsExtension::isAllEmpty($a);
}
