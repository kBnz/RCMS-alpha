// îñÇ¢ï∂éöÇèoÇ∑
(function(j$) {
    j$.fn.inputPrompt = function(){
        var argv = arguments.length ? arguments[0] : {};
        var classPrompt = (classPrompt in argv) ? argv.classPrompt : 'placeholder';
        return this.each(function(){
          var e = j$(this);
          if(e.val() == '' || e.val() == e.attr('title')){
            e.val(e.attr('title')).addClass(classPrompt);
          }else if(e.val() != '' && e.val() != e.attr('title')){
            e.removeClass(classPrompt);
          }
          e.blur(function(){
              if(e.val() == '' || e.val() == e.attr('title')){
                e.val(e.attr("title")).addClass(classPrompt);
              }
            })
            .focus(function(){
              if(e.val() == e.attr('title')){
                e.val('').removeClass(classPrompt);
              }else if(e.val() != '' && e.val() != e.attr('title')){
                e.removeClass(classPrompt);
              }
            })
            .parents("form").submit(function(){
              if(e.val() == '' || e.val() == e.attr('title')){
                e.val('').removeClass(classPrompt);
              }
            })
          ;
        });
    }
})(jQuery);