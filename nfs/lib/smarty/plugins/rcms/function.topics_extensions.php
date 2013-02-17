<?php
/**
 * Smarty plugin
 * @category CMS
 * @package  Smarty
 * @author   ���� ���� <kanagawa@diverta.co.jp>
 */
function smarty_function_topics_extensions($params, &$smarty) {

    $col_nm = $params['ext_col_nm'] ? $params['ext_col_nm'] : 0;
    $disp_no = $params['disp_no'] ? $params['disp_no'] : 0;
    $info_type = $params['info_type'] ? $params['info_type'] : 'value';
    $info_type = $params['division_tag'] ? $params['division_tag'] : '<br>';

    // �g�����ڂɊւ���e��ݒ���擾
    $extensions = Topics::refactorExtConfig($params['ext_columns']);

    if ($extensions['values'][$col_nm][$disp_no]!=''){

        if ($extensions['type'][$col_nm]=='0'){
        // �e�L�X�g�{�b�N�X�̏ꍇ
            return nl2br($extensions['values'][$col_nm][$disp_no]);

        }elseif ($extensions['type'][$col_nm]=='1'){
        // �e�L�X�g�G���A�̏ꍇ
            return nl2br($extensions['values'][$col_nm][$disp_no]);

        }elseif ($extensions['type'][$col_nm]=='2'){
        // �I���`���̏ꍇ
            if ($info_type=='key'){
                return implode(',', $extensions['keys'][$col_nm][$disp_no]);
            }else{
                return implode(',', $extensions['values'][$col_nm][$disp_no]);
            }
        }elseif ($extensions['type'][$col_nm]=='3'){
        // ���l�̏ꍇ
            return nl2br($extensions['values'][$col_nm][$disp_no]);

        }elseif ($extensions['type'][$col_nm]=='4'){
        // �摜�̏ꍇ
            if ($info_type=='alt'){
                return $extensions['images'][$col_nm][$disp_no]['alt'];
            }else{
                if ($extensions['images'][$col_nm][$disp_no]['url']){
                    return $extensions['images'][$col_nm][$disp_no]['url'];
                }
            }

        }elseif ($extensions['type'][$col_nm]=='5'){
        // �����I���̏ꍇ
            if ($info_type=='key'){
                return implode(',', $extensions['keys'][$col_nm][$disp_no]);
            }else{
                return implode(',', $extensions['values'][$col_nm][$disp_no]);
            }
        }
    }else{
        if ($extensions['type'][$col_nm]=='4'){
        // �摜�̏ꍇ
            return $params['no_image'];
        }
    }
}
?>
