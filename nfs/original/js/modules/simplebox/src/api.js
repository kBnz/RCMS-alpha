//外部からアクセス可能な関数を定義
//付箋の操作はここに集める。
window.Simplebox = {
	menu: {
		open: menu.open.bind(menu),
		close: menu.close.bind(menu)
	},
	editor: {
		open: editor.open.bind(editor),
		close: editor.close.bind(editor),
		create: editor.create.bind(editor)
	},
	move:function(id) {
		var e = box.items[id];
		if (e._isAuthor) {
			var offset = Position.cumulativeOffset(e);
			r('move', {parameters: $H({id: id, x: offset[0], y: offset[1]})});
		}
	},
	dispose:function(id) {
		var e = box.items[id];
		if (e._isAuthor) {
			box.items.remove(id);
			e.visualEffect('DropOut', {duration: 0.5});
			r('dispose', {parameters: $H({id: id})});
			menu.addNum(-1);
			setTimeout(function() { e.remove(); }, 2000);	//エフェクト終了後に削除
		}
	},
	show: function(id) {
		box.items[id].visualEffect('Appear', {duration: 0.5});
		if (isLoggedIn) r('show', {parameters: $H({id: id})});
		else cookie.show(id);
	},
	hide: function(id) {
		box.items[id].visualEffect('Fade', {duration: 0.5});
		if (isLoggedIn) r('hide', {parameters: $H({id: id})});
		else cookie.hide(id);
	},
	showAll: function() {
		box.items.each(function(pair) { pair.value.visualEffect('Appear', {duration: 0.5}); });
		if (isLoggedIn) r('show', {parameters: $H({'id[]': box.items.keys()})});
		else cookie.show(box.items.keys());
	},
	hideAll: function() {
		box.items.each(function(pair) { pair.value.visualEffect('Fade', {duration: 0.5}); });
		if (isLoggedIn) r('hide', {parameters: $H({'id[]': box.items.keys()})});
		else cookie.hide(box.items.keys());
	}
};
