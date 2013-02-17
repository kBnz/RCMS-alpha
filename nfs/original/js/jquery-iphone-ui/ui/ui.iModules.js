/**
 * Change style for menu
 *
 * @author Anton Shevchuk (http://anton.shevchuk.name)
 * @copyright (c) 2009 jQuery iPhone UI (http://iphone.hohli.com)
 * @license   Dual licensed under the MIT (MIT-LICENSE.txt) and GPL (GPL-LICENSE.txt) licenses.
 * 
 * @version 0.1
 */
(function($){
    $.widget('ui.iModules', {
        _init: function() {
			var $this = this.element;
            	$this.addClass('iphoneui')
					 .addClass('imenuui');

            $this.hover(function(){
                $(this).addClass('active');
            }, function(){
                $(this).removeClass('active');
            });	

            $this.click(function(){
                $this.next().slideToggle();
            });

            
        }
    });
})(jQuery);