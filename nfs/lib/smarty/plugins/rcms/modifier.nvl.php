<?php
function smarty_modifier_nvl($val, $param)
{
    if (is_null($val)) {
        return $param;
    }
    else {
        return $val;
    }
}

?>
