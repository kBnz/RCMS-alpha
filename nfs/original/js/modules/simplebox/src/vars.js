//スコープ内で使用可能な変数

/** const */
var COOKIE_NAME = 'Simplebox';

/** const */
var DEFAULT_Z_INDEX = 100;

/** 付箋モジュールがインストールされてればtrueがセットされる */
var isInstalled = null;

/** ログインユーザーであればtrueがセットされる */
var isLoggedIn = null;

/** 付箋の新規作成が認められていればtrueがセットされる */
var canInsert = null;

/** 付箋スタイルがセットされる(Hashオブジェクト) */
var styles = null;

/** load関数によってサーバーから取得されたデータがセットされる */
var data = null;

/** 付箋を全て格納するdiv要素 */
var box = div({id: 'Simplebox'});

/** zIndexの最大値を保持する変数 */
var maxZ = DEFAULT_Z_INDEX;
