/** 1ページあたりの件数の既定値 */
var DEFAULT_COUNT = 20;

/** リクエストを送信するクラス */
var HotPepperApi = Class.create();
HotPepperApi.prototype = {
	/** APIのリクエスト先URL */
	base: '',
	/** APIキーとリクエスト先URLを受け取る */
	initialize: function(base) {
		this.base = base;
	},
	/**
	 * リクエストを送信する
	 * @param string target APIの種類(/GourmetSearch/V110/等)
	 * @param object query {キー: 値}の形式の検索クエリ
	 * @param function handler レスポンスを処理する関数
	 */
	send: function(target, query, handler) {
		var url = this.base + encodeURIComponent(target + '?' + $H(query).toQueryString());
		new Ajax.Request(url, { onComplete: handler });
	}
};

/** 検索処理を担当するクラス */
var HotPepperSearch = Class.create();
HotPepperSearch.prototype = {
	/** APIクラスを保持 */
	_api: null,
	/** 検索結果を表示する関数 */
	_handler: null,
	/** APIクラスを受け取り、_handlerを生成する */
	initialize: function(api) {
		this._api = api;
		this._handler = this._createHandler();
	},
	/** 検索を実行する */
	search: function(query) {
		$('ahResultViewer').innerHTML = 'Now loading...';

		//検索クエリをlocation.hashに保存する
		location.hash = encodeURIComponent($H(query).toQueryString());

		this._api.send('/GourmetSearch/V110/', query, this._handler);

	},
	/** 最後に検索したクエリでもう一度検索する */
	restore: function() {
		var query = this._loadQuery();
		if (query) this.search(query);
	},
	/** 引数で指定したページにジャンプする */
	moveTo: function(page) {
		var query = this._loadQuery();
		if (query) {
			query.Start = ((page - 1) * (query.Count || DEFAULT_COUNT)) + 1;
			this.search(query);
		}
	},
	/** location.hashに保存された検索クエリを復元する */
	_loadQuery: function() {
		var stored = decodeURIComponent(location.hash.substr(1));
		return (stored.indexOf('=') < 0) ? false : stored.toQueryParams();
	},
	/** 検索結果を表示する関数を生成する */
	_createHandler: function() {
		var fetch = function(parent, tagName) { return parent.getElementsByTagName(tagName)[0].firstChild.nodeValue; };
		var pages_add = function(id, label) { this.push('<a href="javascript:moveTo(' + id + ')">' + label + '</a>'); };
		return function(xhr) {
			try {
				var xml = xhr.responseXML;
				var num = parseInt(fetch(xml, 'NumberOfResults'));
				var from = parseInt(fetch(xml, 'DisplayFrom'));
				var perPage = parseInt(fetch(xml, 'DisplayPerPage'));

				//ページ案内
				var navi = num + '件中 ' + from + ' - ' + Math.min(from + perPage -1, num) + '件を表示';

				var pages = [];
				var last = Math.ceil(num / perPage);

				if (last > 1) {
					pages.add = pages_add;

					var current = Math.ceil(from / perPage);
					if (current > 1) {
						pages.add(1, '&lt;&lt;先頭');
						pages.add(current - 1, '&lt;前へ');
					}
					if (current < last) {
						pages.add(current + 1, '次へ&gt;');
						pages.add(last, '最後&gt;&gt;');
					}
					var start = Math.floor((current - 1) / 10) * 10 + 1;
					var end = Math.min(start + 10, last + 1);
					for (var i = start; i < end; i++) {
						if (i == current) pages.push(i);
						else pages.add(i, i);
					}
				}

				navi = '<tr><th colspan="2">' + navi + '&nbsp;&nbsp;' + pages.join('&nbsp;&nbsp;') + '</th></tr>';

				//検索結果
				var shops = xhr.responseXML.getElementsByTagName('Shop');
				var result = '';
				for (var i = 0, l = shops.length; i < l; i++)
					result += '<tr>\n\t<td>\n\t\t<img src="' + fetch(shops[i], 'PcLargeImg') + '" /><br />\n\t\t<span>写真提供：ホットペッパー.jp</span>\n\t</td>\n\t<td>\n\t\t<p>' + fetch(shops[i], 'GenreCatch') + '</p>\n\t\t<p style="font-weight:bold">' + fetch(shops[i], 'ShopName') + '</p>\n\t\t<p>' + fetch(shops[i], 'ShopCatch') + '</p>\n\t\t<p>' + fetch(shops[i], 'StationName') + ': ' + fetch(shops[i], 'Access') + '</p>\n\t\t<p>' + fetch(shops[i], 'Open') + '</p>\n\t\t<p><a href="/management/?mt=api_hotpepper&ct=api_hotpepper_edit&shop_id=' + fetch(shops[i], 'ShopIdFront') + '">関連付け画面へ</a></p>\n\t</td>\n</tr>';

				$('ahResultViewer').innerHTML = '<table>' + navi + result + navi + '</table>';
			} catch (e) {
				$('ahResultViewer').innerHTML = '検索に失敗しました。';
			}
		};
	}
};

