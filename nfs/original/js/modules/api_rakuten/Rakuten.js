/** 楽天市場検索 */
(function() {
//Global APIs
var Rakuten = window.Rakuten = {};

/** 詳細ページへのパス (テンプレートで指定) */
Rakuten.pathToDetails = '/id=';

/** リクエストを送信するクラス */
Rakuten.Api = Class.create();
Object.extend(Rakuten.Api.prototype, {
	/** APIのリクエスト先URL */
	_base: '',
	/** リクエスト先URLを受け取る */
	initialize: function(base) { this._base = base; },
	/**
	 * リクエストを送信する
	 * @param string target APIの種類(/rws/1.11/等)
	 * @param object query {キー: 値}の形式の検索クエリ
	 * @param function handler レスポンスを処理する関数
	 */
	send: function(target, query, handler) {
		var url = this._base + encodeURIComponent(target + 'json?' + $H(query).toQueryString());
		new Ajax.Request(url, { onComplete: handler });
	}
});

/** 検索処理を担当するクラス */
Rakuten.Search = Class.create();
Object.extend(Rakuten.Search.prototype, {
	/** @var Rakuten.Api APIクラスを保持 */
	_api: null,
	/** @var function 検索結果を表示する関数 */
	_handler: null,
	/** APIクラスを受け取り、_handlerを生成する */
	initialize: function(api) {
		this._api = api;
		this._handler = this._createHandler();
	},
	/** 商品を検索する */
	search: function(query) {
		$('arResults').innerHTML = 'Now Loading...';

		//検索クエリをlocation.hashに保存する
		location.hash = encodeURIComponent($H(query).toQueryString());

		query.version = '2007-10-25';
		query.operation = 'ItemSearch';

		this._api.send('/rws/1.11/', query, this._handler);
	},
	/** ジャンルを検索する */
	searchGenre: function(genreId, handler) {
		var query = {
			version: '2007-04-11',
			operation: 'GenreSearch',
			genreId: genreId
		};
		this._api.send('/rws/1.11/', query, handler);
	},
	/** 指定したページに移動する */
	moveTo: function(page) {
		var query = this._loadQuery();
		if (query) {
			query.page = page;
			this.search(query);
		}
	},
	/** 最後に検索したクエリでもう一度検索する */
	restore: function() {
		var query = this._loadQuery();
		if (query) this.search(query);
	},
	/** location.hashに保存した検索クエリを読み出す */
	_loadQuery: function() {
		var stored = decodeURIComponent(location.hash.substr(1));
		return (stored.indexOf('=') < 0) ? false : stored.toQueryParams();
	},
	/** 検索結果を表示する関数を生成する */
	_createHandler: function() {
		var viewer = $('arResults');
		var Pages = function(current, navi) { this._current = current; this._pages = [navi]; };
		Pages.prototype = {
			toString: function() { return this._pages.join('&nbsp;&nbsp;'); },
			add: function(id, label) {
				if (id != this._current)
					label = elm('a', label, {href: 'javascript:moveTo(' + id + ')'});
				this._pages.push(label);
			}
		};
		return function(xhr) {
			try {
				var obj = eval('(' + xhr.responseText + ')'), s = obj.Body.ItemSearch;

				//page navigator
				var current = s.page, last = s.pageCount;
				var pages = new Pages(current, s.count + '件中 ' + s.first + '件 - ' + s.last + '件を表示');

				if (last > 1) {
					if (current > 1) {
						pages.add(1, '&lt;&lt;先頭');
						pages.add(current - 1, '&lt;前へ');
					}
					if (current < last) {
						pages.add(current + 1, '次へ&gt;');
						pages.add(last, '最後&gt;&gt;');
					}
					var tmp = Math.floor((current - 1) / 10) * 10;
					var start = Math.max(tmp, 1), end = Math.min(tmp + 12, last + 1);
					for (var i = start; i < end; i++) pages.add(i, i);
				}
				var navi = elm('tr', elm('th', pages.toString(), {colspan: 3}));

				//検索結果
				var results = '';
				if (s.Items) {
					s.Items.Item.each(function(item) {
						results += elm('tr',
							elm('th', elm('a', item.itemName, {href: Rakuten.pathToDetails + item.itemCode}))
							+ elm('td',
								elm('p', elm('img', '', {src: item.smallImageUrl}))
								+ elm('p', item.itemPrice + '円')
								+ elm('p', elm('a', item.shopName, {href: item.shopUrl})),
								{style: 'text-align:center'}
							)
							+ elm('td', item.itemCaption.substr(0, 200) + '...')
						);
					});
				}

				viewer.innerHTML = elm('table',
					elm('thead', navi) + elm('tbody', results) + elm('tfoot', navi)
				);
			} catch (e) {
				viewer.innerHTML = elm('p', '検索に失敗しました。');
			}
		};
	}
});

/** 検索フォームの動作を担当するクラス */
Rakuten.Form = Class.create();
Object.extend(Rakuten.Form.prototype, {
	/** @var Rakuten.Search 検索処理クラス */
	_search: null,
	/** @var HTMLFormElement フォーム要素 */
	_form: null,
	/** 検索処理クラスとフォーム要素を受け取る */
	initialize: function(search, form) {
		this._search = search;
		this._form = $(form);

		//トップレベルジャンルを読み出す
		this._form.genreId.value = 0;
		this.loadGenre(0);
	},
	/** onsubmit handler */
	submit: function() {
		var e = this._form.elements;
		var q = {};

		if (($F(e['keyword']) != '') || ($F(e['genreId']) > 0)) {
			var keys = ['keyword', 'orFlag', 'hits', 'genreId', 'minPrice', 'maxPrice', 'sort', 'shopCode'];
			for (var i = 0, l = keys.length, tmp; i < l; i++)
				if (tmp = $F(e[keys[i]])) q[keys[i]] = tmp;

			this._search.search(q);
		} else window.alert('キーワードもしくはジャンルは必ず指定してください。');
	},
	/**
	 * ジャンル選択時の処理
	 * @param int level genreLevel
	 */
	loadGenre: function(level) {
		level = parseInt(Math.abs(level)) || 0;

		//より下級のselect要素を削除する
		var pf = 'arGenreLevel', parent = $('arGenre');
		for (var i = level, node; node = $(pf + ++i);)
			parent.removeChild(node);

		//選択されたジャンルによる操作
		var val = $F(pf + level), id = val;
		if (val.match(/^none:(\d+)$/)) {	//未指定が選ばれた場合
			this._form.genreId.value = RegExp.$1;
		} else {
			this._form.genreId.value = val;

			var self = this, onChange = function() { self.loadGenre(level + 1); };
			this._search.searchGenre(val, function(xhr) {
				var obj = eval('(' + xhr.responseText + ')');
				if (!obj.Body.GenreSearch.child.length) return;

				var select = document.createElement('select');
				select.id = pf + (level + 1);
				select.onchange = onChange;
				select.options[0] = new Option('未指定', 'none:' + val);
				select.style.width = '90%';
				select.style.display = 'block';

				var opt = select.options, child = obj.Body.GenreSearch.child;
				for (var i = 0, l = child.length; i < l; i++)
					opt[opt.length] = new Option(child[i].genreName, child[i].genreId);

				parent.appendChild(select);
			});
		}
	}
});

//util
/** HTML要素文字列を生成する */
function elm(name, value, attr) {
	var buff = name, value = (value == null) ? '' : value;
	for (var p in attr) buff += ' ' + p + '="' + attr[p] + '"';
	return '<' + buff + (value ? '>' + value + '</' + name : '/') + '>';
}

})();
