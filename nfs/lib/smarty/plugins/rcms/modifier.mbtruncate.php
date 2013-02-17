<?php
function smarty_modifier_mbtruncate($string, $length = 80, $etc = '...') {
if ($length == 0) {return '';}

//空白もカウントしてしまうし、表示に関係ないので、消去してしまう（全角は残す）
$string = str_replace("\n","",$string);
$string = str_replace("\r","",$string);
$string = str_replace("&nbsp;"," ",$string);
$string = trim($string);

//半角スペースは表示に残す 全体の半角スペースはカウントしない
$arrString = explode(" ",$string);

$total_len = 0;
$output_str = "";
$etc_len = mb_strlen($etc);

foreach($arrString as $key => $str){

    $total_len += mb_strlen($str);

    if($total_len > $length){
        //はみ出した分を除く
        $output_str .= $str;

        //どれだけ詰めるか計算
        $pg_cut_len = 0; //プログラム的に詰める長さ
        $cut_len = 0; //見た目の詰めた長さ（スペース以外）
        for($i=1;$i<$total_len;$i++){
            if(substr($output_str,-1*$i,1)!=" "){
                $cut_len++;
            }
            $pg_cut_len--;
            if($cut_len >= $etc_len + $total_len - $length){break;} //etcの長さとオーバーした長さを超えたらbreak
        }

        $output_str = mb_substr($output_str,0,$pg_cut_len).$etc;
        break;
    }elseif($total_len == $length){
        if(count($arrString) == $key){
            //ちょうどぴったり
            $output_str .= $str;
        }else{
            //はみ出した分を除く
            $output_str .= $str;

            //どれだけ詰めるか計算
            $pg_cut_len = 0; //プログラム的に詰める長さ
            $cut_len = 0; //見た目の詰めた長さ（スペース以外）
            for($i=1;$i<$total_len;$i++){
                if(substr($output_str,-1*$i,1)!=" "){
                    $cut_len++;
                }
                $pg_cut_len--;
                if($cut_len >= $etc_len){break;} //etcの長さを超えたらbreak
            }

            $output_str = mb_substr($output_str,0,$pg_cut_len).$etc;
        }
        break;
    }else{
        $output_str .= $str." ";
    }
}
return  trim($output_str);
}
?>