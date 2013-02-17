var YouTube = {
    /** 検索フォームオブジェクト */
    _form: null,
    /** 結果を表示するdiv要素オブジェクト */
    _viewer: null,
    /** 検索スクリプトへのパス */
    pathToSearcher: '',
    /** 初期化処理 */
    init: function(form, viewer) {
        this._form   = $(form);
        this._viewer = $(viewer);
        this.restore();
    },
    /** 最後に検索したクエリで再検索する */
    restore: function() {
        var query = this._loadQuery();
        if (query) {
            this.search(query);
        }
    },
    /** 検索フォームのonsubmitで実行される */
    submit: function() {
        var query = {};
        $A(this._form.elements).each(function(e) {
            e.name && (query[e.name] = $F(e));
        });
        this.search(query);
        return false;   //submitアクションを停止するため
    },
    /** 検索する */
    search: function(query) {
        this._viewer.innerHTML = 'Now Loading...';
        var serialized = $H(query).toQueryString();

        //検索クエリをlocation.hashに保存する
        location.hash = encodeURIComponent(serialized);

        //検索
        new Ajax.Updater(this._viewer, this.pathToSearcher, {
            method: 'get', parameters: { query: serialized }
        });
    },
    /** 指定したページにジャンプする */
    moveTo: function(page) {
        var query = this._loadQuery();
        if (query) {
            query.startIndex = 1 + --page * (query.maxResults || 10);
            this.search(query);
        }
    },
    /** location.hashに保存した検索クエリを読み出す */
    _loadQuery: function() {
        var stored = decodeURIComponent(location.hash.substr(1));
        return (stored.indexOf('=') < 0) ? false : stored.toQueryParams();
    },
    /** DBに保存する */
    capture: function(a, id, name) {
        a.onclick   = null;
        a.innerHTML = '...';
        new Ajax.Request('/management/api_youtube/api_youtube_capture/', {
            parameters: $H({"external_id": id, "name": name}),
            onSuccess: function(xhr) {
                if (xhr.responseText == 'true') {
                    a.innerHTML = 'Done!';
                }
            }
        });
    }
};
