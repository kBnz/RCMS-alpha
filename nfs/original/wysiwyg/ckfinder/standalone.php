<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>CKFinder</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex, nofollow" />
	<script type="text/javascript" src="ckfinder.js"></script>
	<style type="text/css">
		body, html, iframe, #ckfinder {
			margin: 0;
			padding: 0;
			border: 0;
			width: 100%;
			height: 100%;
			overflow: hidden;
		}
	</style>
</head>
<body>
	<div id="ckfinder"></div>
	<script type="text/javascript">
var Cookie={get:function(name){var match=('; '+document.cookie+';').match('; '+encodeURIComponent(name)+'=(.*?);');return match?decodeURIComponent(match[1]):'';},set:function(name,value,expires,path,domain,secure){var buffer=encodeURIComponent(name)+'='+encodeURIComponent(value);if(typeof expires!='undefined')buffer+='; expires='+new Date(expires).toUTCString();if(typeof path!='undefined')buffer+='; path='+path;if(typeof domain!='undefined')buffer+='; domain='+domain;if(secure)buffer+='; secure';document.cookie=buffer;},expireInMonth:function(name,value){this.set(name,value,(new Date()).getTime()+1000*60*60*24*30,'/');}};function display(obj){$(obj).toggle();}

(function()
{
		var config = {
		};
		var get = CKFinder.tools.getUrlParam;
		var getBool = function( v )
		{
			var t = get( v );

			if ( t === null )
				return null;

			return t == '0' ? false : true;
		};

		var tmp;
		if ( tmp = get( 'basePath' ) ){
			CKFinder.basePath = tmp;
		}else{
			CKFinder.basePath = './';
		}

		if ( tmp = get( 'startupPath' ) )
			config.startupPath = decodeURIComponent( tmp );

		config.id = get( 'id' ) || '';

		if ( ( tmp = getBool( 'rlf' ) ) !== null )
			config.rememberLastFolder = tmp;

		if ( ( tmp = getBool( 'dts' ) ) !== null )
			config.disableThumbnailSelection = tmp;

		if ( tmp = get( 'data' ) )
			config.selectActionData = tmp;

		if ( tmp = get( 'tdata' ) )
			config.selectThumbnailActionData = tmp;

		if ( tmp = get( 'type' ) )
			config.resourceType = tmp;

		if ( tmp = get( 'skin' ) )
			config.skin = tmp;

		if ( tmp = get( 'langCode' ) )
			config.language = tmp;

		//RCMS特有の引数
		if ( tmp = Cookie.get( '_lang' ) )
			config.language = tmp;

		if ( tmp = get( 'columns' ) )
			config.columns = tmp;

		// Try to get desired "File Select" action from the URL.
		var action;
		if ( tmp = get( 'CKEditor' ) )
		{
			if ( tmp.length )
				action = 'ckeditor';
		}
		if ( !action )
			action = get( 'action' );

		var parentWindow = ( window.parent == window )
			? window.opener : window.parent;

		switch ( action )
		{
			case 'js':
				var actionFunction = get( 'func' );
				if ( actionFunction && actionFunction.length > 0 )
					config.selectActionFunction = parentWindow[ actionFunction ];

				actionFunction = get( 'thumbFunc' );
				if ( actionFunction && actionFunction.length > 0 )
					config.selectThumbnailActionFunction = parentWindow[ actionFunction ];
				break ;

			case 'ckeditor':
				var funcNum = get( 'CKEditorFuncNum' );
				if ( parentWindow['CKEDITOR'] )
				{
					config.selectActionFunction = function( fileUrl, data )
					{
						parentWindow['CKEDITOR'].tools.callFunction( funcNum, fileUrl, data );
					};

					config.selectThumbnailActionFunction = config.selectActionFunction;
				}
				break;

			default:
				if ( parentWindow && parentWindow['FCK'] && parentWindow['SetUrl'] )
				{
					action = 'fckeditor' ;
					config.selectActionFunction = parentWindow['SetUrl'];

					if ( !config.disableThumbnailSelection )
						config.selectThumbnailActionFunction = parentWindow['SetUrl'];
				}
				else if(get( 'column' ))
				{
					config.selectActionFunction = function( fileUrl, data )
					{
<?php if(isset($_REQUEST["column"]) && $_REQUEST["column"]){ ?>
	window.opener.document.getElementById('<?=$_REQUEST["column"]?>').value = fileUrl;
<?php } ?>
					};
				
				}
				else
				{
					action = null ;
				}
		}

		config.action = action;

		// Always use 100% width and height when nested using this middle page.
		config.width = config.height = '100%';
		config.defaultSortBy = 'date';
		config.disableHelpButton = true;
		config.disableThumbnailSelection = true;

		config.extraPlugins = "filepath,pixlreditor";
		config.removePlugins = 'basket';
		var ckfinder = new CKFinder( config );

		ckfinder.replace( 'ckfinder', config );

})();

	</script>
</body>
</html>
