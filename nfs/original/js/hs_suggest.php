<?php
if(substr($_SERVER["SERVER_ADDR"],0,11)=="192.168.25."){define("NO_ACCESSCOUNT","1");}

/*
 * 高校名の入力補助
 * 「メンバー情報」モジュールで使用します。
 */

/* ==仕様==
  ２つの動作が含まれています。
  1. 高校名から検索して候補一覧を表示すること
  2. 1の候補一覧でクリックされた高校ID、都道府県IDを
    「メンバー情報」ページの高校プルダウン、都道府県プルダウンに設定すること
*/

//基本ファイルのインクルード
require_once("default.php");

$s_name = $_REQUEST['s_name'];
$hs_id = $_REQUEST['hs_id'];    // 高校

$hs_list = array();
if ($hs_id != "") {
    // 同一都道府県の高校リストを取得する
    $strSQL =
        " select * from m_highschool where tdfk_cd in ( " .
        "   select tdfk_cd from m_highschool where hs_id = " . db_convert($hs_id) . ") " .
        " order by hs_id";
    $result = selectQuery($cn, $strSQL);
    while ($row = getRow($result)) {
        $hs_list[] = $row;
    }
}


// 検索の場合、高校の候補リストを取得する
$hs_list_sugg = array();
if ($s_name != "") {
    $strSQL =
        " select * from m_highschool " .
        " where hs_name like '%". db_convert($s_name) . "%' " .
        " or hs_sname like '%" . db_convert($s_name) . "%' order by tdfk_cd, hs_name";
    $result = selectQuery($cn, $strSQL);
    while ($row = getRow($result)) {
        $hs_list_sugg[] = $row;
    }
}

$first  = translate("/label/first"); //  最初へ
$last  = translate("/label/last"); //最後へ
$prev  = translate("/label/prev"); //前へ
$next  = translate("/label/next"); //次へ
$search  = translate("/label/search"); //検索
$high_school_name  = translate("/label/high_school_name"); //高校名
$place  = translate("/label/place"); //場所
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script type="text/javascript" src="/js/pager.js"></script>
<link href="/css/management/default.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
<!--
function selectHS(hs_id) {
    location.href="/js/hs_suggest.php?hs_id="+hs_id;
}
-->
</script>
<style>
<!--

table {
  font-size:12px;
}
#searchbox {
  text-align:left;
  margin-top:10px;
  margin-left:10px;
}

-->
</style>
</head>
<body>

<div class="list_main" id="searchbox">
<form action="/js/hs_suggest.php">
  <?= $high_school_name ?>：<input type="text" name="s_name" value="<?= $s_name ?>" />
  <input type="submit" value="<?= $search ?>" />
</form>

<?
  if (!$hs_list) {
?>
      <p id="nav_info"></p>
      <p>
        <input id="page_nav_first" type="button" onclick="item_list.first();" value="<?= $first ?>" class="custombtn1">
        <input id="page_nav_prev" type="button" onclick="item_list.prev();" value="<?= $prev ?>" class="custombtn1">
        <input id="page_nav_next" type="button" onclick="item_list.next();" value="<?= $next ?>" class="custombtn1">
        <input id="page_nav_last" type="button" onclick="item_list.last();" value="<?= $last ?>" class="custombtn1">
      </p>
      <table id="item_list" width="100%" border="0" cellspacing="2" cellpadding="0">
        <tr>
          <th><?= $high_school_name ?></th>
          <th><?= $place ?></th>
        </tr>

      <?
        foreach ($hs_list_sugg as $row) {
            echo '<tr style="display:none">';
            echo '<td><a href="javascript:void(0)" onclick="selectHS('. $row["hs_id"] .');return false;">'. $row["hs_name"] ."</a></td>";
            echo "<td>". $arrTdfk[$row["tdfk_cd"]] ."</td>";
            echo "</tr>";
        }
      ?>

      </table>
      </div>
      <script type="text/javascript">
      <!--
      var item_list = new Pager("item_list", <?= count($hs_list_sugg) ?>, 10, 2);
      item_list.speed = 0;
      item_list.onChangePage = function() {
          //ボタンのonoffとか操作する。
          var cu = this.getCurrentPageNo();
          var fi = document.getElementById("page_nav_first");
          var pr = document.getElementById("page_nav_prev");
          var ne = document.getElementById("page_nav_next");
          var la = document.getElementById("page_nav_last");
          var nv = document.getElementById("nav_info");
          fi.disabled = (cu == this.getFirstPageNo());
          pr.disabled = (cu == this.getFirstPageNo());
          ne.disabled = (cu == this.getLastPageNo());
          la.disabled = (cu == this.getLastPageNo());
          //javascriptで多言語対応するのが難しいので、最初と最後だけ表示
          if(this.getRowCount() >= 1){
            nv.innerHTML = "1 - " + this.getRowCount();
          }else{
            nv.innerHTML = "0 - " + this.getRowCount();
          }
      }
      item_list.initView(1);
      -->
      </script>
<?
  }
  else {
      $tdfk_cd = $hs_list[0]["tdfk_cd"];
?>
      <script type="text/javascript">
      <!--
      // 都道府県の設定
      var tdfk_obj = parent.document.getElementById('tdfk_hi');
      for (i in tdfk_obj.options) {
          if (tdfk_obj.options[i].value == '<?= $tdfk_cd ?>') {
              tdfk_obj.options[i].selected = true;
          }
      }
      // 高校の設定
      var high_school_obj = parent.document.getElementById('high_school');
      var len = high_school_obj.length ;
      for (var i = len - 1 ; i >= 0 ; i--) {
          high_school_obj[i] = null ;
      }

<?
      $selectedIndex = 0;
      for ($i = 0 ; $i < count($hs_list) ; $i++) {
          if ($hs_id == $hs_list[$i]["hs_id"]) {
              $selectedIndex = $i;
          }
          echo 'high_school_obj.options['.$i.'] = new Option("'.$hs_list[$i]["hs_name"].'", "'.$hs_list[$i]["hs_id"].'", false, false) ;' ;
      }
      echo 'high_school_obj.selectedIndex = ' . $selectedIndex . ";" ;
?>

      -->
      </script>
<?
  }
?>
</body>
</html>
