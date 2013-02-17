<?php
if(substr($_SERVER["SERVER_ADDR"],0,11)=="192.168.25."){define("NO_ACCESSCOUNT","1");}

//基本ファイルのインクルード
require_once("default.php");
/*
 * elementの要素を変更します
 * $change_element : 要素を変更するelement
 * $element_type : 要素を変更するelementのtype
 * $item_type : 取得するデータの区別
 * $rowArr : データ格納配列
 * $changeKey : select要素を変えた後のkey
 * $changeValue : select要素を変えた後のvalue
 *
 */
// 関連情報用
// このファイルを呼び出した時のみ設定
$contName["high_school"] = "学校(高校)" ;
$conf_table_nm["high_school"] = "m_highschool";
$conf_table_idnm["high_school"] = "hs_id";
$conf_table_title["high_school"] = array("hs_name");
$conf_table_title_str["high_school"] = "%%0%%";
$conf_default_order["high_school"] = "tdfk_cd";
$search_colon["high_school"][0] = "tdfk_cd";

$contName["university"] = "学校(大学)" ;
$conf_table_nm["university"] = "m_university";
$conf_table_idnm["university"] = "univ_id";
$conf_table_title["university"] = array("univ_name");
$conf_table_title_str["university"] = "%%0%%";
$conf_default_order["university"] = "univ_kana";
$search_colon["university"][0] = "univ_kana";

//$cn = dbConnect();

$arrCheck = array("searchID","sports_type","tableID","apiID","order_no","start_number","end_number","ce");
foreach($arrCheck as $check){
    if (isset($_REQUEST[$check]) && !preg_match("/^[a-z0-9_\-:+]+$/i", $_REQUEST[$check])) {
        unset($_REQUEST[$check]);
    }elseif($_REQUEST[$check] == "undefined"){
        unset($_REQUEST[$check]);
    }
}

