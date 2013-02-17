/** 1ページあたりの件数 */
var PER_PAGE = 20;

/** リクエストを送信するクラス */
var GnaviApi = Class.create();
GnaviApi.prototype = {
	/** APIのリクエスト先URL */
	base: '',
	/** APIキーとリクエスト先URLを受け取る */
	initialize: function(base) {
		this.base = base;
	},
	/**
	 * リクエストを送信する
	 * @param string target APIの種類(/ver1/RestSearchAPI/等)
	 * @param object query {キー: 値}の形式の検索クエリ
	 * @param function handler レスポンスを処理する関数
	 */
	send: function(target, query, handler) {
		var url = this.base + encodeURIComponent(target + '?' + $H(query).toQueryString());
		new Ajax.Request(url, { onComplete: handler });
	}
};

/** 検索処理を担当するクラス */
var GnaviSearch = Class.create();
GnaviSearch.prototype = {
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
		$('agResultViewer').innerHTML = 'Now loading...';

		//検索クエリをlocation.hashに保存する
		location.hash = encodeURIComponent($H(query).toQueryString());

		this._api.send('/ver1/RestSearchAPI/', query, this._handler);

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
			query.offset_page = page;
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
		var fetch = function(parent, tagName) {
			try {
				return parent.getElementsByTagName(tagName)[0].firstChild.nodeValue;
			} catch (e) {
				return '';
			}
		};
		var pages_add = function(id, label) { this.push('<a href="javascript:moveTo(' + id + ')">' + label + '</a>'); };
		var errorCodes = {
			600: '該当の店舗は存在しません。',
			601: '不正アクセスです。',
			602: '不正なぐるなび店舗IDが指定されました。',
			603: '条件の指定が不正です。',
			604: '処理中にエラーが発生しました。'
		};

		return function(xhr) {
			var result = '';
			try {
				var xml = xhr.responseXML;
				if (xml.documentElement.tagName.match(/response/i)) {
					var num = parseInt(fetch(xml, 'total_hit_count'));
					var page = parseInt(fetch(xml, 'page_offset'));
					var from = 1 + (PER_PAGE * (page - 1));

					//ページ案内
					var navi = num + '件中 ' + from + ' - ' + Math.min(num, from + PER_PAGE -1) + '件を表示';
					
					var pages = [];
					var last = Math.ceil(num / PER_PAGE);
					
					if (last > 1) {
						pages.add = pages_add;

						if (page > 1) pages.add(page - 1, '&lt;前へ');
						if (page < last) pages.add(page + 1, '次へ&gt;');

						var start = Math.floor((page - 1) / 10) * 10 + 1;
						var end = Math.min(start + 10, last + 1);
						for (var i = start; i < end; i++) {
							if (i == page) pages.push(i);
							else pages.add(i, i);
						}
					}
					
					navi = '<p>' + navi + '&nbsp;&nbsp;' + pages.join('&nbsp;&nbsp;') + '</p>';

					//検索結果
					var restaurants = xml.getElementsByTagName('rest');
					var buffer = '<tr><th>店名</th><th>PR</th><th>アクセス</th><th>営業時間</th></tr>';
					for (var i = 0, l = restaurants.length; i < l; i++) {
						var rest = restaurants[i];
						buffer += '<tr>\n\t<td style="font-weight:bold"><a href="/management/?mt=api_gnavi&ct=api_gnavi_edit&restaurant_id=' + fetch(rest, 'id') + '">' + fetch(rest, 'name') + '</a></td>\n\t<td>' + fetch(rest, 'pr_short') + '</td>\n\t<td>' + fetch(rest, 'station') + fetch(rest, 'station_exit') + 'から' + fetch(rest, 'walk') + '分</td>\n\t<td>' + fetch(rest, 'opentime') + '</td>\n</tr>';
					}

					result = navi + '<table>' + buffer + '</table>' + navi;
				} else
					result = errorCodes[fetch(xml, 'code')] || 'Error: 検索に失敗しました。';
			} catch (e) {
				result = '検索に失敗しました。';
			}

			$('agResultViewer').innerHTML = result;
		};
	}
};

/** 検索フォームの動作を担当するクラス */
var GnaviForm = Class.create();
GnaviForm.prototype = {
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
		var q = {sort: 2, hit_per_page: PER_PAGE};

		var keys = ['name', 'name_kana', 'pref', 'address', 'category_l', 'category_s'];
		for (var tmp, i = 0, l = keys.length; i < l; i++) {
			if (tmp = $F(e[keys[i]])) q[keys[i]] = tmp;
		}

		this._search.search(q);
	},
	/** 都道府県マスタ・大業態マスタを読み込む */
	loadDefault: function() {
		var self = this;
		var e = this._form.elements;
		var fetch = function(parent, tagName) { return parent.getElementsByTagName(tagName)[0].firstChild.nodeValue; };
		this._api.send('/ver1/PrefSearchAPI/', {}, function(xhr) {
			var items = {};
			var elm = xhr.responseXML.getElementsByTagName('pref');
			for (var i = 0, l = elm.length; i < l; i++)
				items[fetch(elm[i], 'pref_code')] = fetch(elm[i], 'pref_name');
			self.refreshOptions(e.pref, items);
		});
		this._api.send('/ver1/CategoryLargeSearchAPI/', {}, function(xhr) {
			var items = {};
			var elm = xhr.responseXML.getElementsByTagName('category_l');
			for (var i = 0, l = elm.length; i < l; i++)
				items[fetch(elm[i], 'category_l_code')] = fetch(elm[i], 'category_l_name');
			self.refreshOptions(e.category_l, items);
		});
	},
	/** 大業態のコードを元に小業態をロードする */
	loadSmallCategories: function(largeCategoryCode) {
		var self = this;
		var e = this._form.elements;
		var fetch = function(parent, tagName) { return parent.getElementsByTagName(tagName)[0].firstChild.nodeValue; };

		this._api.send('/ver1/CategorySmallSearchAPI/', {}, function(xhr) {
			var items = {};
			var elm = xhr.responseXML.getElementsByTagName('category_s');
			for (var i = 0, l = elm.length; i < l; i++)
				if (fetch(elm[i], 'category_l_code') == largeCategoryCode)
					items[fetch(elm[i], 'category_s_code')] = fetch(elm[i], 'category_s_name');
			self.refreshOptions(e.category_s, items);
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
