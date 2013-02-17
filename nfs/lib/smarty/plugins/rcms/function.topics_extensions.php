<?php
/**
 * Smarty plugin
 * @category CMS
 * @package  Smarty
 * @author   金川 正樹 <kanagawa@diverta.co.jp>
 */
function smarty_function_topics_extensions($params, &$smarty) {

    $col_nm = $params['ext_col_nm'] ? $params['ext_col_nm'] : 0;
    $disp_no = $params['disp_no'] ? $params['disp_no'] : 0;
    $info_type = $params['info_type'] ? $params['info_type'] : 'value';
    $info_type = $params['division_tag'] ? $params['division_tag'] : '<br>';

    // 拡張項目に関する各種設定を取得
    $extensions = Topics::refactorExtConfig($params['ext_columns']);

    if ($extensions['values'][$col_nm][$disp_no]!=''){

        if ($extensions['type'][$col_nm]=='0'){
        // テキストボックスの場合
            return nl2br($extensions['values'][$col_nm][$disp_no]);

        }elseif ($extensions['type'][$col_nm]=='1'){
        // テキストエリアの場合
            return nl2br($extensions['values'][$col_nm][$disp_no]);

        }elseif ($extensions['type'][$col_nm]=='2'){
        // 選択形式の場合
            if ($info_type=='key'){
                return implode(',', $extensions['keys'][$col_nm][$disp_no]);
            }else{
                return implode(',', $extensions['values'][$col_nm][$disp_no]);
            }
        }elseif ($extensions['type'][$col_nm]=='3'){
        // 数値の場合
            return nl2br($extensions['values'][$col_nm][$disp_no]);

        }elseif ($extensions['type'][$col_nm]=='4'){
        // 画像の場合
            if ($info_type=='alt'){
                return $extensions['images'][$col_nm][$disp_no]['alt'];
            }else{
                if ($extensions['images'][$col_nm][$disp_no]['url']){
                    return $extensions['images'][$col_nm][$disp_no]['url'];
                }
            }

        }elseif ($extensions['type'][$col_nm]=='5'){
        // 複数選択の場合
            if ($info_type=='key'){
                return implode(',', $extensions['keys'][$col_nm][$disp_no]);
            }else{
                return implode(',', $extensions['values'][$col_nm][$disp_no]);
            }
        }
    }else{
        if ($extensions['type'][$col_nm]=='4'){
        // 画像の場合
            return $params['no_image'];
        }
    }
}
?>
