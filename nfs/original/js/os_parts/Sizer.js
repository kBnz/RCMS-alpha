/**
 * ブログパーツのサイズを取得する
 * @author 高澤
 */
function Sizer() {
	this.initialize.apply(this, arguments);
}
Sizer.prototype = {
	target: {},	//測る対象
	width: 200,
	height: 400,
	/** @param mixed target 測る対象を囲むdiv要素 or そのid */
	initialize: function(target) {
		this.target = (typeof target == 'string') ? document.getElementById(target) : target;
		this.size();
	},
	size: function() {
		this.width  = this.target.offsetWidth;
		this.height = this.target.offsetHeight;
	},
	/**
	 * 幅と高さをinput要素に入力する
	 * @param mixed width 幅を入力するinput要素 or そのid
	 * @param mixed height 高さを入力するinput要素 or そのid
	 */
	send: function(width, height) {
		if (typeof width  == 'string') width  = document.getElementById(width);
		if (typeof height == 'string') height = document.getElementById(height);
		width.value = this.width;
		height.value = this.height;
		width.onchange();
		height.onchange();
	}
}