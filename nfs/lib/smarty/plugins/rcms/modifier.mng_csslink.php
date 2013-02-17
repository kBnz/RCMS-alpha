<?php
function smarty_modifier_mng_csslink($mt, $media = "screen,print") {
    $path = "/css/management/modules/" . $mt . ".css";
    if (file_exists(ROOT_DIR . "/html" . $path)) {
        return '<link href="' . $path . '" rel="stylesheet" type="text/css" media="' . $media . '">';
    }
    return "";
}
?>
