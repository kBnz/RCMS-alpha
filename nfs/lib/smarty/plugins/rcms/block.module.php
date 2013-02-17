<?php
function smarty_block_module($params, $content, &$smarty) {
    if(!isset($content)){return;} //空の時は返り値を見てないみたい

    global $NO_RCMS_ORG_DIV_FLG;

    $enable = true;

    // 肯定条件 trueだったら表示する
    if (array_key_exists('if', $params)) {
        $v = $params['if'];
        if (is_array($v)) {
            $enable = count($v) > 0;
        }
        else {
            $enable = (bool)$v;
        }
    }

    // 否定条件 trueだったら表示しない
    if (array_key_exists('unless', $params)) {
        $v = $params['unless'];
        if (is_array($v)) {
            $enable = count($v) == 0;
        }
        else {
            $enable = !(bool)$v;
        }
    }


    $result = '';
    if ($enable) {
        $name = $params['name'];

        if ($NO_RCMS_ORG_DIV_FLG){
            $result = <<< _STR_HTML_
<div class="$name module">
$content
</div>
_STR_HTML_;
        }else{
            $result = <<< _STR_HTML_
<div class="$name module"><div class="module_top"></div><div class="module_body"><div class="module_body_centertop"><div class="module_body_centerleft"><div class="module_body_centerright"><div class="module_body_centerbottom"><div class="module_body_lefttop"><div class="module_body_righttop"><div class="module_body_leftbottom"><div class="module_body_rightbottom">
$content
</div></div></div></div></div></div></div></div></div><div class="module_bottom"></div></div><!-- // end div $name module -->
_STR_HTML_;
        }
    }

    return $result;
}
?>
