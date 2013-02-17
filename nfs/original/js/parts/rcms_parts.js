/** IFRAME要素を吐き出し、モジュールを単独で読み出す
 * @author 高澤 */
(function(a){
	if (typeof a.php != 'number') { document.write('Error');	return; }
	if (typeof a.tpl != 'number') { document.write('Error');	return; }
	var p = {
		php:    parseInt(a.php),
		tpl:    parseInt(a.tpl),
		width:  parseInt(a.width)  || 200,
		height: parseInt(a.height) || 400,
		style:  String(a.style),
		color0: String(a.color0.match(/[0-9a-f]{6}/i) || ''),
		color1: String(a.color1.match(/[0-9a-f]{6}/i) || ''),
		color2: String(a.color2.match(/[0-9a-f]{6}/i) || ''),
		color3: String(a.color3.match(/[0-9a-f]{6}/i) || '')
	}
	
	var get = [];
	for (var k in p) {
		get.push(k + '=' + p[k]);
	}
	if (a.param != '') get.push(a.param);

	var url = a.base + '/direct.php?mt=parts&ct=display&' + get.join('&');
	var tag = '<iframe src="'+url+'" width="'+p.width+'" height="'+p.height+'" frameborder="0" style="margin:0px;border-style:none;padding:0px;">IFRAME対応のブラウザでご覧ください。</iframe>';
	//tag = '<object style="margin:0px;border-style:none;padding:0px;" data="'+url+'" type="text/html" height="'+p.height+'" width="'+p.width+'">'+tag+'</object>'
	document.write(tag);
})(rcms_parts);