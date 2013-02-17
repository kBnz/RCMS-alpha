<?
if(substr($_SERVER["SERVER_ADDR"],0,11)=="192.168.25."){define("NO_ACCESSCOUNT","1");}

/*
 * type : スポーツのタイプ
 *
 */

//基本ファイルのインクルード
require_once("default.php");

$sports_type = $_REQUEST['type'];

$check_flg = true ;
$error_msg = "";
$display_flg = false;

switch($sports_type) {
    case "rugby":
        $teamTypeArr = $arrTeamRugbyType;
        $table_nm = "m_team_rugby";
        $title = "ラグビーチームインプット";
        break;
    case "amefoot":
        $teamTypeArr = $arrTeamAmefootType;
        $table_nm = "m_team_amefoot";
        $title = "アメフトチームインプット";
        break;
    case "soccer":
        $teamTypeArr = $arrTeamSoccerType;
        $table_nm = "m_team_soccer";
        $title = "サッカーチームインプット";
        break;
    case "baseball":
        $teamTypeArr = $arrTeamBaseballType;
        $table_nm = "m_team_baseball";
        $title = "野球チームインプット";
        break;
    case "basket":
        $teamTypeArr = $arrTeamBasketType;
        $table_nm = "m_team_basket";
        $title = "バスケチームインプット";
        break;
  case "futsal":
        $teamTypeArr = $arrTeamFutsalType;
        $table_nm = "m_team_futsal";
        $title = "フットサルチームインプット";
        break;
  case "mlacrosse":
        $teamTypeArr = $arrTeammlacrosseType;
        $table_nm = "m_team_mlacrosse";
        $title = "男子ラクロスチームインプット";
        break;
  case "flacrosse":
        $teamTypeArr = $arrTeamflacrosseType;
        $table_nm = "m_team_flacrosse";
        $title = "女子ラクロスチームインプット";
        break;
  case "volley":
        $teamTypeArr = $arrTeamvolleyType;
        $table_nm = "m_team_volley";
        $title = "バレーボールチームインプット";
        break;
    default :
        echo "ページがありません。<br>" ;
        echo '<a href="#" onclick="self.close()">閉じる</a>' ;
        exit ;
}

if($_REQUEST['subflg']) {
    // 入力チェック
    if($_REQUEST['team_nm'] == "") {
        $error_msg .= "チーム名称が記入されていません";
        $check_flg = false ;
    }
    if (!isset($teamTypeArr[$_REQUEST["team_type"]])) {
        $error_msg .= "チームタイプが不正です。";
        $check_flg = false ;
    }
    
    $strSQL = "select * from ".$table_nm." where team_nm='".db_convert($_REQUEST['team_nm'])."' and dflg=0";
    $result = selectQuery($cn, $strSQL);
    if($result->numRows() > 0) {
        $error_msg .= "このチームは既に登録されています";
        $check_flg = false ;
    }
    
    // データ新規追加
    if($check_flg) {
        $arrValue = array() ;
        $arrValue['team_nm'] = "'".db_convert($_REQUEST['team_nm'])."'";
        $arrValue['team_short_nm'] = "'".db_convert($_REQUEST['team_nm'])."'";
        $arrValue['team_type'] = "'".db_convert($_REQUEST['team_type'])."'";
        $arrValue['dflg'] = 0;
        $team_rygby_id = insertQuery($cn, $table_nm,$arrValue);
        $display_flg = true;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="robots" content="noindex,nofollow">
<title><?= $title ?></title>
</head>
<script language="JavaScript">
function onClickClose() {
    window.opener.onChangeTeamType(<?= $_REQUEST['team_type'] ?>,'<?= $sports_type ?>');
    var select = self.opener.document.getElementById('team_type');
    select.value = <?= $_REQUEST['team_type'] ?>;
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

if(!$display_flg) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta name="robots" content="noindex,nofollow">
<title><?= $title ?></title>
</head>
<body>
<form  action="" method="post" name="team_rugby_input" id="team_rugby_input">
<input value="<?= $sports_type ?>" type=hidden />
<font color="red"><?= $error_msg ?><font>
<table class="main" bgcolor="#EEEEEE" cellspacing="5" width="100%" style="border: 1px solid #EC661B;">
  <tr>
    <th bgcolor="#FFCC66">チーム名称</th>
    <td><input size="40" id="team_nm" name="team_nm" value="<?= $_REQUEST['team_nm'] ?>" type="text" /></td>
  </tr>
  <tr>
    <th bgcolor="#FFCC66">タイプ</th>
    <td><select id="team_type" name="team_type">
<?
      foreach($teamTypeArr as $key => $value) {
          if($_REQUEST['team_type'] == $key) {
              $str_selected = "selected";
          }
          echo '<option value="'.$key.'" '.$str_selected.'>'.$value.'</option>';
          $str_selected = "";
      }
?>
        </select>
    </td>
  </tr>
</table>

<div style="margin-top:10px;">
<input name="close" value="閉じる" onClick="self.close()" type="button" style="float:right;" />
<input name="subflg" value="追加" type="submit" style="float:right; margin-right:10px;" />
</div>

</form>
</body>
</html>
<?
}
?>