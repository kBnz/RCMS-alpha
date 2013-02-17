//レイアウト支援
$(function(){
	
	//サブメニューをリンクエリアに
	$("#selectDvice ul.sub li").click(function() {
		window.location=$(this).find("a").attr("href");
		return false;
	});
	
	$("#selectDvice ul.sub li").hover(function(){
		$(this).addClass("box_hover"); 
	},function(){
		$(this).removeClass("box_hover"); 
	});

	

	
	//下層メニュー
	$(".sub").css("display","none");
	
	$("#selectDvice li").hover(function(){
		$(">ul:not(:animated)",this).show();
	},function(){
		
		$(">ul",this).hide(0,function(){
			$(".sub").css("display","none");
		});
	});
	

	
});





//ポップアップウィンドウ
function gene_window(mypage, myname, w, h, scroll) {
	var win_width = (screen.width - w) / 2;
	var win_height = (screen.height - h) / 2;
	win_detail = 'height='+h+',width='+w+',top='+win_height+',left='+win_width+',scrollbars='+scroll;
	win = window.open(mypage, myname, win_detail)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}







