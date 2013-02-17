//ユーティリティ関数
/** Ajax.Requestの第一引数を一部固定したもの */
function r(action, opt) {
	return new Ajax.Request('/direct.php?mt=simplebox&ct=ajax&action=' + action + '&d=' + new Date() % 100, opt);
}

/** div要素を生成する */
function div(ext, style) {
	return Object.extend($(document.createElement('div')), ext || {}).setStyle(style || {});
}

//スコープ終了
})();