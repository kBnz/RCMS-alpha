<?php
/**
 * 値がなければ表示しない
 */
function smarty_block_block_topics_ext($params, $content, &$smarty) {
    if(!isset($content)){return;} //空の時は返り値を見てないみたい

    $params['return_flg']=1;
    
    $rtn = smarty_function_assign_topics_ext($params, &$smarty);

    if($rtn != ""){
        return $content;
    }else{
        return "";
    }
}
?>