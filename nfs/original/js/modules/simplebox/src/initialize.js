//付箋モジュールを初期化する
/** データがロードされるのを待って描画を開始する */
var initialize = onReady.bind(null, function() {
	if (isInstalled === false) return;	//インストールされていなければ停止
	if (data == null)
		setTimeout(initialize, 100);
	else {
		//div#Simpleboxの初期化
		if (box.parentNode) box.remove();
		box = div({id: 'Simplebox'});

		maxZ = DEFAULT_Z_INDEX;
		menu.initialize();
		editor.initialize();
		paint();

		document.body.appendChild(box);
	}
});
