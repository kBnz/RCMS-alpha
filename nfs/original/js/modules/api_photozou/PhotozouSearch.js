/** 1ページあたりの件数 */
var PER_PAGE = 20;

/** リクエストを送信するクラス */
var PhotozouApi = Class.create();
PhotozouApi.prototype = {
    /** APIを使用するために割り当てられたキー */
    user_id: '',
    /** APIのリクエスト先URL */
    base: '',
    /** APIキーとリクエスト先URLを受け取る */
    initialize: function(user_id, base) {
        this.user_id = user_id;
        this.base = base;
    },
    /**
     * リクエストを送信する
     * @param string target APIの種類(/ver1/RestSearchAPI/等)
     * @param object query {キー: 値}の形式の検索クエリ
     * @param function handler レスポンスを処理する関数
     */
    send: function(target, query, handler) {
        query.user_id = this.user_id;
        query.type = 'album';
        var url = this.base + encodeURIComponent(target + '?' + $H(query).toQueryString());
        new Ajax.Request(url, { onComplete: handler });
    }
};

/** 検索処理を担当するクラス */
var PhotozouSearch = Class.create();
PhotozouSearch.prototype = {
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

        if (($H(query).toQueryString()).indexOf("album_id")>=0){
            //検索クエリをlocation.hashに保存する
            location.hash = encodeURIComponent($H(query).toQueryString());

            this._api.send('/rest/photo_list_public', query, this._handler);
        }else{
            $('agResultViewer').innerHTML = 'アルバムを選択してください。';
        }

    },
    /** 最後に検索したクエリでもう一度検索する */
    restore: function() {
        var query = this._loadQuery();
        if (query) this.search(query);
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

        return function(xhr) {

            var result = '';
            try {
                var xml = xhr.responseXML;
                if (xml.getElementsByTagName("rsp")[0].getAttribute("stat")=="ok") {

                    //検索結果
                    var restaurants = xml.getElementsByTagName('photo');
                    var buffer = '';
                    for (var i = 0, l = restaurants.length; i < l; i++) {
                        var rest = restaurants[i];
                        buffer += '<div style="float:left;margin:5px;"><a href="/management/api_photozou/api_photozou_edit/?photo_id=' + fetch(rest, 'photo_id') + '"><img src="' + fetch(rest, 'thumbnail_image_url') + '" alt="' + fetch(rest, 'photo_title') + '"></a></div>\n';
                    }

                    result = buffer + '<div class="clearFloat"></div>';
                } else
                    result = xml.getElementsByTagName("rsp")[0].getAttribute("msg") || 'Error: 検索に失敗しました。';
            } catch (e) {
                result = '検索に失敗しました。';
            }

            $('agResultViewer').innerHTML = result;
        };
    }
};

/** 検索フォームの動作を担当するクラス */
var PhotozouForm = Class.create();
PhotozouForm.prototype = {
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
        var q = {};

        var keys = ['album_id'];
        for (var tmp, i = 0, l = keys.length; i < l; i++) {
            if (tmp = $F(e[keys[i]])) q[keys[i]] = tmp;
        }

        this._search.search(q);
    },
    /** アルバム情報を読み込む */
    loadDefault: function() {
        var self = this;
        var e = this._form.elements;
        var fetch = function(parent, tagName) { return parent.getElementsByTagName(tagName)[0].firstChild.nodeValue; };
        this._api.send('/rest/photo_album', {}, function(xhr) {
            var items    = {};
            var html_str = "";
            var elm = xhr.responseXML.getElementsByTagName('album');
            for (var i = 0, l = elm.length; i < l; i++){
                if((fetch(elm[i], 'perm_type')=='allow')&&(fetch(elm[i], 'perm_type2')=='net')){
                    html_str += '<input type="checkbox" name="album_id[]" value="'+fetch(elm[i], 'album_id')+ '"> '+fetch(elm[i], 'name');
                }
            }
            j$("#check_box").html(html_str);
        });
    },
};
