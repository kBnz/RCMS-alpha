// コントロール連番
var idNum = 0 ;

// コンテンツ選択プルダウン変更時
function onChangeSelUniversity(value, search1, search2, search3) {
  document.getElementById('loadingMsg').style.display = "inline";
  var myIframe = document.getElementById('myFrame');
  if(value == "recent_update") {
    myIframe.src = "/js/getItem.php?search1="+ search1 + "&item_type=relation_recent";
  } else if (value.match(/^api_/)) {
    myIframe.src = '/js/getItem.php?apiID=' + value + "&search1="+ search1 + "&search2="+ search2 + "&search3=" + search3 + '&item_type=api';
  } else {
    myIframe.src = "/js/getItem.php?tableID=" + value + "&search1="+ search1 + "&search2="+ search2 + "&search3=" + search3 + "&item_type=relation";
  }
}

/** API系モジュールの検索フォームから検索を実行する関数 */
var searchApi = onChangeSelUniversity;  //意味がはっきりする関数名を使いたいだけ

// 追加ボタン押下時
function addOpt() {
  var selectFrom = document.getElementById('itemList') ;
  var ix = selectFrom.options.selectedIndex ;
  var moduleNm = getModuleNm(selectFrom.options[ix].value) ;
  var seq      = getSeq(selectFrom.options[ix].value) ;

  // コントロールの作成(本当はmoduleNmじゃなくてidnmの方が良い)
  parent.addCtrl(moduleNm, seq, selectFrom.options[ix].innerHTML, idNum, moduleNm) ;
}

// コントロールの作成
function addCtrl(moduleNm, seq, value, mode, idnm) {

  var select = document.getElementById('sel') ;
  idNum = idNum + 1 ;
  var idNum2 = idNum ;

  // 重複チェック
  if (dupChk(moduleNm, seq, value) == true) {
    alert("選択した項目は既に選択されています。") ;
    return ;
  }

  // アンカーの作成(イメージあり)
  var elm0 = document.createElement("a");
  elm0.href = "/management/" + moduleNm + "/" + moduleNm + "_edit/" + idnm + "=" + seq ;
  //var img = document.createElement("img");
  //img.setAttribute("src", "../images/modules/" + moduleNm + "/small_icon.gif");
  //img.setAttribute("border", "0");
  //elm0.appendChild(img);

  // アンカーの作成(イメージなし)
  var elm1 = document.createElement("a");
  elm1.href = "/management/" + moduleNm + "/" + moduleNm + "_edit/" + idnm + "=" + seq ;
  var str = document.createTextNode(value);
  elm1.appendChild(str);

  // DOMメソッドのサポート状態判定
  if (elm1.addEventListener) {
      elm1.addEventListener('mouseover', function(event) {showPreViewMoz(event, moduleNm, seq)}, true);
      elm1.addEventListener('mouseout', hiddenPreViewMoz, true);
  }
  else {
      elm1.attachEvent('onmouseover', function() {showPreViewIe(moduleNm, seq, true)});
      elm1.attachEvent('onmouseout', hiddenPreViewIe);
  }

  if (mode == 0) {
      // ボタンの作成
      var elm2 = document.createElement("a");
      elm2.href = "javascript:;";
      var str2 = document.createTextNode("[削除]");
      elm2.appendChild(str2);
      // DOMメソッドのサポート状態判定
      if (elm2.addEventListener) {
        elm2.addEventListener('click', function() {delRel(idNum2, seq, moduleNm, value);}, true);
      }
      else {
        elm2.attachEvent('onclick', function() {delRel(idNum2, seq, moduleNm, value);});
      }

      // tagの場合、選択していないタグであれば、追加しておく
      if (mode == "0" && moduleNm == "tag") {
        obj = document.getElementById('tag_id_'+seq);
        
        // 既に追加した場合(class=tag)、何もしない
        if (obj.getAttribute("class") == "not_tag") {
            choiceTag(obj, seq, value);
        }
      }

  }

  // hiddenの作成
  var elm3 = document.createElement("input");
  elm3.type = "hidden";
  elm3.name = "relation[" + idNum2 + "]" ;
  elm3.value = moduleNm + ":" + seq + ":" + value ;

  var item = document.createElement("span");
  if (item.getAttribute("className") != null) {
      item.setAttribute("className", "relationItem");  // ie
  }
  else {
      item.setAttribute("class", "relationItem"); // other
  }
  item.id = "relationItem_" + idNum2;

  item.appendChild(elm0) ;
  item.appendChild(elm1) ;
  if (mode == 0) {
    item.appendChild(elm2) ;
  }
  item.appendChild(elm3) ;

  select.appendChild(item) ;
}

function showPreViewMoz(myEvent, moduleNm, seq) {
  document.getElementById('myFrame2').src = '/management/relation_preview.php?selected=' + moduleNm + ':' + seq;
  myObj = document.getElementById('relationArea').style;
  myObj.left = myEvent.clientX + window.pageXOffset + "px";
  myObj.top  = myEvent.clientY + window.pageYOffset + "px";
  myObj.visibility = "visible";
}

function hiddenPreViewMoz(myEvent) {
  myObj = document.getElementById('relationArea').style;
  myObj.visibility = "hidden";
}

function showPreViewIe(moduleNm, seq) {
  document.getElementById('myFrame2').src = '/management/relation_preview.php?selected=' + moduleNm + ':' + seq;
  myObj = document.all['relationArea'].style;
  myObj.left = window.event.clientX + document.body.scrollLeft + "px";
  myObj.top  = window.event.clientY + document.body.scrollTop + "px";
  myObj.visibility = "visible";
}

function hiddenPreViewIe() {
  myObj=document.all['relationArea'].style;
  myObj.visibility = "hidden";
}

