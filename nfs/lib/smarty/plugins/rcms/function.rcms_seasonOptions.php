<?php
function smarty_function_rcms_seasonOptions($params, &$smarty) {
    global $arrSeason;

    require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');

    $name = null;
    $selected = null;
    $firstOption = array();

    if (isset($params['name'])) {
        $name = (string)$params['name'];
    }
    if (isset($params['selected'])) {
        $selected = (string)$params['selected'];
    }
    if (isset($params['firstOption'])) {
        if ($params['firstOption'] == 'true') {
            $strLabel = translate('/label/the_year');
            $firstOption[''] = '--'.$strLabel.'--';
        }
        elseif (is_array($params['firstOption'])) {
            $firstOption = $params['firstOption'];
        }
    }

    $options = array();
    foreach (($firstOption + $arrSeason) as $key => $val) {
        $options[] = sprintf(
            '<option value="%s"%s>%s</option>',
            smarty_function_escape_special_chars($key),
            ($selected == $key ? ' selected="selected"' : ''),
            smarty_function_escape_special_chars($val)
        );
    }

    if (!is_null($name)) {
        return '<select class="season" name="' . smarty_function_escape_special_chars($name) . '">' . join('', $options) . '</select>';
    }
    else {
        return join('', $options);
    }
}
