$(function(){
 
    //�O���[�o���i�r�i���C�����j���[�j�N���b�N��
    $("ul.globalmenu li").click(function(event){
       if ($('ul:first',this).css('visibility') == "hidden") {
          $('ul:first',this).css('visibility', 'visible');
          $(this).addClass("globalmenu_focus");
       } else {
          $('ul:first',this).css('visibility', 'hidden');
          $(this).removeClass("globalmenu_focus");
       }
       var currentList = this.id;
       $('ul.globalmenu li:[id^=g_menu]').each(function(){
          if (this.id !== currentList) {
             if ($('ul:first',this).css('visibility') == "visible") {
                $('ul:first',this).css('visibility', 'hidden');
             }
             $(this).removeClass("globalmenu_focus");
          }
       });
       event.stopPropagation()
    });
 
    //�T�u���j���[�N���b�N��
    $("ul.globalmenu_sub li").click(function(event){
       event.stopPropagation()
    });
 
    //�E�C���h�E�N���b�N��
    $(window).click(function(){
       $('ul.globalmenu li:[id^=g_menu]').each(function(){
           $(this).removeClass("globalmenu_focus");
       });
       $("ul.globalmenu li ul").each(function(){
           if ($(this).css('visibility') == 'visible') {
              $(this).css('visibility','hidden');
           }
       });
    });
 
    $('ul.globalmenu li:[id^=menu]').each(function(){
       $('ul:first',this).children().each(function(){
          $(this).addClass("globalmenu_subfocus");
       });
    });
 
});