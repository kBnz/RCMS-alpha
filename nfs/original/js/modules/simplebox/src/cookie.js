//非ログインユーザー向けcookie操作を集中
var cookie = {
	show: function(id) {
		id = [].concat(id);	//配列じゃなければ配列化
		var ck = [].without.apply(Cookie.get(COOKIE_NAME).split(','), id).join(',');
		Cookie.expireInMonth(COOKIE_NAME, ck);
	},
	hide: function(id) {
		var ck = Cookie.get(COOKIE_NAME).split(',').concat(id).join(',');
		Cookie.expireInMonth(COOKIE_NAME, ck);
	},
	isHidden: function(id) {
		return !!Cookie.get(COOKIE_NAME).match('(^|,)' + id + '(,|$)');
	}
};