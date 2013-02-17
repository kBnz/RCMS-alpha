<?php

function smarty_block_pankuzu($params, $content, &$smarty) {

    if(is_admin_v2()){

        // V2の管理画面の時
        if(!isset($content)){return;} //空の時は返り値を見てないみたい
    
        $lines = preg_split('/\r?\n/', trim($content));
        
        $html    = "<div id=\"content_head\">";
        $node_no = 0;

        foreach($lines as $line) {
            if ($line == '') {
                continue;
            }
            // $lineにaタグが含まれていればaタグの中にdivを入れる
            if (preg_match("/<a.*?>/", $line) && preg_match("/(<\/a>)/", $line)) {
                $line = preg_replace("/(<a.*?>)(.+)(<\/a>)/", "$1<div class=\"navigation_node_{$node_no}\">$2</div>$3", $line);
                $html .= $line;
            } else {
            // aタグが無ければそのままdivで囲う
                $html .= "<div class=\"navigation_node_{$node_no}\">";
                $html .= $line;
                $html .= "</div>";
            }
            $node_no++;
        }
        $html .= "</div>";

        return $html;

    }else{
        
        // それ以外の時
        if(!isset($content)){return;} //空の時は返り値を見てないみたい
    
        $lines = preg_split('/\r?\n/', trim($content));
        $len = count($lines);
        $buf = array();
        for ($i = 0 ; $i < $len; $i++) {
            if ($lines[$i] == '') {
                continue;
            }
            $buf[] = '<li>' . trim($lines[$i]) . '</li>';
        }
    
        if (empty($buf)) {
            return;
        }
    
        $html = '<ul class="pankuzu">' . join('<li class="separate">&gt;&gt;</li>', $buf) . '</ul>';
        return $html;

    }


}