$item_type = $_REQUEST['item_type'];
switch($item_type) {
    case "relation": // 関連情報取得
        $tableID = trim($_REQUEST['tableID']);
        $search = array($_REQUEST['search1'], $_REQUEST['search2'], $_REQUEST['search3']);
        $change_element = "itemList";
        $element_type = "select";
        $changeKey0 = "table_prefix";
        $changeKey = "table_idval";
        $changeValue = "table_titleval";
        $rowArr = RCMSRelation::select2($cn, $tableID, $season, $search);
        break;
    case "relation_recent" : // 関連情報(最近更新されたコンテンツ)
        $tableID = "recent";
        $search = array($_REQUEST['search1'], $_REQUEST['search2'], $_REQUEST['search3']);
        $change_element = "itemList";
        $element_type = "select";
        $changeKey0 = "table_prefix";
        $changeKey = "table_idval";
        $changeValue = "table_titleval";
        $rowArr = RCMSRelation::selectRecent($cn, $search);
        break;
    case 'api': //関連情報(外部APIにアクセス)
        $tableID = trim($_REQUEST['apiID']);
        $search = array($_REQUEST['search1'], $_REQUEST['search2'], $_REQUEST['search3']);
        $change_element = "itemList";
        $element_type = "select";
        $changeKey0 = "table_prefix";
        $changeKey = "table_idval";
        $changeValue = "table_titleval";
        $rowArr = Api_Relation::factory($tableID)->getRelatableItems($search);
        break;
    case "hs_member": // 学校情報取得 高校 @member_edit
        $searchID = $_REQUEST['searchID']; // 都道府県コードの取得
        $searchFor = "tdfk";
        $change_element = "high_school";
        $element_type = "select";
        $changeKey = "hs_id";
        $changeValue = "hs_name";
        $rowArr = selectMaster($cn, $searchFor, $searchID);
        break;
    case "univ_member": // 学校情報取得 大学 @member_edit
        $searchID = $_REQUEST['searchID']; // 頭文字の取得
        $searchFor = "inicial";
        $change_element = "university";
        $element_type = "select";
        $changeKey = "univ_id";
        $changeValue = "univ_name";
        $rowArr = selectMaster($cn, $searchFor, $searchID);
        break;
    case "hs" : // 学校情報取得 高校 @team_master
        $searchID = $_REQUEST['searchID'];
        $searchFor = "tdfk";
        $change_element = "relation_id";
        $element_type = "select";
        $changeKey = "hs_id";
        $changeValue = "hs_name";
        $rowArr = selectMaster($cn, $searchFor, $searchID);
        break;
    case "univ" : // 学校情報取得 大学 @team_master
        $searchID = $_REQUEST['searchID'];
        $searchFor = "inicial";
        $change_element = "relation_id";
        $element_type = "select";
        $changeKey = "univ_id";
        $changeValue = "univ_name";
        $rowArr = selectMaster($cn, $searchFor, $searchID);
        break;
    case "gameOpponent": // 試合対戦相手情報
        $searchID = $_REQUEST['searchID'];
        $sports_type = $_REQUEST['sports_type'];
        $change_element = "opponent";
        $element_type = "team";
        $changeKey = "team_".$sports_type."_id";
        $changeValue = "team_nm";
        $rowArr = selectMaster3($cn, $searchID, $sports_type);
        break;
    case "gameTeam": // 試合対戦相手情報(自分。leaguerugbyなど)
        $searchID = $_REQUEST['searchID'];
        $sports_type = $_REQUEST['sports_type'];
        $change_element = "team";
        $element_type = "team";
        $changeKey = "team_".$sports_type."_id";
        $changeValue = "team_nm";
        $rowArr = selectMaster3($cn, $searchID, $sports_type);
        break;
    case "gameMember": // 試合メンバー情報
        $searchID = $_REQUEST['searchID'];
        $order_no = $_REQUEST['order_no'];
        $change_element = "member_name_sel[".$order_no."]";
        $season = $_REQUEST['season'] ;
        $element_type = "select";
        $changeKey = "member_id";
//         $changeValue1 = "name1";
//         $changeValue2 = "name2";
        $changeValue = "name";
        $rowArr = selectMember($cn, $searchID, $season);
        break;
    case "gameMemberOb": // 試合メンバー(OB)情報
        $searchID = $_REQUEST['searchID'];
        $order_no = $_REQUEST['order_no'];
        $change_element = "member_name_sel[".$order_no."]";
        $element_type = "select";
        $changeKey = "member_id";
//         $changeValue1 = "name1";
//         $changeValue2 = "name2";
        $changeValue = "name";
        $rowArr = selectMemberOb($cn, $searchID);
        break;
    case "photoMember": // 写真メンバー情報
        $searchID = $_REQUEST['searchID'];
        $change_element = "photo_member";
        $element_type = "select";
        $changeKey = "member_id";
//         $changeValue1 = "name1";
//         $changeValue2 = "name2";
        $changeValue = "name";
        $rowArr = selectMember($cn, $searchID, $season);
        break;
    case "photoGame": // 写真試合情報
        $searchID = db_convert($_REQUEST['searchID']);
        $sports_type = $_REQUEST['sports_type'];
        $change_element = $_REQUEST['ce'];
        $element_type = "select2";
        $changeKey = "game_id";
        //$changeValue1 = "ymd";
        $changeValue2 = "team_nm";
        $rowArr = selectMaster4($cn, $searchID, $sports_type);
        break;
    case "teamConv": // 大会チーム
        $searchID = $_REQUEST['searchID'];
        $sports_type = $_REQUEST['sports_type'];
        $order_no = $_REQUEST['order_no'];
        $change_element = "joined_teams[".$order_no."]";
        $element_type = "team";
        $changeKey = "team_nm";
        $changeValue = "team_nm";
        $rowArr = selectMaster3($cn, $searchID, $sports_type);
        break;
    case "teamType": // スポーツ別チームタイプ
        $sports_type = $_REQUEST['sports_type'];
        $start_number = $_REQUEST['start_number'];
        $end_number = $_REQUEST['end_number'];
        $change_element1 = "team_type";
        $change_element2 = "joined_teams";
        $element_type = "multi";
        $changeKey1 = "type_key";
        $changeValue1 = "type_value";
        $changeKey2 = "team_nm";
        $changeValue2 = "team_nm";
        foreach($arrTeamType[$sports_type] as $key => $value) {
            $row["type_key"] = $key;
            $row["type_value"] = $value;
            $rowArr1[] = $row;
        }
        $rowArr2 = selectMaster3($cn, 1, $sports_type);
        break;
    case "empty" : // 要素消去
        $rowArr = array();
        $change_element = 'team_search';
        $element_type = "select1";
        break;
    default :
        exit;
        break;
}

// javascript内でメソッドを呼び出す時
if($_REQUEST['parflg']) {
    $parent_str = "parent.";
}

