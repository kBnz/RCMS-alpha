//ロードされたデータを描画する
/** 付箋ボックスを初期化し、描画された付箋をボックスに収める */
var paint = function() {
	if (data == null) return;

	//描画開始
	var items = {}, pointer = data.length;
	while (pointer--)	//古いものを下層にするために逆順
		items[data[pointer].id] = box.appendChild(createSimplebox(data[pointer]));

	//後処理
	box.items = $H(items);
};

/** 付箋を描画する */
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
//staticメンバー
{
	/** new Draggableで渡すオプション */
	dragOpt: {
		starteffect: function(e) { e.setOpacity(0.8); },
		endeffect: function(e) { e.setOpacity(1); Simplebox.move(e._id); }
	},
	/** hoverに渡すハンドラ */
	hoverIn: function(e) { privateMenu.show(this._id); },
	/** hoverに渡すハンドラ */
	hoverOut: function(e) { privateMenu.close(); },
	/** click時の動作 (最前面に移動する) */
	onClick: function(e) { (e.target || e.srcElement).style.zIndex = ++maxZ; }
});
