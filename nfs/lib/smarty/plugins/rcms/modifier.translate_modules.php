<?php
function smarty_modifier_translate_modules($key) {
    global $requestContext;
    $args = array_slice(func_get_args(), 1);
    $key = '/modules/' . $key;
    return RCMS_Translate::getInstance($requestContext['locale'])->translate($key, $args);
}
?>
