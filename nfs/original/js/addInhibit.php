<?php
if(substr($_SERVER["SERVER_ADDR"],0,11)=="192.168.25."){define("NO_ACCESSCOUNT","1");}

//基本ファイルのインクルード
require_once("default.php");

$url = $_REQUEST['url'];
$check_flg = true;

if($_REQUEST['subflg']) {
    // 入力チェック
    if($url == "") {
        $error_msg .= "URLが記入されていません<br>";
        $check_flg = false ;
    }
    else if(inTBInhibitList($url)) {
        $error_msg .= "このURLは既に禁止されています<br>";
        $check_flg = false ;
    }
    
    // データ新規追加
    if($check_flg) {
        $fr = fopen(ROOT_DIR."/html/files/tb/inhibit_list.txt", "a+");
        fwrite($fr, $url."\n");
        fclose($fr);
        $display = ture;
?>
<html>
<head>
<title>トラックバック禁止リストの追加</title>
</head>
<script language="JavaScript">
function onClickClose() {
    self.close();
}
</script>
<body>
データが登録されました。<br>
<input name="close" value="閉じる" onClick="onClickClose()" type="button" />
</body>
</html>
<?
    }
}

if(!$display) {
?>

<html>
<head>
<title>トラックバック禁止リスト追加</title>
</head>
<body>
<form  action="" method="post" name="add_inhibit_list" id="add_inhibit_list">
<font color="red"><?= $error_msg ?></font>
禁止したいURLを入力してください。<br>
禁止したURLからトラックバックが来ると申請中となりすぐには公開されません<br><br>
<span style="font-size:80%">例)｢http://www.ooooo.co.jp/xxx/｣を禁止すると<br>
禁止されるURL:<br>
｢http://www.ooooo.co.jp/xxx/｣<br>
｢http://www.ooooo.co.jp/xxx/1234.html｣<br>
禁止されないURL:<br>
｢http://www.ooooo.co.jp/｣<br>
｢http://www.ooooo.co.jp/ooo/1234.html｣<br>
</span><br>
<table>
  <tr>
    <td colspan="2"><input name="url" value="<?= $url ?>" type="text" size="100"/></td>
  </tr>
  <tr>
    <td><input name="subflg" value="追加" type="submit" /></td>
    <td><input name="close" value="閉じる" onClick="self.close()" type="button" /></td>
  </tr>
</table>

</form>
</body>
</html>
<?
}
?>