// 特殊セレクト
// マスタ用
function selectMaster($db, $searchFor ,$searchValue) {
    switch($searchFor) {
        case tdfk:
            $table_nm = "m_highschool";
            $whereStr = "tdfk_cd='".db_convert($searchValue)."'";
            $orderStr = "hs_sname,hs_name";
            break;
        case inicial:
            $table_nm = "m_university";
            $whereStr = "univ_kana='".mb_convert_encoding(db_convert($searchValue),'ASCII','auto')."'";
            $orderStr = "univ_name";
            break;
    }

    $strSQL = "select * from ".$table_nm." where ".$whereStr." order by ".$orderStr;
    $result = selectQuery($db, $strSQL);
    $cnt = 0;
    $all = array() ;
    while($row = getRow($result)){
        $all[$cnt] = $row ;
        $cnt++ ;
    }
    return $all;

}   //selectMaster

function selectMaster2($db, $searchFor ,$schoolID) {
// IDから検索
    if((int)$searchID == ""){exit;}

    switch($searchFor) {
        case hs:
            $table_nm = "m_highschool";
            $whereStr = "hs_id='".(int)$schoolID."'";
            break;
        case univ:
            $table_nm = "m_university";
            $whereStr = "univ_id='".(int)$schoolID."'";
            break;
    }

    $strSQL = "select * from ".$table_nm." where ".$whereStr." and dflg = 0";
    $result = selectQuery($db, $strSQL);
    $row = getRow($result);
    return $row;
}   //selectMaster2

function selectMaster3($db, $searchID, $sports_type) {
// タイプからチーム一覧を検索
    if($sports_type=="" || (int)$searchID == ""){exit;}

    $strSQL = "select * from m_team_".db_convert($sports_type)." where team_type=".(int)$searchID." order by team_".db_convert($sports_type)."_id desc";
    $result = selectQuery($db, $strSQL);
    $cnt = 0;
    while($row = getRow($result)){
        $all[$cnt] = $row ;
        $cnt++ ;
    }

    return $all;
}   //selectMaster3

function selectMaster4($db, $searchID, $sports_type) {
// 試合タイプから試合一覧を検索
    if($sports_type=="" || $searchID == ""){exit;}

    global $arrGame_type_rugby, $arrGame_type_amefoot, $arrGame_type_baseball, $arrGame_type_soccer, $arrGame_type_volley,$season, $using_season;
    switch($sports_type) {
        case 'rugby' :
            $searchID = $arrGame_type_rugby[$searchID];
            break;
        case 'amefoot' :
            $searchID = $arrGame_type_amefoot[$searchID];
            break;
        case 'baseball' :
            $searchID = $arrGame_type_baseball[$searchID];
            break;
        case 'soccer' :
            $searchID = $arrGame_type_soccer[$searchID];
            break;
        case 'volley' :
            $searchID = $arrGame_type_volley[$searchID];
            break;
    }
    $whereStr = "";
    if ($using_season) {
        $whereStr = "and season = " . int($season);
    }
    $strSQL =
        "select * " .
        "from t_game_".db_convert($sports_type)."_header a " .
        "inner join m_team_".db_convert($sports_type)." b on a.opponent = b.team_".db_convert($sports_type)."_id " .
        "where a.dflg = 0 and a.open_flg = 1 $whereStr and game_type like '%".db_convert($searchID)."%' order by ymd desc";

    $result = selectQuery($db, $strSQL);
    $cnt = 0;
    $lang = RCMS_Locale::getLocale()->getSiteLanguage();

    $all = array();
    while($row = getRow($result)){
        // i18n
        $bag = UpdateHistory::createBag($db, "game".$sports_type, $row['game_id'], $lang);
        $row = $bag->getHistory("t_game_".$sports_type."_header", $row);
        $row["team_nm"] = mb_substr(RCMSRelation::getTitle("game".$sports_type, $row), 0, 30);
        $all[$cnt] = $row ;
        $cnt++ ;
    }

    return $all;
}   //selectMaster4

