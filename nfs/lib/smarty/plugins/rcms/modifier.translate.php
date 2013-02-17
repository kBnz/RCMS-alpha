<?php
function smarty_modifier_translate($key) {
    global $requestContext;
    $args = array_slice(func_get_args(), 1);
    return RCMS_Translate::getInstance($requestContext['locale'])->translate($key, $args);
}
?>
