function showKeywordSummary(word, event) {
    if(window.event) { // IE
        summaryarea.style.top  = window.event.clientY + document.body.scrollTop - 100 + "px";
    } else {           // gecko
        summaryarea.style.top  = event.clientY + window.pageYOffset - 100 + "px";
    }
    keyword = word;
    keywordLink.innerHTML = '<a href="/keyword_detail/word='+keyword+'">詳細を見る</a>'
    summaryarea.style.display = '';
    titlearea.innerHTML = word;
    msgarea.innerHTML = 'NOW LOADING';
    getKeywordSummary();
}

function getKeywordSummary() {
    var xml = new JKL.ParseXML( "/direct.php?mt=keyword&ct=summary&word="+keyword );
    var func = function ( data )
    {  
        getSummaryData( data );
    }
    xml.async( func );
    xml.parse();
} // getKeywordSummary

function getSummaryData(xml) {
    if(eval(xml["keyword"]["exist"]) == 1) {
        keywordLink.innerHTML = '<a href="/keyword_detail/word='+keyword+'">詳細を見る</a>'
    }
    else {
        keywordLink.innerHTML = '<a href="/keyword_detail/MODE=NEW&word='+keyword+'">記事を作成する</a>'
    }
    
    msgarea.innerHTML = xml["keyword"]["summary"];
}

function hiddenKeywordSummary() {
    document.getElementById('keywordSummaryArea').style.display = "none";
}

function keywordEditStart() {
    document.getElementById("keywordDefaultView").style.display = "none";
    document.getElementById("keywordEditView").style.display = "";
}

function keywordInsert() {
    document.getElementById("MODE").value = "INSERT";
    document.keyword_detail.submit();
}

function keywordUpdate() {
    document.getElementById("MODE").value = "UPDATE";
    document.keyword_detail.submit();
}

function keywordPreview() {
    document.getElementById("MODE").value = "PREVIEW";
    document.keyword_detail.submit();
}

function showkeywordListDetail(word, obj, no) {
    keyword = word;
    contentslink.innerHTML = '<a href="/keyword_detail/word='+keyword+'">詳細を見る</a>';
    if(oldobj) {
        if(oldobj == obj) {
            return ;
        }
        toUnselectObj(oldobj);
    }
    toSelectObj(obj);
    oldobj = obj;
    if((no-2)*155 - document.getElementById("keywordList").scrollLeft > 0) {
        pageMove((no-2)*155 - document.getElementById("keywordList").scrollLeft, "r");
    } else {
        pageMove(document.getElementById("keywordList").scrollLeft - (no-2)*155, "l");
    }
    getKeywordDetail();
}

function getKeywordDetail() {
    var xml = new JKL.ParseXML( "/direct.php?mt=keyword&ct=summary&word="+keyword);
    var func = function ( data )
    {
        getDetailData( data );
    }
    xml.async( func );
    xml.parse();
} // getKeywordSummary

function getDetailData(xml) {
    contentsarea.innerHTML = xml.keyword.summary;
}

function toSelectObj(obj) {
    obj.style.borderBottom = '5px solid #FFFFFF';
}

function toUnselectObj(obj) {
    obj.style.borderBottom = '5px solid #FF9933';
}

function pageLeft() {
    pageMove(500, 'l');
}

function pageRight() {
    pageMove(500, 'r');
}

var count;
var timer;
var v;
var a;

function pageMove(px, side) {
    if(timer) {
        clearInterval(timer);
    }
    count = 0;
    v = 4*px/100;
    a = 4*px/5000;
    if(side == 'r') {
        timer = setInterval("moveRight()",10);
    } else {
        timer = setInterval("moveLeft()",10);
    }
}

function moveRight() {
    if(count == 50) {
        clearInterval(timer);
    }
    document.getElementById("keywordList").scrollLeft += v-(a*count);
    count++;
}

function moveLeft() {
    if(count == 50) {
        clearInterval(timer);
    }
    document.getElementById("keywordList").scrollLeft -= v-(a*count);
    count++;
}

function getKeywordList(index) {
    pageMove(document.getElementById("keywordList").scrollLeft, "l");
    contentsarea.innerHTML = "";
    contentslink.innerHTML = "";
    var xml = new JKL.ParseXML( "/direct.php?mt=keyword&ct=list&index="+index );
    var func = function ( data )
    {  
        getListData( data );
    }
    xml.async( func );
    xml.parse();
}

function getListData(xml) {
    str = "<table><tr>";
    if(xml["keyword"]["list"]) {
        str += xml["keyword"]["list"];
    }
    else {
        str += "<td>まだデータはありません。</td>";
    }
    str += "</tr></table>";
    keywordList.innerHTML = str;
}
