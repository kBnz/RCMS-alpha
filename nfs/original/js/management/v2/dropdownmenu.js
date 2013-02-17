$(function(){
 
    //グローバルナビ（メインメニュー）クリック時
    $("ul.dropdownMenu li").click(function(event){
       if ($('ul:first',this).css('visibility') == "hidden") {
          $('ul:first',this).css('visibility', 'visible');
          $(this).addClass("dropdownMenuFocus");
       } else {
          $('ul:first',this).css('visibility', 'hidden');
          $(this).removeClass("dropdownMenuFocus");
       }
       var currentList = this.id;
       $('ul.dropdownMenu li:[id^=menu]').each(function(){
          if (this.id !== currentList) {
             if ($('ul:first',this).css('visibility') == "visible") {
                $('ul:first',this).css('visibility', 'hidden');
             }
             $(this).removeClass("dropdownMenuFocus");
          }
       });
       event.stopPropagation()
    });
 
    //サブメニュークリック時
    $("ul.dropdownSubMenu li").click(function(event){
       event.stopPropagation()
    });
 
    //ウインドウクリック時
    $(window).click(function(){
       $('ul.dropdownMenu li:[id^=menu]').each(function(){
           $(this).removeClass("dropdownMenuFocus");
       });
       $("ul.dropdownMenu li ul").each(function(){
           if ($(this).css('visibility') == 'visible') {
              $(this).css('visibility','hidden');
           }
       });
    });
 
    $('ul.dropdownMenu li:[id^=menu]').each(function(){
       $('ul:first',this).children().each(function(){
          $(this).addClass("dropdownSubMenuFocus");
       });
    });
 
});