// メンバー用
function selectMember($db, $grade, $selected_season = "") {
    global $season, $using_season;
    $select_str    = translate("/label/select_btn");

    if($grade == ""){return array();}

    $whereStr = "";
    // 常にシーズンで絞込み
    $selected_season = $selected_season ? $selected_season : $season;
    $whereStr = "and b.season = " . (int)$selected_season;
    // 学年から検索
    //$strSQL = "select a.member_id,a.name1,a.name2,b.grade ".
    $strSQL = "select a.member_id,a.disp_name as name,b.grade ".
          "from v_member_header a ".
          "left join t_member_grade b ".
          "on a.member_id = b.member_id ".
          "where a.dflg = 0 and a.open_flg = 1 ".$whereStr." and b.grade = '".(int)$grade."' order by a.member_id desc" ;
    $result = selectQuery($db, $strSQL);
    $cnt = 1;
    $all = array() ;
    //$all[0] = array("member_id"=>"","name1"=>"↓選択","name2"=>"");
    $all[0] = array("member_id"=>"","name"=>"↓$select_str");
    while($row = getRow($result)){
        $all[$cnt] = $row ;
        $cnt++ ;
    }
    return $all;
}   //selectMember

// メンバー(OB)用
function selectMemberOb($db, $year) {
    $select_str    = translate("/label/select_btn");
    // 学年から検索
    //$strSQL = "select a.member_id,a.name1,a.name2,b.grade ".
    $strSQL = "select a.member_id,a.disp_name as name,b.grade ".
          "from v_member_header a ".
          "left join t_member_grade b ".
          "on a.member_id = b.member_id ".
          "where a.dflg = 0 and b.season = '".(int)$year."' and b.grade = '99' order by a.member_id desc" ;
    $result = selectQuery($db, $strSQL);
    $cnt = 1;
    $all = array() ;
    //$all[0] = array("member_id"=>"","name1"=>"↓選択","name2"=>"");
    $all[0] = array("member_id"=>"","name"=>"↓$select_str");
    while($row = getRow($result)){
        $all[$cnt] = $row ;
        $cnt++ ;
    }
    return $all;
}   //selectMemberOb

