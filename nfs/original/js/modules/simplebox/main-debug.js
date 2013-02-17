/*
 * Simplebox
 * built by JS Builder http://www.jackslocum.com/blog/2006/09/17/js-builder-a-simple-javascript-build-tool/
 */


document.writeln('<style type="text/css">@import url(/js/modules/simplebox/style.css);</style>');


(function() {



var COOKIE_NAME = 'Simplebox';


var DEFAULT_Z_INDEX = 100;


var isInstalled = null;


var isLoggedIn = null;


var canInsert = null;


var styles = null;


var data = null;


var box = div({id: 'Simplebox'});


var maxZ = DEFAULT_Z_INDEX;



var load = function(callback) {
	r('data', {
		method: 'get',
		parameters: $H({path: location.pathname, query: location.search.substr(1)}),
		onComplete: function(x) {
			try {
				data = eval(x.responseText);
				isInstalled = data.shift();
				isLoggedIn = data.shift();
				canInsert = data.shift();
				styles = $H(data.shift());
			} catch (e) {
				isInstalled = isLoggedIn = canInsert = false;
				data = styles = null;
			}
			if (callback) callback();
		}
	});
};


var paint = function() {
	if (data == null) return;

	
	var items = {}, pointer = data.length;
	while (pointer--)	
		items[data[pointer].id] = box.appendChild(createSimplebox(data[pointer]));

	
	box.items = $H(items);
};


var createSimplebox = Object.extend(function(o) {
	var self = arguments.callee, e = div(
		{
			_id: o.id, _isAuthor: o.isAuthor, _content: o.content,
			innerHTML: o.content.replace(/\n/g, '<br/>'), className: 'item ' + o.style
		},
		{position: 'absolute', left: o.x + 'px', top: o.y + 'px', zIndex: ++maxZ}
	);
	e.style.display = (isLoggedIn ? o.isHidden : cookie.isHidden(o.id)) ? 'none' : 'block';

	e.observe('click', self.onClick);
	e._draggable = new Draggable(e, self.dragOpt);
	hover(e, self.hoverIn, self.hoverOut);

	return e;
},

{
	
	dragOpt: {
		starteffect: function(e) { e.setOpacity(0.8); },
		endeffect: function(e) { e.setOpacity(1); Simplebox.move(e._id); }
	},
	
	hoverIn: function(e) { privateMenu.show(this._id); },
	
	hoverOut: function(e) { privateMenu.close(); },
	
	onClick: function(e) { (e.target || e.srcElement).style.zIndex = ++maxZ; }
});



var initialize = onReady.bind(null, function() {
	if (isInstalled === false) return;	
	if (data == null)
		setTimeout(initialize, 100);
	else {
		
		if (box.parentNode) box.remove();
		box = div({id: 'Simplebox'});

		maxZ = DEFAULT_Z_INDEX;
		menu.initialize();
		editor.initialize();
		paint();

		document.body.appendChild(box);
	}
});



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


var editor = {
	_editor: div({id: 'Simplebox_Editor'}, {position: 'absolute'}).hide(),
	_text: $(document.createElement('textarea')),
	_style: $(document.createElement('select')),
	_target: null,
	initialize: function() {
		var d = this._editor, t = this._text, s = this._style;
		t.rows = 5;

		styles.each(function(p) { s.options[s.length] = new Option(p.value, p.key); });
		s.observe('change', function() { d.className = $F(s); });

		d.appendChild(t);
		d.appendChild(document.createElement('br'));
		d.appendChild(s);
		d.appendChild(document.createElement('br'));
		d.appendChild(Object.extend(document.createElement('a'), {
			innerHTML: '編集終了', href: 'javascript:Simplebox.editor.close()'
		}));
		d.appendChild(document.createElement('br'));
		d.appendChild(Object.extend(document.createElement('a'), {
			innerHTML: '※ブラウザの幅によって、配置した位置がずれる可能性があります。'
		}));

		document.body.appendChild(d);
	},
	open: function(id) {
		var e = box.items[id], d = this._editor, t = this._text, s = this._style;

		if (this._target || !e._isAuthor) return;
		this._target = e;

		t.value = e._content;
		for (var i = 0, l = s.length; i < l; i++)
			if (e.hasClassName(s.options[i].value)) s.selectedIndex = i;
		d.className = $F(s);

		var height = e.getHeight(), offset = Position.cumulativeOffset(e);
		d.setStyle({left: offset[0] + 'px', top: (offset[1] + height + 10) + 'px', zIndex: ++maxZ}).show();
	},
	close: function() {
		if (!this._target || !this._target._isAuthor) return;
		var e = this._target, d = this._editor, t = this._text, s = this._style;

		this._target = null;
		d.hide();

		
		if ($F(t) == '') return;
		if ($F(t) == e._content && e.hasClassName($F(s))) return;

		e._content = t.value;
		e.className = 'item ' + s.value;
		e.innerHTML = t.value.replace(/\n/g, '<br/>');

		if (e._id)	
			r('modify', {parameters: $H({id: e._id, content: e._content, style: s.value})});
		else {	
			if (!canInsert) return;
			box.appendChild(e);
			menu.addNum(1);
			r('create', {
				parameters: $H({
					content: e._content, style: s.value,
					x: parseInt(e.style.left), y: parseInt(e.style.top),
					path: location.pathname, query: location.search.substr(1)
				}),
				onComplete: function(x) {
					var id = parseInt(x.responseText);
					e._id = id;
					box.items[id] = e;
				}
			});
		}
	},
	create: function() {	
		var d = this._editor, t = this._text, s = this._style;
		var menu = $('Simplebox_Menu'), h = menu.getHeight(), o = Position.cumulativeOffset(menu);
		var e = createSimplebox({
			id: 0, content: '', x: o[0], y: o[1] + h + 10,
			style: 'Style01', isHidden: false, isAuthor: true
		});

		if (this._target || !e._isAuthor) return;
		this._target = e;

		t.value = '';
		s.selectedIndex = 0;
		d.className = $F(s);

		d.setStyle({left: e.style.left, top: e.style.top, zIndex: ++maxZ}).show();
	}
};


var cookie = {
	show: function(id) {
		id = [].concat(id);	
		var ck = [].without.apply(Cookie.get(COOKIE_NAME).split(','), id).join(',');
		Cookie.expireInMonth(COOKIE_NAME, ck);
	},
	hide: function(id) {
		var ck = Cookie.get(COOKIE_NAME).split(',').concat(id).join(',');
		Cookie.expireInMonth(COOKIE_NAME, ck);
	},
	isHidden: function(id) {
		return !!Cookie.get(COOKIE_NAME).match('(^|,)' + id + '(,|$)');
	}
};


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
			setTimeout(function() { e.remove(); }, 2000);	
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


load();	
initialize();


function r(action, opt) {
	return new Ajax.Request('/direct.php?mt=simplebox&ct=ajax&action=' + action + '&d=' + new Date() % 100, opt);
}


function div(ext, style) {
	return Object.extend($(document.createElement('div')), ext || {}).setStyle(style || {});
}


})();
