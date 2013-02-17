<?php
if(substr($_SERVER["SERVER_ADDR"],0,11)=="192.168.25."){define("NO_ACCESSCOUNT","1");}
/*
 * 関連情報の入力補助
 *
 */
//基本ファイルのインクルード
require_once("default.php");

$order = $_REQUEST['order'];
$type = $_REQUEST['type'];

$strLabel_1 = translate('/modules/menu/label/recent_update_contents'); // 最近更新したコンテンツ
$strLabel_2 = translate('/label/add'); // 追加
$strLabel_3 = translate('/label/search'); // 検索
$strLabel_4 = translate('/original/js/infoinput/label/select'); // 選択
$strLabel_5 = translate('/original/js/infoinput/label/up_to_contents'); // 100件まで表示
$strLabel_6 = translate('/label/preview'); // プレビュー

$modules_option = array();
$modules_option["recent_update"] = $strLabel_1; // 最近更新したコンテンツ
$relation_array = setRelationModule($INCLUDE_MODULE_LIST,$conf_tab);
foreach($relation_array as $value) {
    $modules_option[$value] = $contName[$value];
}
$js_host = getCDNDomain("js");
$css_host = getCDNDomain("css");

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?=$css_host?>/css/management/default.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="<?=$js_host?>/js/relationItem.js"></script>
<script>
<!--
function selectMember() {
        var select = document.getElementById('itemList');
        var member_id = select.value.replace('member:', '');
        parent.document.getElementById('member_id<?php if(isset($order)){print("[$order]");} ?>').value = member_id;
        parent.document.getElementById('member_name<?php if(isset($order)){print("[$order]");} ?>').value = select.options[select.selectedIndex].text;
        parent.document.getElementById('member_grade<?php if(isset($order)){print("[$order]");} ?>').value = document.getElementById('searchList1').value;
        parent.document.getElementById('inputArea<?php if(isset($order)){print("[$order]");} ?>').style.visibility = "hidden";
        parent.document.getElementById('subInfo<?php if(isset($order)){print("[$order]");} ?>').style.display = "none";
}

function closeArea() {
        parent.document.getElementById('inputArea<?php if(isset($order)){print("[$order]");} ?>').style.display = "none";
        parent.document.getElementById('inputArea2<?php if(isset($order)){print("[$order]");} ?>').style.display = "block";
}
//-->
</script>
<style>
body {
    background-color: #eeeeee;
}
.close {
    background-color: #cccccc;
    text-align: right;
    padding: 2px 5px 2px 0;
}
.searchBox {
    background-color: #eeeeee;
    text-align: left;
    padding: 10px;
}
.searchBox:after {
    content: ".";
    display: block;
    height: 0;
    clear: both;
    visibility: hidden;
}
/* \*/
*html .searchBox {
    height: 1%;
}
.searchBox h1 {
    background-color: #FEDF6A;
    font-size: 12px;
    padding: 3px 10px;
}
.searchBox .searchArea h1 {
    float: left;
    margin-right: 10px;
}
.searchArea {
    margin-bottom: 10px;
}
.selectArea {
    clear: both;
    margin-right: 10px;
    float: left;
}
.previewArea {
    background-color: #ffffff;
    float: right;
    width: 200px;
    height: 200px;
    padding: 5px;
}


</style>
</head>
<body>

<p class="close"><a href="javascript:closeArea();">[×]</a></p>

<div class="searchBox">
    <span id="loadingMsg" style="display:none"><blink>Now Loading...</blink></span>

    <div class="searchArea">
        <h1><?=$strLabel_3//検索?></h1>
        <span id="relation_items">
<?php
if($type != 'gamemember') {
        echo '<select id="selUniversity" onChange="onChangeSelUniversity(this.value)">';
        foreach($modules_option as $key => $val) echo "<option value=\"{$key}\">{$val}</option>";
        print '</select>';
}
?>
        </span>

        <select name="searchList1" id="searchList1" onChange="onChangeSelUniversity(document.getElementById('selUniversity').value, this.value, document.getElementById('searchList2').value, document.getElementById('searchList3').value);" style="margin:0px 5px;display:none">
        </select>

        <select name="searchList2" id="searchList2" onChange="onChangeSelUniversity(document.getElementById('selUniversity').value, document.getElementById('searchList1').value, this.value, document.getElementById('searchList3').value);" style="margin:0px 5px;display:none">
        </select>

        <select name="searchList3" id="searchList3" onChange="onChangeSelUniversity(document.getElementById('selUniversity').value, document.getElementById('searchList1').value, document.getElementById('searchList2').value, this.value);" style="margin:0px 5px;display:none">
        </select>

    </div>

    <div class="selectArea">
        <select name="itemList" id="itemList" onChange="document.getElementById('relation_preview').src = '/management/menu/relation_preview/selected=' + this.options[this.selectedIndex].value;" style="width:370px;">
        </select><br />
<?
if($type != "gamemember") {
?>
                        <input type="button" name="addRel" value="<?=$strLabel_2//追加?>" onClick="addOpt();">
<?
} else {
?>
                        <input type="button" name="addRel" value="<?=$strLabel_4//選択?>" onClick="selectMember();">
                        <input type="hidden" name="selUniversity" id="selUniversity" value="member">
<?
}
?>
<br /><span style="color:red;font-size:smaller">※<?=$strLabel_5//100件まで表示?></span>

    </div>

    <div class="previewArea">
        <h1><?=$strLabel_6//プレビュー?></h1>
            <iframe name="relation_preview" src="<?=$js_host?>/js/empty.html" id="relation_preview" style="height: 170px;width: 200px;" scrolling="Auto" marginheight="0" marginwidth="0" frameborder="0"></iframe>
    </div>



<iframe id="myFrame" frameborder="0" vspace="0" hspace="0" marginwidth="0" marginheight="0" src="<?=$js_host?>/js/empty.html" width="1" height="1" scrolling="no" style="overflow:hidden"></iframe>

</div>


<script type="text/javascript">
// セレクトの作成
<?
if($type != "gamemember") {
        print "onChangeSelUniversity(document.getElementById('selUniversity').value);" ;
} else {
        print "onChangeSelUniversity('member');";
}
?>
</script>

</body>
</html>
