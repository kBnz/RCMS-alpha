//付箋メニューを管理するオブジェクト
/** 付箋全体を操作するメニュー */
var menu = {
	_menu: div({id: 'Simplebox_Menu', className: 'SB_Style01'}, {position: 'absolute'}).hide(),
	initialize: function() {
		var button = $('Simplebox_Button');
		if (button) button.innerHTML = '<a href="javascript:Simplebox.menu.open()">付箋(<span id="Simplebox_Num" style="display:inline">' + data.length + '</span>)</a>';

		var menuItems = [
			'<a href="javascript:Simplebox.showAll()">全て表示</a>',
			'<a href="javascript:Simplebox.hideAll()">全て非表示</a>',
			'<a href="javascript:Simplebox.menu.close()">閉じる</a>'
		];

		if (canInsert) menuItems.unshift('<a href="javascript:Simplebox.editor.create()">新規作成</a>');

		this._menu.innerHTML = menuItems.join(' | ');
		document.body.appendChild(this._menu);
	},
	open: function(id) {
		var e = id ? $(box.items[id]) : $('Simplebox_Button');
		var height = e.getHeight(), offset = Position.cumulativeOffset(e);
		this._menu.setStyle({left: offset[0] + 'px', top: (offset[1] + height + 10) + 'px', zIndex: ++maxZ}).show();
	},
	close: function() { this._menu.hide(); },
	updateNum: function(n) { try { $('Simplebox_Num').innerHTML = n; } catch (e) {} },
	addNum: function(n) {
		try {
			var e = $('Simplebox_Num');
			e.innerHTML = parseInt(e.innerHTML) + n;
		} catch (e) {}
	}
};

/** 個別の付箋にくっつくメニュー */
var privateMenu = {
	_menu: div(),
	_reader: [
		'<a href="javascript:Simplebox.hide(#itemId#)">非表示</a>'
	],
	_author: [
		'<a href="javascript:Simplebox.editor.open(#itemId#)">編集</a>',
		'<a href="javascript:Simplebox.dispose(#itemId#)">削除</a>',
		'<a href="javascript:Simplebox.hide(#itemId#)">非表示</a>'
		
	],
	show: function(id) {
		var e = box.items[id], menu = '<a href="javascript:Simplebox.menu.open(#itemId#)">メニュー</a>: ';
		menu += (e._isAuthor ? this._author : this._reader).join(' | ');
		this._menu.innerHTML = menu.replace(/#itemId#/g, id);
		e.appendChild(this._menu);
	},
	close: function() { if (this._menu.parentNode) this._menu.parentNode.removeChild(this._menu); }
};
