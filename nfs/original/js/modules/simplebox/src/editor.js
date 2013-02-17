//編集画面を管理する
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

		//空欄 or 変更なしなら終了
		if ($F(t) == '') return;
		if ($F(t) == e._content && e.hasClassName($F(s))) return;

		e._content = t.value;
		e.className = 'item ' + s.value;
		e.innerHTML = t.value.replace(/\n/g, '<br/>');

		if (e._id)	//更新
			r('modify', {parameters: $H({id: e._id, content: e._content, style: s.value})});
		else {	//新規作成
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
	create: function() {	//新規作成処理
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
