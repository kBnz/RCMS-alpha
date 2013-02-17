<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty sort_link modifier plugin
 *
 * Type:     modifier<br>
 * Name:     sort_link<br>
 * Purpose:  <th>タグ内にあるカラム名等を利用してソートするためのリンクを作る
 *
 * @author   岸本
 * @param string
 * @param string
 * @param string
 * @param string
 * @param integer
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_sort_link($column_name, $column, $link, $sort, $desc, $up_arrow = '<img src="/images/management/icon_up.gif" />', $down_arrow = '<img src="/images/management/icon_down.gif" />')
{
    if ($sort == $column){
        if ($desc == 1){
            $string = $up_arrow;
        } else {
            $string = $down_arrow;
        }
    }
    $link = preg_replace("/pageID=[0-9]+/i","",$link);
    $string .= '<a href="'.$link.'&sort='.$column;
    if ($column == $sort && $desc != 1 or $column != $sort) {// デフォルトでdesc==1
        $string .= '&desc=1';
    }
    $string .= '">'.$column_name.'</a>';
    
    return $string;
}

?>
