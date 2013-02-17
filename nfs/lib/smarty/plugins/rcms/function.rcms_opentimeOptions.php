<?php
function smarty_function_rcms_opentimeOptions($params, &$smarty) {
    require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');

    $name = null;
    $selected = null;
    
    if (isset($params['name'])) {
        $name = (string)$params['name'];
    }
    if (isset($params['selected'])) {
        $selected = (string)$params['selected'];
    }

    $options = array();
    $options[] = '<option value="">-----</option>';
    for ($hh = 0 ; $hh < 24 ; $hh++) {
        for ($mm = 0 ; $mm < 60 ; $mm += 30) {
            $disp_mm = $mm;
            $disp_hh = $hh;
            if($params["end_time"]){
                if($disp_mm == 0){
                    $disp_mm = 30;
                }elseif($disp_mm == 30){
                    $disp_hh += 1;$disp_mm = 0;
                }
            }
            $key = sprintf('%02d:%02d', $disp_hh, $disp_mm);
            $options[] = sprintf(
                '<option value="%s"%s>%s</option>',
                $key,
                ($selected == $key ? ' selected="selected"' : ''),
                $key
            );
        }
    }

    if (!is_null($name)) {
        return '<select name="' . smarty_function_escape_special_chars($name) . '" id="' . smarty_function_escape_special_chars(str_replace(array("[","]"),array("_",""),$name)) . '">' . join('', $options) . '</select>';
    }
    else {
        return join('', $options);
    }
}