// 重複チェック
function dupChk(moduleNm, seq, value) {
    var bl = false ;

    for (var i = 1 ; i < idNum ; i++) {
        var b = document.getElementsByName("relation[" + i + "]")[0];
        if (b && b.value == (moduleNm + ":" + seq + ":" + value)) {
            bl = true ;
        }
    }
    return bl ;
}

// 削除ボタン押下時
function delRel(idNum, seq, moduleNm, value) {
    var sel = document.getElementById('sel') ;
    var item = document.getElementById('relationItem_' + idNum) ;
    sel.removeChild(item);
    
    // tagの場合、選択済みのタグのほうも削除しておく
    if (moduleNm == "tag") {
        obj = document.getElementById('tag_id_'+seq);
        
        // 既に削除した場合(class=not_tag)、何もしない
        if (obj.getAttribute("class") == "tag") {
            choiceTag(obj, seq, value);
        }
    }
    
}

function getModuleNm(value) {
  var pos = value.indexOf(":", 0) ;
  return value.substring(0, pos) ;
}

function getSeq(value) {
  var pos = value.indexOf(":", 0) ;
  return value.substring(pos+1, value.length) ;
}

function display(obj){
    var b = document.getElementById(obj);
    if (arguments.length == 2) {
        b.style.display = arguments[1];
    }
    else {
        if (document.getElementById(obj).style.display == "none") {
            document.getElementById(obj).style.display = "block";
        }
        else {
            document.getElementById(obj).style.display = "none";
        }
    }
}

// マスター利用時＠member情報
// 都道府県の選択が変更されたとき
function onChangeTdfkHi(value) {
  var myIframe = document.getElementById('myFrame');
  myIframe.src = "/js/getItem.php?searchID=" + value + "&item_type=hs_member" ;
}

// 頭文字の選択が変更されたとき
function onChangeInicial(value) {
  var myIframe = document.getElementById('myFrame');
  myIframe.src = "/js/getItem.php?searchID=" + value + "&item_type=univ_member";
}

// チームマスター使用時
// チームタイプが変更されたとき
function onChangeTeamTypeSearch(value) {
  if(value == "2") {
      var type = "inicial";
  }
  else if(value == "3") {
      var type = "tdfk";
  }
  else {
      var type = "empty";
  }
  var myIframe = document.getElementById('myFrame');
  myIframe.src = "/js/getItem.php?item_type=" + type;
}

// 検索項目が変更されたとき
function onChangeTeamSearch(value, parflg) {
  if(parflg == 1) {
    var myIframe = parent.document.getElementById('myFrame');
    if(parent.document.getElementById('relation_type').value == "2") {
       var type = "univ";
    }
    else if(parent.document.getElementById('relation_type').value == "3") {
       var type = "hs";
    }
    myIframe.src = "/js/getItem.php?searchID=" + escape(value) + "&item_type=" + type + "&parflg=1";
  }
  else {
    var myIframe = document.getElementById('myFrame');
    if(document.getElementById('relation_type').value == "2") {
       var type = "univ";
    }
    else if(document.getElementById('relation_type').value == "3") {
       var type = "hs";
    }
    myIframe.src = "/js/getItem.php?searchID=" + escape(value) + "&item_type=" + type;
  }
}

// 短縮名が選択されたとき(URL表示)
function onChangeTeamRelationNm(value) {
  var myIframe = document.getElementById('myFrame');
  if(document.getElementById('search_type').value == "2") {
     var type = "url_univ";
  }
  else if(document.getElementById('search_type').value == "3") {
     var type = "url_hs";
  }
  myIframe.src = "/js/getItem.php?item_type=" + type + "&searchID=" + value;
}

// 試合ラグビー管理
function onChangeTeamType(searchID, sports_type) {
  var myIframe = document.getElementById('myFrame');
  myIframe.src = "/js/getItem.php?item_type=gameOpponent&searchID=" + searchID +"&sports_type=" + sports_type;
}

function onChangeTeamType2(searchID, sports_type) {
  var myIframe = document.getElementById('myFrame');
  myIframe.src = "/js/getItem.php?item_type=gameTeam&searchID=" + searchID +"&sports_type=" + sports_type;
}

// 試合メンバー情報
function onChangeMmeberData(grade, order_no) {
  var myIframe = document.getElementById('myFrame');
  var season = document.getElementById('season').value;
  if(grade == 'ob') {
      document.getElementById('ob_space['+order_no+']').style.display = "block";
      var year = document.getElementById('graduate_yaer['+order_no+']').value;
      myIframe.src = "/js/getItem.php?item_type=gameMemberOb&searchID=" + year + "&order_no=" + order_no;
  }
  else {
      document.getElementById('ob_space['+order_no+']').style.display = "none";
      myIframe.src = "/js/getItem.php?item_type=gameMember&searchID=" + grade + "&order_no=" + order_no + "&season=" + season;
  }
  document.getElementById('member_id['+order_no+']').value = '';
  document.getElementById('member_name['+order_no+']').value = '';
}

function onChangeObData(year, order_no) {
  var myIframe = document.getElementById('myFrame');
  myIframe.src = "/js/getItem.php?item_type=gameMemberOb&searchID=" + year + "&order_no=" + order_no;
  document.getElementById('member_id['+order_no+']').value = '';
  document.getElementById('member_name['+order_no+']').value = '';
}

function onChangeTeamTypeConv(searchID, sports_type, order_no) {
  var myIframe = document.getElementById('myFrame');
  myIframe.src = "/js/getItem.php?item_type=teamConv&searchID=" + searchID +"&sports_type=" + sports_type + "&order_no=" + order_no;
}