/**
 * あて先クラス
 */
if (typeof mail == "undefined") {
  mail = {};
}

mail.ToList = function (tableId) {
    this._tableId = tableId ;
    this._tableObj = document.getElementById(tableId) ;
    this._seq = 0 ;
}
mail.ToList.prototype.getIdList = function() {
    var list = [];
    for (var i = 0 ; i < this._seq ; i++) {
        var o_id = document.getElementById(this._tableId + "_member_id_" + i);
        if (o_id) {
            list.push(o_id.value);
        }
    }
    return list;
}
mail.ToList.prototype.contains = function(id) {
    var list = this.getIdList();
    var len = list.length;
    for (var i = 0 ; i < len ; i++) {
        if (list[i] == id) {
            return true;
        }
    }
    return false;
}
mail.ToList.prototype.getTable = function() {
    return this._tableObj ;
}
mail.ToList.prototype.addItem = function(id, name) {
    if (this.contains(id)) {
//        alert('送り先が重複しています');
        return;
    }
    // セットする行を取得する 空行があればそれを使って、なければ新規行
    var seq = null;
    for (var i = 0 ; i < this._seq ; i++) {
        var o_id = document.getElementById(this._tableId + "_member_id_" + i);
        if (o_id && o_id.value == '') {
            seq = i;
        }
    }
    if (seq == null) {
        var seq = this.addRow();
    }
    this.setItem(seq, id, name);
}
/**
 * 指定の行に値を設定します。
 */
mail.ToList.prototype.setItem = function(seq, id, name) {
    var o_name = document.getElementById(this._tableId + "_member_name_" + seq);
    var o_id = document.getElementById(this._tableId + "_member_id_" + seq);
    o_name.innerHTML = name;
    o_id.value = id;
}
/**
 * 一行追加
 * return 行の識別番号
 */
mail.ToList.prototype.addRow = function() {
    var seq = this._seq++;
    var table = this.getTable();
    var row = this.getTable().insertRow(table.rows.length);
    row.id = this._tableId + '_row_' + seq;

    var cell = row.insertCell(0);
    cell.appendChild(document.createTextNode(''));
    cell.id = this._tableId + "_member_name_" + seq;

    // 削除
    var el = document.createElement("input");
    el.type = "button";
    el.value = "削除";
    if (navigator.userAgent.indexOf("MSIE") != -1) {
        // ie
        el.setAttribute("onclick", new Function(this._tableId + ".removeRow(" + seq + ")"));
    }
    else {
        el.setAttribute("onclick", this._tableId + ".removeRow(" + seq + ")");
    }
    row.insertCell(1).appendChild(el);

    // id
    el = document.createElement("input");
    el.id = this._tableId + "_member_id_" + seq;
    el.type = "hidden";
    el.name = 'to_member_id[]';
    row.appendChild(el);

    return seq;
}
/**
 * 指定の行を削除します。
 */
mail.ToList.prototype.removeRow = function(seq) {
    var row = document.getElementById(this._tableId + '_row_' + seq);
    var p = row.parentNode;
    p.removeChild(row);
}

mail.send = function(action, to) {
    var form = document.createElement('FORM');
    form.action = action;
    form.method = "post";
    form.appendChild(this.cei("hidden", "MODE", "EDIT"));
    form.appendChild(this.cei("hidden", "to_member_id", to));
    document.body.appendChild(form);
    form.submit();
}

mail.addReject = function(action, member_id, DG_CODE) {
    var form = document.createElement('FORM');
    form.action = action;
    form.method = "post";
    form.appendChild(this.cei("hidden", "MODE", "J_ADD"));
    form.appendChild(this.cei("hidden", "other_member_id", member_id));
    form.appendChild(this.cei("hidden", "DG_CODE", DG_CODE));
    document.body.appendChild(form);
    form.submit();
}

mail.cei = function(type, name, value) {
    var ele = document.createElement("input");
    ele.type = type;
    ele.name = name;
    ele.value = value;
    return ele;
}
