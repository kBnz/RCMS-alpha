<?php
/**
 * 入力文字種の属性出力
 * (例) {istyle type="en}
 *  h  - ひらがな
 *  hk - カタカナ
 *  en - 英字 (docomoでは小文字に設定できない)
 *  n  - 数字
 */
function smarty_function_istyle($params, &$smarty) {
    $results = array(
        'DoCoMo' => array(
            'h'  => 'style="-wap-input-format:&quot;*&lt;ja:h&gt;&quot;"',
            'hk' => 'style="-wap-input-format:&quot;*&lt;ja:hk&gt;&quot;"',
            'en' => 'style="-wap-input-format:&quot;*&lt;ja:en&gt;&quot;"',
            'n'  => 'style="-wap-input-format:&quot;*&lt;ja:n&gt;&quot;"',
        ),
        'AU' => array(
            'h'  => 'format="*M"',
            'hk' => 'format="*M"',
            'en' => 'format="*m"',
            'n'  => 'istyle="4"',
//            'n'  => 'format="*N"', // この指定だと文字種変更できなくなってしまう A5518SA
        ),
        'SoftBank' => array(
            'h'  => 'mode="hiragana"',
            'hk' => 'mode="katakana"',
            'en' => 'mode="alphabet"',
            'n'  => 'mode="numeric"',
        )
    );

    $ua = is_mobile_type();

    if (isset($results[$ua]) && isset($results[$ua][$params['type']])) {
        return $results[$ua][$params['type']];
    }
    return "";
}
