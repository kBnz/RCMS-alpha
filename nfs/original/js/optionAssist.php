<?php
if(substr($_SERVER["SERVER_ADDR"],0,11)=="192.168.25."){define("NO_ACCESSCOUNT","1");}

/*
  使用方法

  <<呼び出し側>>
    リクエストパラメータ
      target: 値を設定するフォーム名
      type:   選択リストを識別する値
    呼び出し例:
      window.open('/js/optionAssist.php?target=game_edit.game_type&type=gameamefoot_game_type','','width=250,height=400,scrollbars=1,resizable=yes');

  <<受け側>>
    require_onceで呼び出されるPHPは次の変数に値を設定すること
      $title      ウィンドウのタイトル
      $listtitle  リストのタイトル
      $selectList 選択リストの配列

*/

//基本ファイルのインクルード
require_once("default.php");

$selectList = array();
// guard for XSS
$target = "self.opener.document.".preg_replace('/[^.\w]/', '', $_REQUEST["target"]).".value" ;
$type = $_REQUEST["type"];

switch ($type) {
    case "gameamefoot_game_type":
        require_once(LIB_PATH."/modules/gameamefoot/admin/game_type_list.php") ;
        break;
    case "gameamefoot_game_type2":
        require_once(LIB_PATH."/modules/gameamefoot/admin/game_type2_list.php") ;
        break;
    case "gamebaseball_game_type":
        require_once(LIB_PATH."/modules/gamebaseball/admin/game_type_list.php") ;
        break;
    case "gamebaseball_game_type2":
        require_once(LIB_PATH."/modules/gamebaseball/admin/game_type2_list.php") ;
        break;
    case "gamejyudou_game_type":
        require_once(LIB_PATH."/modules/gamejyudou/admin/game_type_list.php") ;
        break;
    case "gamejyudou_game_type2":
        require_once(LIB_PATH."/modules/gamejyudou/admin/game_type2_list.php") ;
        break;
    case "gamerugby_game_type":
        require_once(LIB_PATH."/modules/gamerugby/admin/game_type_list.php") ;
        break;
    case "gamerugby_game_type2":
        require_once(LIB_PATH."/modules/gamerugby/admin/game_type2_list.php") ;
        break;
    case "leaguerugby_game_type":
        require_once(LIB_PATH."/modules/leaguerugby/admin/game_type_list.php") ;
        break;
    case "leaguerugby_game_type2":
        require_once(LIB_PATH."/modules/leaguerugby/admin/game_type2_list.php") ;
        break;
    case "gamesoccer_game_type":
        require_once(LIB_PATH."/modules/gamesoccer/admin/game_type_list.php") ;
        break;
    case "gamesoccer_game_type2":
        require_once(LIB_PATH."/modules/gamesoccer/admin/game_type2_list.php") ;
        break;
    case "gamebasket_game_type":
        require_once(LIB_PATH."/modules/gamebasket/admin/game_type_list.php") ;
        break;
    case "gamebasket_game_type2":
        require_once(LIB_PATH."/modules/gamebasket/admin/game_type2_list.php") ;
        break;
    case "gamefutsal_game_type":
        require_once(LIB_PATH."/modules/gamefutsal/admin/game_type_list.php") ;
        break;
    case "gamefutsal_game_type2":
        require_once(LIB_PATH."/modules/gamefutsal/admin/game_type2_list.php") ;
        break;
    case "gamemlacrosse_game_type":
        require_once(LIB_PATH."/modules/gamemlacrosse/admin/game_type_list.php") ;
        break;
    case "gamemlacrosse_game_type2":
        require_once(LIB_PATH."/modules/gamemlacrosse/admin/game_type2_list.php") ;
        break;
    case "gameflacrosse_game_type":
        require_once(LIB_PATH."/modules/gameflacrosse/admin/game_type_list.php") ;
        break;
    case "gameflacrosse_game_type2":
        require_once(LIB_PATH."/modules/gameflacrosse/admin/game_type2_list.php") ;
        break;
    case "gametennis_game_type":
        require_once(LIB_PATH."/modules/gametennis/admin/game_type_list.php") ;
        break;
    case "gametennis_game_type2":
        require_once(LIB_PATH."/modules/gametennis/admin/game_type2_list.php") ;
        break;
    case "gamevolley_type":
        require_once(LIB_PATH."/modules/gamevolley/admin/game_type_list.php") ;
        break;
    case "gamevolley_type2":
        require_once(LIB_PATH."/modules/gamevolley/admin/game_type2_list.php") ;
        break;
    case "gameamefoot_place":
        require_once(LIB_PATH."/modules/gameamefoot/admin/place_list.php") ;
        break;
    case "gamebaseball_place":
        require_once(LIB_PATH."/modules/gamebaseball/admin/place_list.php") ;
        break;
    case "gamerugby_place":
        require_once(LIB_PATH."/modules/gamerugby/admin/place_list.php") ;
        break;
    case "gamesoccer_place":
        require_once(LIB_PATH."/modules/gamesoccer/admin/place_list.php") ;
        break;
    case "gamebasket_place":
        require_once(LIB_PATH."/modules/gamebasket/admin/place_list.php") ;
        break;
    case "gamefutsal_place":
        require_once(LIB_PATH."/modules/gamefutsal/admin/place_list.php") ;
        break;
    case "gamejyudou_place":
        require_once(LIB_PATH."/modules/gamejyudou/admin/place_list.php") ;
        break;
    case "gamemlacrosse_place":
        require_once(LIB_PATH."/modules/gamemlacrosse/admin/place_list.php") ;
        break;
    case "gameflacrosse_place":
        require_once(LIB_PATH."/modules/gameflacrosse/admin/place_list.php") ;
        break;
    case "gametennis_place":
        require_once(LIB_PATH."/modules/gametennis/admin/place_list.php") ;
        break;
    case "gamevolley_place":
        require_once(LIB_PATH."/modules/gamevolley/admin/place_list.php") ;
        break;
    case "leaguerugby_place":
        require_once(LIB_PATH."/modules/leaguerugby/admin/place_list.php") ;
        break;
    case "position":
        require_once(LIB_PATH."/modules/member/admin/position_list.php") ;
        break;
    case "gametabletennis_game_type":
        require_once(LIB_PATH."/modules/gametabletennis/admin/game_type_list.php") ;
        break;
    case "gametabletennis_game_type2":
        require_once(LIB_PATH."/modules/gametabletennis/admin/game_type2_list.php") ;
        break;
    case "gametabletennis_place":
        require_once(LIB_PATH."/modules/gametabletennis/admin/place_list.php") ;
        break;

    case "gamesimple_game_type":
        require_once(LIB_PATH."/modules/gamesimple/admin/game_type_list.php") ;
        break;
    case "gamesimple_game_type2":
        require_once(LIB_PATH."/modules/gamesimple/admin/game_type2_list.php") ;
        break;
    case "gamesimple_place":
        require_once(LIB_PATH."/modules/gamesimple/admin/place_list.php") ;
        break;
    default:
        // TODO エラーのときの対処
        echo "<html>";
        echo "<head>";
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />";
        echo "</head>";
        echo "<body>";
        echo "ページがありません。<br>" ;
        echo '<a href="#" onclick="self.close()">閉じる</a>' ;
        echo "</body>";
        echo "</html>";
        exit ;
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo $title?></title>
<style type="text/css">
.main {
  margin-top: 0.5em ;
}
.listtitle {
  padding-left: 1em;
  font-weight: bold;
  background-color: #FFCC66;
  padding-top: 3px;
  padding-bottom: 3px;
}
.list {
  border: 1px solid #EC661B;
  font-size: 0.7em;
  background-color: #EEEEEE;
  padding-left:20px;
}
.list a {
  color: #333333 ;
  text-decoration: none;
  display: block;
}
.list a:hover {
  color: #FF0000;
  text-decoration: underline;
  background-color:#D5D5D5;
}
.list li{
    list-style-position: outside;
    margin: 0px;
    padding: 0px;
    border: solid #EEEEEE 1px;
}
.close {
  margin-top: 0.5em;
  margin-right: 1.5em;
  font-size: 0.7em;
  display: block;
  height: 20px;
  padding:5px;
}
</style>
<script language="JavaScript">
function setValue(v) {
  <?echo $target?> = v;
}
</script>
</head>
<body topmargin="0">
  <table class="main" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr><td class="listtitle"><?echo $listtitle?></td></tr>
    <tr>
      <td>
        <table class="list" cellpadding="0" cellspacing="0" border="0" width="100%">
          <tr><td>
<?
if(is_array($selectList)){
  foreach ($selectList as $key => $value) {
      if ($value != "") {
          $value = rtrim($value);
?>
          <li><a href="#" onClick="javascript:setValue('<?echo $value?>');self.close()"><? echo $value?></a></li>
<?
      }
  }
    echo "<br>※サイト基本設定＞サイト管理<br>で変更できます。";
}else{
    echo "登録されていません。<br>サイト基本設定＞サイト管理<br>から登録できます。";
}
?>
          </td></tr>
        </table>
      </td>
    </tr>
  </table>
  <span class="close"><input type="button" value="閉じる" onClick="self.close()"></span>
</body>
</html>
