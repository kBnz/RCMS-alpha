//ajaxでデータをロードする
/**
 * サーバーからデータをロードし、dataにセットする
 * @param function callback dataにセットした後に呼ばれる関数
 */
var load = function(callback) {
	r('data', {
		method: 'get',
		parameters: $H({path: location.pathname, query: location.search.substr(1)}),
		onComplete: function(x) {
			try {
				data = eval(x.responseText);
				isInstalled = data.shift();
				isLoggedIn = data.shift();
				canInsert = data.shift();
				styles = $H(data.shift());
			} catch (e) {
				isInstalled = isLoggedIn = canInsert = false;
				data = styles = null;
			}
			if (callback) callback();
		}
	});
};