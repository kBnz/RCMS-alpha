/**
 * ブログパーツ作成画面用JavaScript
 * prototype.js必須
 * @author 高澤
 */
/** パラメータを扱うクラス */
function Param() {}
Param.prototype = {
	base: '.',
	php: 0,
	tpl: 0,
	width: 200,
	height: 400,
	param: '',
	style: '',
	color0: '',
	color1: '',
	color2: '',
	color3: '',
	serialize: function(format) {
		var p = {
			php:    parseInt(this.php)    || 0,
			tpl:    parseInt(this.tpl)    || 0,
			width:  parseInt(this.width)  || 200,
			height: parseInt(this.height) || 400,
			style:  String(this.style),
			color0: String(this.color0.match(/[0-9a-f]{6}/i) || ''),
			color1: String(this.color1.match(/[0-9a-f]{6}/i) || ''),
			color2: String(this.color2.match(/[0-9a-f]{6}/i) || ''),
			color3: String(this.color3.match(/[0-9a-f]{6}/i) || '')
		}
		var arr = [];
		if (format.match(/^GET$/i)) {
			for (var k in p) {
				arr.push(k + '=' + p[k]);
			}
			if (this.param != '') arr.push(this.param);
			return arr.join('&');
		} else if (format.match(/^JSON$/i)) {
			p.base  = String(this.base);
			p.param = String(this.param);
			for (var k in p) {
				if (typeof p[k] == 'number') {
					arr.push(k + ':' + p[k]);
				} else if (typeof p[k] == 'string') {
					arr.push(k + ':"' + p[k] + '"');
				}
			}
			return '{' + arr.join(',') + '}';
		} else {
			return '';
		}
	}
}

/** エディット画面を操るオブジェクト */
var edit = {
	//ROOT_URLの値
	base: '.',
	//使用するinput要素などのid属性値
	form:     'form',	//フォーム
	module:   'module',	//モジュールリスト
	content:  'content',	//コンテンツリスト
	template: 'template',	//テンプレートリスト
	param:    'param',	//GETパラメータ
	width:    'width',	//幅
	height:   'height',	//高さ
	preview:  'preview',	//プレビューを表示するiframe
	code:     'code',	//コードを表示するtextarea
	size:     'size',	//サイズ自動設定画面を開くボタン
	style:    'style', //スタイル
	color0:   'color0',	//色0
	color1:   'color1',	//色1
	color2:   'color2',	//色2
	color3:   'color3',	//色3

	//パラメータオブジェクト
	po: new Param(),

	/** window.onloadで必ず呼び出すこと */
	initialize: function() {
		var self = this;
		var getLists = this.base2 + '/js/os_parts/getLists.php';
		this.po.base = this.base;
		var rf = function(name) {
			return function() {
				self.po[name] = this.value;
				self.refresh();
			}
		}

		$(this.form).onsubmit = function() {
			self.refresh();
			return false;
		}
		$(this.module).onchange = function() {
			var url = getLists + '?moduleName=' + encodeURI(this.value);
			new Ajax.Request(url, { onComplete: function(x) { self.refreshList(x, self.content); } });
		}
		$(this.content).onchange = function() {
			var url = getLists + '?phpID=' + encodeURI(this.value);
			new Ajax.Request(url, { onComplete: function(x) { self.refreshList(x, self.template); } });
			self.po.php = $F(this);
			$(self.param).value = '';
			$(self.param).onchange();
		}
		$(this.size).onclick = function() {
			self.showSizer();
		}
		$(this.code).onfocus = function() {
			self.refresh();
			this.select();
		}
		$(this.template).onchange = rf('tpl');
		$(this.param).onchange = rf('param');
		$(this.width).onchange = rf('width');
		$(this.height).onchange = rf('height');
		if ($(this.style)) $(this.style).onchange = rf('style');
		if ($(this.color0)) $(this.color0).onchange = rf('color0');
		if ($(this.color1)) $(this.color1).onchange = rf('color1');
		if ($(this.color2)) $(this.color2).onchange = rf('color2');
		if ($(this.color3)) $(this.color3).onchange = rf('color3');
	},
	/** Ajaxで受け取ったデータを元にselect要素を更新する */
	refreshList: function (xhr, select){
		var data = eval('(' + xhr.responseText + ')');
		var s    = $(select);

		s.length = 0;
		if (data) {
			for (var k in data) {
				s.length++;
				s.options[s.length - 1].value = k;
				s.options[s.length - 1].text  = data[k];
			}
		}
		s.selectedIndex = 0;
		s.onchange();
	},
	/** サイズ自動設定画面を開く */
	showSizer: function() {
		var url = this.base2 + '/direct.php?mt=os_parts&ct=display&sizer&' + this.po.serialize('GET');
		window.open(url, 'os_parts_sizer');
	},
	/** プレビュー更新＆コード生成 */
	refresh: function() {
		if ($F(this.content) && $F(this.template)) {
			var prv = $(this.preview);
			prv.width  = parseInt($F(this.width))  || 200;
			prv.height = parseInt($F(this.height)) || 400;
			prv.src = this.base2 + '/direct.php?mt=os_parts&ct=display&' + this.po.serialize('GET');
			
			var code = 'var rcms_os_parts=' + this.po.serialize('JSON');
			code = '<script type="text/javascript">'+code+'</script>';
			code += '<script type="text/javascript" src="'+this.base+'/js/os_parts/rcms_os_parts.js"></script>';
			$(this.code).value = code;
		}
	}
}