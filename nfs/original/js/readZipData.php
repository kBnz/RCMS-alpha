<?php
if(substr($_SERVER["SERVER_ADDR"],0,11)=="192.168.25."){define("NO_ACCESSCOUNT","1");}

//基本ファイルのインクルード
require_once("default.php");
if (isset($_REQUEST["zipcode"]) && !preg_match("/^[0-9\-]+$/i", $_REQUEST["zipcode"])) {
    unset($_REQUEST["zipcode"]);
}

    $zipcode = str_pad(trim($_REQUEST['zipcode']), 7, "0", STR_PAD_RIGHT) ;     // 検索するテーブルIDを取得

    $cn = dbConnect();
    $colnmAr = array('tdfk_cd', 'city_nm', 'town_nm') ;
    $strSQL = "select ".$colnmAr['0'].", ".$colnmAr['1'].", ".$colnmAr['2']." "."from m_ken_all where zip_cd = '".$zipcode."'" ;

//echo $strSQL ;
    if ($strSQL != "") {
        $result = selectQuery($cn, $strSQL);
        $row = $result->fetchRow(DB_FETCHMODE_ASSOC) ;
        if ($row) {
            print "[";
            print "\"".mb_convert_encoding($row[$colnmAr['0']],"UTF-8","EUC-JP")."\"," ;
            print "\"".mb_convert_encoding($row[$colnmAr['1']],"UTF-8","EUC-JP")."\"," ;
            print "\"".mb_convert_encoding($row[$colnmAr['2']],"UTF-8","EUC-JP")."\"" ;
            print "]";
        }
    }
?>