/** 検索フォームの動作を担当するクラス */
var HotPepperForm = Class.create();
HotPepperForm.prototype = {
	/** APIクラス */
	_api: null,
	/** 検索クラス */
	_search: null,
	/** 検索フォーム要素 */
	_form: null,
	/** APIクラスと検索クラスとform要素を受け取る */
	initialize: function(api, search, form) {
		this._api = api;
		this._search = search;
		this._form = $(form);
		
		this.loadDefault();
	},
	/** onsubmitハンドラ */
	submit: function() {
		var e = this._form.elements;
		var q = {Order: 3, Count: DEFAULT_COUNT};
		if (tmp = $F(e.Count)) q.Count = tmp;

		var keys = ['Keyword', 'ShopAddress', 'GenreCD', 'FoodCD', 'LargeAreaCD', 'MiddleAreaCD', 'SmallAreaCD'];
		var flg = false;
		for (var i = 0, l = keys.length; i < l; i++) {
			var tmp = $F(e[keys[i]]);
			if (tmp) {
				q[keys[i]] = tmp;
				flg = true;
			}
		}

		if (flg) this._search.search(q);
		else window.alert('少なくとも１つの条件を入れてください。');
	},
	/** ジャンル一覧・料理名一覧・大エリア一覧を読み込む */
	loadDefault: function() {
		var self = this;
		var e = this._form.elements;
		var fetch = function(parent, tagName) { return parent.getElementsByTagName(tagName)[0].firstChild.nodeValue; };
		this._api.send('/Genre/V110/', {}, function(xhr) {
			var items = {};
			var elm = xhr.responseXML.getElementsByTagName('Genre');
			for (var i = 0, l = elm.length; i < l; i++)
				items[fetch(elm[i], 'GenreCD')] = fetch(elm[i], 'GenreName');
			self.refreshOptions(e.GenreCD, items);
		});
		this._api.send('/Food/V110/', {}, function(xhr) {
			var items = {};
			var elm = xhr.responseXML.getElementsByTagName('Food');
			for (var i = 0, l = elm.length; i < l; i++)
				items[fetch(elm[i], 'FoodCD')] = fetch(elm[i], 'FoodName');
			self.refreshOptions(e.FoodCD, items);
		});
		this._api.send('/LargeArea/V110/', {}, function(xhr) {
			var items = {};
			var elm = xhr.responseXML.getElementsByTagName('LargeArea');
			for (var i = 0, l = elm.length; i < l; i++)
				items[fetch(elm[i], 'LargeAreaCD')] = fetch(elm[i], 'LargeAreaName');
			self.refreshOptions(e.LargeAreaCD, items);
		});
	},
	/** 大エリアのコードを元に中エリアをロードする */
	loadMiddleArea: function(largeAreaCD) {
		var self = this;
		var e = this._form.elements;
		var fetch = function(parent, tagName) { return parent.getElementsByTagName(tagName)[0].firstChild.nodeValue; };

		if (largeAreaCD == '') {
			e.MiddleAreaCD._largeAreaCD = '';
			self.refreshOptions(e.MiddleAreaCD, {});
			self.refreshOptions(e.SmallAreaCD, {});
			return;
		}

		e.MiddleAreaCD._largeAreaCD = largeAreaCD;
		this._api.send('/MiddleArea/V110/', {LargeAreaCD: largeAreaCD}, function(xhr) {
			var items = {};
			var elm = xhr.responseXML.getElementsByTagName('MiddleArea');
			for (var i = 0, l = elm.length; i < l; i++)
				items[fetch(elm[i], 'MiddleAreaCD')] = fetch(elm[i], 'MiddleAreaName');
			self.refreshOptions(e.MiddleAreaCD, items);
			self.refreshOptions(e.SmallAreaCD, {});
		});
	},
	/** 大エリアと中エリアのコードを元に小エリアをロードする */
	loadSmallArea: function(largeAreaCD, middleAreaCD) {
		var self = this;
		var e = this._form.elements;
		var fetch = function(parent, tagName) { return parent.getElementsByTagName(tagName)[0].firstChild.nodeValue; };

		if (middleAreaCD == '') {
			self.refreshOptions(e.SmallAreaCD, {});
			return;
		}

		this._api.send('/SmallArea/V110/', {LargeAreaCD: largeAreaCD, MiddleAreaCD: middleAreaCD}, function(xhr) {
			var items = {};
			var elm = xhr.responseXML.getElementsByTagName('SmallArea');
			for (var i = 0, l = elm.length; i < l; i++)
				items[fetch(elm[i], 'SmallAreaCD')] = fetch(elm[i], 'SmallAreaName');
			self.refreshOptions(e.SmallAreaCD, items);
		});
	},
	/**
	 * select要素の中身を入れ替える
	 * @param element target 対象のselect要素
	 * @param object items {送信内容: 表示文字列}の形式のオブジェクト
	 */
	refreshOptions: function(target, items) {
		var o = target.options;

		var i = o.length = 0;
		o[i++] = new Option('未指定', '');

		for (var e in items)
			if (items.hasOwnProperty(e)) o[i++] = new Option(items[e], e);

		target.selectedIndex = 0;
	}
};
