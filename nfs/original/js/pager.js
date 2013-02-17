/* ====================================
 * class Pager
 * ==================================== */
/*
 * @param id     テーブルのID
 * @param cnt    全行数
 * @param per    1ページあたりの行数
 * @param sta    何行目からデータ行が始まるか
 */
function Pager(name, cnt, per, sta) {
   this._name = name;
   this._rowCount = cnt;
   this._perPage = per;
   this._startRowNo = sta;

   // 初期化
   this._currentPage = 0;
   this.onChangePage = function(){}
   this.speed = 1;
   this._list = document.getElementById(this._name);
}

// ----- setter -----
Pager.prototype.setRowCount = function(cnt) {
    this._rowCount = cnt;
}

// ----- getter -----

Pager.prototype.getTable = function() {
    return this._list;
}
Pager.prototype.getRowCount = function() {
    return this._rowCount;
}
Pager.prototype.getLastPageNo = function() {
    return Math.ceil(this._rowCount / this._perPage);
}
Pager.prototype.getFirstPageNo = function() {
    return 1;
}
Pager.prototype.getCurrentPageNo = function() {
    return this._currentPage;
}

// ----- public method -----

Pager.prototype.prev = function () {
//    alert("current=" + this.getCurrentPageNo() + "\nrow_count=" + this._rowCount + "\nper_page=" + this._perPage);
    var page = Math.min(this.getCurrentPageNo() - 1, this.getLastPageNo());
    page = Math.max(page, 1);
    this._changePage(this.getCurrentPageNo(), page);
}
Pager.prototype.next = function () {
//    alert("current=" + this.getCurrentPageNo() + "\nrow_count=" + this._rowCount + "\nper_page=" + this._perPage);
    var page = Math.min(this.getCurrentPageNo() + 1, this.getLastPageNo());
    page = Math.max(page, 1);
    this._changePage(this.getCurrentPageNo(), page);
}

Pager.prototype.first = function () {
    this.initView(this.getFirstPageNo());
//    this._changePage(this.getCurrentPageNo(), this.getFirstPageNo());
}
Pager.prototype.last = function () {
    this.initView(this.getLastPageNo());
//    this._changePage(this.getCurrentPageNo(), this.getLastPageNo());
}

Pager.prototype.initView = function (new_page) {
    if (this._rowCount <= 0) {
        return;
    }
    if (this.paging_now) {
        return;
    }
    new_page = Math.min(Math.max(new_page, 1), this.getLastPageNo());
    var s = Math.min((new_page - 1) * this._perPage + 1, this._rowCount);
    var e = Math.min(new_page * this._perPage, this._rowCount);
    for (var i = 1 ; i <= this._rowCount ; i++) {
        if (i >= s && i <= e) {
            this._rowView(i);
        }
        else {
            this._rowHide(i);
        }
    }
    this._currentPage = new_page;
    // fire
    this.onChangePage();
}

/* リストの行数をもとに行オブジェクトを取得 */
Pager.prototype.getRow = function (i) {
    var tbo = this.getTable().getElementsByTagName("tbody").item(0);
    var tr = tbo.getElementsByTagName("tr").item(i - 2 + this._startRowNo);
    return tr;
}
/* リストの主キーをもとに行オブジェクトを取得 */
Pager.prototype.getRowById = function (id) {
    var tr = document.getElementById(this._name + id);
    return tr;
}

// ----- private method -----

Pager.prototype._changePage = function (old_page, new_page) {
    if (this._rowCount <= 0) {
        return;
    }
    if (old_page == new_page) {
        return;
    }
    if (this.paging_now) {
        return;
    }
    this.paging_now = true;
    var o_s = (old_page - 1) * this._perPage + 1;  // 改ページ前の最初の行
    var n_s = (new_page - 1) * this._perPage + 1;  // 改ページ後の最初の行
    if (o_s < n_s) {
        this._pagedown(o_s, n_s);
    }
    else if (o_s > n_s) {
        this._pageup(o_s, n_s);
    }
    this._currentPage = new_page;

    // fire
    this._fireChangePage();
}

Pager.prototype._fireChangePage = function() {
    if (!this.paging_now) {
        this.onChangePage();
        return;
    }
    window.setTimeout(this._name + "._fireChangePage()", this.speed) ;
}

Pager.prototype._pagedown = function(current, n_s) {
    this.temp_current_row = current;
    this.temp_next_row = n_s;
    this._rowHide(this.temp_current_row);
    this._rowView(this.temp_current_row + this._perPage);
    this.temp_current_row++;
    if (this.temp_current_row >= this.temp_next_row) {
        this.paging_now = false;
        return;
    }
    if (this.speed > 0) {
        window.setTimeout(this._name + "._pagedown(" + this._name + ".temp_current_row," + this._name + ".temp_next_row)", this.speed) ;
    }
    else {
        this._pagedown(this.temp_current_row, this.temp_next_row);
        this.paging_now = false;
    }
}

Pager.prototype._pageup = function(current, n_s) {
    this.temp_current_row = current;
    this.temp_next_row = n_s;
    this._rowHide(this.temp_current_row + this._perPage - 1);
    this._rowView(this.temp_current_row - 1);
    this.temp_current_row--;
    if (this.temp_current_row <= this.temp_next_row) {
        this.paging_now = false;
        return;
    }
    if (this.speed > 0) {
        window.setTimeout(this._name + "._pageup(" + this._name + ".temp_current_row," + this._name + ".temp_next_row)", this.speed) ;
    }
    else {
        this._pageup(this.temp_current_row, this.temp_next_row);
        this.paging_now = false;
    }
}

Pager.prototype._rowHide = function (i) {
    var row = this.getRow(i);
    if (row != null) {
        row.style.display = "none";
    }
}
Pager.prototype._rowView = function (i) {
    var row = this.getRow(i);
    if (row != null) {
        row.style.display = "";
    }
}

