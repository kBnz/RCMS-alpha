<?php
if(substr($_SERVER["SERVER_ADDR"],0,11)=="192.168.25."){define("NO_ACCESSCOUNT","1");}

//基本ファイルのインクルード
require_once("default.php");

$url = $_REQUEST['url'];
$arrLine = array();

$fr = fopen(ROOT_DIR."/html/files/tb/inhibit_list.txt", "r+");
while($line = fgets($fr)) {
    $arrLine[] = $line;
}
fclose($fr);

if($_REQUEST['subflg'] && $url) {
    foreach($arrLine as $key => $value) {
        $value = preg_replace("/\n/", "", $value);
        $value = preg_replace("/\?/", "\?", $value);
        $value = preg_replace("/\//", '\/', $value);
        if(preg_match("/".$value."/", $url)) {
            $arrLine[$key] = null;
            $delete = ture;
            break;
        }
    }
    if($delete) {
        $fr = fopen(ROOT_DIR."/html/files/tb/inhibit_list.txt", "w");
        foreach($arrLine as $value) {
            fwrite($fr, $value);
        }
        fclose($fr);
        $msg .= '削除しました';
    }
    else {
        $msg .= '削除に失敗しました';
    }
}

?>

<html>
<head>
<title>トラックバック禁止リスト削除</title>
</head>
<body>
<form  action="" method="post" name="show_inhibit_list" id="show_inhibit_list">
<font color="red"><?= $msg ?></font>
禁止リストから削除したいURLを選択してください。<br>
<table>
  <tr>
    <td colspan="2"><select name="url" size="10">
<?
    foreach($arrLine as $value) {
        echo "<option>".$value."</option>";
    }
?>
    </select></td>
  </tr>
  <tr>
    <td><input name="subflg" value="削除" type="submit" /></td>
    <td><input name="close" value="閉じる" onClick="self.close()" type="button" /></td>
  </tr>
</table>

</form>
</body>
</html>
