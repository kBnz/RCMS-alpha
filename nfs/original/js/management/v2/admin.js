//レイアウト支援
$(function(){

    //アカウントの左側のラインをつける
    $("nav#account ul li:first-child").addClass("first");
    
    //メインのリスト　ヘッダー下のライン
    $("table.list-table tbody tr:first td").addClass("firstline");
    
    //メインのリスト、偶数列に背景色を追加
    $("table.list-table tbody tr:odd").addClass("odd");
    
    
    //親ボックスをリンクエリアに
    $(".linkbox").click(function() {
        window.location=$(this).find("a").attr("href");
        return false;
    });
    $(".linkbox").hover(function(){
        $(this).addClass("box_hover"); 
    },function(){
        $(this).removeClass("box_hover"); 
    });
    
    //ユーティリティのリストをリンクエリアに
    $("#utility li").click(function() {
        window.location=$(this).find("a").attr("href");
        return false;
    });
    
    $("#utility li").hover(function(){
        $(this).addClass("box_hover"); 
    },function(){
        $(this).removeClass("box_hover"); 
    });
    
    
    //サブメニューをリンクエリアに
    $("#adminmenu ul.sub li").click(function() {
        window.location=$(this).find("a").attr("href");
        return false;
    });
    
    $("#adminmenu ul.sub li").hover(function(){
        $(this).addClass("box_hover"); 
    },function(){
        $(this).removeClass("box_hover"); 
    });
    
    
    
    //サイドバー
    $("#adminmenu").toggle(
        function(){
            $("#adminmenu").stop(true,true).animate({
                width : "180px" ,
            },"fast","swing");
            
            $("#adminmenu>ul>li>a").stop(true,true).animate({
                marginLeft : "42px" ,
            },"fast","swing",function(){
                $("#adminmenu").css("overflow","visible");
            });
        },
        function(){
            $("#adminmenu").css("overflow","hidden");
            $("#adminmenu li ul.sub").css("display","none");
    
            $("#adminmenu>ul>li>a").stop(true,true).animate({
                marginLeft : "50px" ,
            },"fast","swing");
    
            $("#adminmenu").stop(true,true).animate({
                width : "50px" ,
            },"fast","swing");
        }
    );

    //ユーティリティ
    $("#utility").hover(function(){
        $("#utility").stop(true,true).animate({
            width : "480px" ,
            left: "580px",
        },"fast","swing");
        
        $("#utility .switch").stop(true,true).animate({
            width : "478px" ,
        },"fast","swing");
        
        $("#utility>div").stop(true,true).animate({
            width : "476px" ,
        },"fast","swing",function(){
            $("#utility div.hidden").stop(true,true).slideDown("fast");
        });
    },function(){
        $("#utility").stop(true,true).animate({
            width : "240px" ,
            left: "820px",
        },"fast","swing");
        
        $("#utility .switch").stop(true,true).animate({
            width : "238px" ,
        },"fast","swing");
        
        $("#utility>div").stop(true,true).animate({
            width : "236px" ,
        },"fast","swing",function(){
            $("#utility div.hidden").stop(true,true).slideUp("fast");
        });
    });
    
    
    //下層メニュー
    $("#adminmenu li").hover(function(){
        $(">ul",this).show();
    },function(){
        
        $(">ul",this).hide(0,function(){
            $(".sub").css("display","none");
        });
    });
    
    
    

    //エントリーオプション
    $(".entry-option-title").click(function(){
        //親要素のクラスを調べる
        if($(this).parent().attr("class")=="entry-option-open"){
            $("+.entry-option-content",this).slideUp("fast");
            $(this).parent().removeClass("entry-option-open");
        } else {
            $("+.entry-option-content",this).slideDown("fast");
            $(this).parent().addClass("entry-option-open");
        }
    });
    
    
});