/* ====================================
 * class ItemSelector
 * ==================================== */

/*
 * @param pg_a  候補リスト
 * @param pg_s  選択済リスト
 * @param name  このインスタンスを格納する変数名
 * @param ini   選択済みリストの初期リスト
 */
function ItemSelector(pg_a, pg_s, name, ini) {
    this._pg_a = pg_a;
    this._pg_s = pg_s;
    this._name = name;
    this._rmap = ini;
    this.class_selected_row = "selected_row";   // 候補リストで選択済み行に適用するcssクラス

    for (var i = 0 ; i < ini.length ; i++) {
        this.setARowClass(ini[i], this.class_selected_row);
    }

}
// ----- private method -----
ItemSelector.prototype._getID = function(id) {
    return document.getElementById(this._pg_s._name + "_id" + id);
}
ItemSelector.prototype._getStatus = function(id) {
    return document.getElementById(this._pg_s._name + "_sts" + id);
}
ItemSelector.prototype._getCancelDel = function(id) {
    return document.getElementById(this._pg_s._name + "_cdel" + id);
}
ItemSelector.prototype._getDel = function(id) {
    return document.getElementById(this._pg_s._name + "_del" + id);
}
ItemSelector.prototype._getJoined = function(id) {
    return document.getElementById(this._pg_s._name + "_joined" + id);
}
ItemSelector.prototype._getNonjoined = function(id) {
    return document.getElementById(this._pg_s._name + "_nonjoined" + id);
}
ItemSelector.prototype._getDel2 = function(id) {
    return document.getElementById(this._pg_s._name + "_del2" + id);
}

// ----- public method -----
ItemSelector.prototype.addItem = function(id, cols, btn_value) {
    for (var i in this._rmap) {
        if (this._rmap[i] == id) {
            return; // 重複
        }
    }
    var list = this._pg_s.getTable();
    var newRow = list.insertRow(list.rows.length);
    newRow.id = this._pg_s._name + id;
    var i = 0;
    for (; i < cols.length ; i++) {
        newRow.insertCell(i).appendChild(document.createTextNode(cols[i]));
    }
    var el = null;
    // cancel button
    el = document.createElement("input");
    el.type = "button";

    // 多言語対応
    if (btn_value != '' && btn_value != undefined) {
        el.value = btn_value;
    }
    else {
        el.value = "追加取消";
    }

    if (navigator.userAgent.indexOf("MSIE") != -1) {
        // ie
        el.setAttribute("onclick", new Function(this._name + ".cancelAddItem('" + id + "')"));
    }
    else {
        el.setAttribute("onclick", this._name + ".cancelAddItem('" + id + "')");
    }
    newRow.insertCell(i).appendChild(el);

    // id
    el = document.createElement("input");
    el.id = this._pg_s._name + "_id" + id;
    el.type = "hidden";
    el.name = this._pg_s._name + "_selected_id[]";
    el.value = id;
    newRow.appendChild(el);

    this._rmap.push(id);
    this._pg_s.setRowCount(this._rmap.length);
    this._pg_s.initView(this._pg_s.getLastPageNo());

    // 追加対象にする。
    this.setARowClass(id, this.class_selected_row);
}
ItemSelector.prototype.cancelAddItem = function(id) {
    var map = new Array();
    var idx = -1;
    for (var i = 0 ; i < this._rmap.length; i++) {
        if (this._rmap[i] != id) {
            map.push(this._rmap[i]);
        }
        else {
            idx = i;
        }
    }
    if (idx > 0) {
        var idobj = this._getID(id);
        var pa = idobj.parentNode;
        pa.removeChild(idobj);
        var list = this._pg_s.getTable();
        var tbody = list.getElementsByTagName("tbody").item(0);
        var row = tbody.getElementsByTagName("tr").item(idx + 1);
        tbody.removeChild(row);
    }
    this._rmap = map;
    this._pg_s.setRowCount(this._rmap.length);
    this._pg_s.initView(this._pg_s.getCurrentPageNo());

    // 追加対象からはずす。
    this.setARowClass(id, "");
}
ItemSelector.prototype.delItem = function(id) {
    var sts = this._getStatus(id);
    sts.value = "del";
    this._getCancelDel(id).style.display = "";
    this._getDel(id).style.display = "none";
}
ItemSelector.prototype.cancelDelItem = function(id) {
    var sts = this._getStatus(id);
    sts.value = "init";
    this._getCancelDel(id).style.display = "none";
    this._getDel(id).style.display = "";
}

ItemSelector.prototype.nonjoinedItem = function(id) {
    var sts = this._getStatus(id);
    sts.value = "nonjoined";
    this._getJoined(id).style.display = "none";
    this._getNonjoined(id).style.display = "inline";
    this._getDel2(id).style.display = "none";
}
ItemSelector.prototype.del2Item = function(id) {
    var sts = this._getStatus(id);
    sts.value = "del";
    this._getJoined(id).style.display = "none";
    this._getNonjoined(id).style.display = "none";
    this._getDel2(id).style.display = "inline";
}
ItemSelector.prototype.joinedItem = function(id) {
    var sts = this._getStatus(id);
    sts.value = "init";
    this._getJoined(id).style.display = "inline";
    this._getNonjoined(id).style.display = "none";
    this._getDel2(id).style.display = "none";
}

ItemSelector.prototype.setARowClass = function(id, cls) {
    var o = this._pg_a.getRowById(id);
    if (o != null) {
        if (o.getAttribute("className") != null) {
            o.setAttribute("className", cls);  // ie
        }
        else {
            o.setAttribute("class", cls);  // other
        }
    }
}
