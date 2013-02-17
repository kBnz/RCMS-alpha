<?php
function smarty_modifier_wysiwyg($val, $name, $width ,$height,$opts = array(),$delay=false) {
    return getWysiwyg($name, $val, $width ,$height,$opts,$delay);
}
?>