?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="JavaScript" type="text/javascript">
function setValue() {
<?
  // selectの要素に改行が含まれると困るので除去
  for ($i = 0 ; $i < count($rowArr) ; $i++) {
      $rowArr[$i][$changeValue] = preg_replace("/\r|\n/","", $rowArr[$i][$changeValue]);
  }
?>
    var element = <?= $parent_str ?>parent.document.getElementById('<?= $change_element ?>') ;
<?
if($element_type == "team") { // teamの場合 配列の要素が配列の場合
?>
  element.length = 0;
  var len = element.length ;
  for (i = len-1 ; i >= 0 ; i--) {
    element.options[i] = null ;
  }
<?
  for ($i = 0 ; $i < count($rowArr) ; $i++) {
    echo 'element.options['.$i.'] = new Option("'.str_replace('"', '\"', $rowArr[$i][$changeValue]).'", "'.$rowArr[$i][$changeKey].'", false, false) ;' . "\n" ;
  }

}elseif($element_type == "select") { // select elementの場合 配列の要素が配列の場合
?>
  var len = element.length ;
  for (i = len-1 ; i >= 0 ; i--) {
    element.options[i] = null ;
  }
  //element.length
<?
  for ($i = 0 ; $i < count($rowArr) ; $i++) {
    if($tableID != "") {
        echo 'element.options['.$i.'] = new Option("'.str_replace('"', '\"', $rowArr[$i][$changeValue]).'", "'.$rowArr[$i][$changeKey0].':'.$rowArr[$i][$changeKey].'", false, false) ;' . "\n" ;
    }
    else {
        echo 'element.options['.$i.'] = new Option("'.str_replace('"', '\"', $rowArr[$i][$changeValue]).'", "'.$rowArr[$i][$changeKey].'", false, false) ;' . "\n" ;
    }
  }

}elseif($element_type == "select1") { // select elementの場合 配列の要素が非配列の場合
?>
  var len = element.length ;
  for (i = len-1 ; i >= 0 ; i--) {
    element.options[i] = null ;
  }
<?
  $i = 0;
  foreach ($rowArr as $key => $value) {
    echo 'element.options['.$i.'] = new Option("'.$value.'", "'.$key.'", false, false) ;' . "\n" ;
    $i++;
  }
}elseif($element_type == "select2") { // valueが2つある時
?>
  var len = element.length ;
  for (i = len-1 ; i >= 0 ; i--) {
    element.options[i] = null ;
  }
<?
  for ($i = 0 ; $i < count($rowArr) ; $i++) {
        echo 'element.options['.$i.'] = new Option("'.$rowArr[$i][$changeValue1].($rowArr[$i][$changeValue1]?"　":"").$rowArr[$i][$changeValue2].'", "'.$rowArr[$i][$changeKey].'", false, false) ;' . "\n" ;  }

}elseif($element_type == "text") { // text elementの場合
?>
  element.value = '<?= $rowArr[$changeValue] ?>';
<?
}elseif($element_type == "multi") {
  for($j=$start_number; $j <= $end_number; $j++) {
?>
  var element1 = <?= $parent_str ?>parent.document.getElementById('<?= $change_element1."[".$j."]" ?>') ;
  var element2 = <?= $parent_str ?>parent.document.getElementById('<?= $change_element2."[".$j."]" ?>') ;
  var len = element1.length ;
  for (i = len-1 ; i >= 0 ; i--) {
    element1.options[i] = null ;
  }
  var len = element2.length ;
  for (i = len-1 ; i >= 0 ; i--) {
    element2.options[i] = null ;
  }
<?
      for ($i = 0 ; $i < count($rowArr1) ; $i++) {
        echo 'element1.options['.$i.'] = new Option("'.str_replace('"', '\"', $rowArr1[$i][$changeValue1]).'", "'.$rowArr1[$i][$changeKey1].'", false, false) ;' ;
      }
      for ($i = 0 ; $i < count($rowArr2) ; $i++) {
        echo 'element2.options['.$i.'] = new Option("'.str_replace('"', '\"', $rowArr2[$i][$changeValue2]).'", "'.$rowArr2[$i][$changeKey2].'", false, false) ;' ;
      }
  }
}

switch($item_type) { // 追加処理
    case "relation": // 関連情報取得
    case "relation_recent": // 関連情報取得
        if($_REQUEST['search1'] == "undefined") {
            // 検索配列のセット
            $searchList = RCMSRelation::getSearchList($tableID);
            for($i = 0; $i < 3; $i++) {
?>
    //API系モジュールが吐いた独自検索窓を削除
    var formSpace = parent.document.getElementById('ApiSpecificSearchForm');
    if (formSpace) formSpace.parentNode.removeChild(formSpace);

    var search = parent.document.getElementById('searchList<?php echo $i + 1; ?>');
    for (var i = search.length - 1 ; i >= 0 ; i--) {
      search.options[i] = null;
    }
<?
                $cnt = 0;
                foreach($searchList[$i] as $key => $value) {
                    echo 'search.options['.$cnt.'] = new Option("'.$value.'", "'.$key.'", false, false) ;' ;
                    $cnt++;
                }
?>
    search.style.display = (search.length == 0) ? 'none' : 'inline';
<?
            }
        }
?>
    parent.document.getElementById('loadingMsg').style.display = "none";
<?
        break;
    case 'api': //API系モジュールの関連情報検索窓
        //通常の検索窓は全て隠して、独自のHTMLを差し込む
        $apiSpecificSearchForm = Api_Relation::factory($tableID)->getSearchForm($search);
?>
    //他のAPI系モジュールが吐いた独自検索窓を削除
    var formSpace = parent.document.getElementById('ApiSpecificSearchForm');
    if (formSpace) formSpace.parentNode.removeChild(formSpace);

    parent.document.getElementById('searchList1').style.display = 'none';
    parent.document.getElementById('searchList2').style.display = 'none';
    parent.document.getElementById('searchList3').style.display = 'none';

    //独自HTMLを差し込む
    var container = parent.document.getElementById('searchList3').parentNode;
    var formSpace = parent.document.createElement('span');
    formSpace.id = 'ApiSpecificSearchForm';
    formSpace.innerHTML = <?php echo $apiSpecificSearchForm; ?>;
    container.appendChild(formSpace);

    parent.document.getElementById("loadingMsg").style.display = "none";
<?php
        break;
    case "hs": // 学校情報取得 高校
        break;
    case "univ": // 学校情報取得 大学
        break;
    case "gameOpponent": // 試合対戦相手情報
        break;
    case "empty": // 要素消去
?>
    var hidden = parent.document.getElementById('relation_type') ;
    hidden.value = "";
    var teamSearch = parent.document.getElementById('team_search');
    if(teamSearch.selectedIndex >= 0) {
        onChangeTeamSearch(teamSearch.options[teamSearch.selectedIndex].value, 1);
    }
    else {
        var select = parent.document.getElementById('relation_id') ;
        var len = select.length ;
        for (i = len-1 ; i >= 0 ; i--) {
            select[i] = null ;
        }
    }
<?
        break;
    default :
        break;
}

?>
}
</script>
<body onload="setValue();">
</body>
</html>

