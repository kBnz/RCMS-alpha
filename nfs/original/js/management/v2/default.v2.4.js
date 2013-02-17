/*
 * management_default
 * Copyright(c) 2006, Jack Slocum.
 * 
 * This code is licensed under BSD license. Use it as you wish, 
 * but keep this copyright intact.
 */

/*@cc_on _d=document;eval('var document=_d')@*/

function getCookie(name){return Cookie.get(name);}
function setCookie(name,value){Cookie.expireInMonth(name,value);}
var Cookie={get:function(name){var match=('; '+document.cookie+';').match('; '+encodeURIComponent(name)+'=(.*?);');return match?decodeURIComponent(match[1]):'';},set:function(name,value,expires,path,domain,secure){var buffer=encodeURIComponent(name)+'='+encodeURIComponent(value);if(typeof expires!='undefined')buffer+='; expires='+new Date(expires).toUTCString();if(typeof path!='undefined')buffer+='; path='+path;if(typeof domain!='undefined')buffer+='; domain='+domain;if(secure)buffer+='; secure';document.cookie=buffer;},expireInMonth:function(name,value){this.set(name,value,(new Date()).getTime()+1000*60*60*24*30,'/');}};function display(obj){$(obj).toggle();}
function openWindow(url){location.href=url;}
function AllChecked(check,form,ele){if(!form)return;var cb=form.elements[ele];if(!cb)return;if(!cb.length){cb=[cb];}
for(var i=0;i<cb.length;i++){if(!cb[i].disabled){cb[i].checked=check;}}}
function changeHankaku(elm,mode){if(elm.value=='')return;var value=elm.value.replace(/[／-：]/g,function(s){return String.fromCharCode(s.charCodeAt(0)-65248);}).replace(/[ー？―‐]/g,'-');var test=function(test,message){if(test)elm.value=value;else window.alert(message);};switch(mode){case'number':test(!isNaN(value),'半角数字で入力してください。');break;case'date':test(value.match(/^\d{4}(\/\d{1,2}){2}$/),'｢/｣区切りの半角数字で入力してください。\n例:1970/01/01');break;case'time':test(value.match(/^\d{1,2}(:\d{1,2}){1,2}$/),'｢:｣区切りの半角数字で入力してください。\n例:09:00');break;case'tel':test(value.match(/^[\d-]+$/),'｢-｣区切りの半角数字で入力してください。\n例:00-0000-0000');break;}}
function getModuleData(module_type,contents_type,data_kbn,callback,args){var pars={module_type:module_type,data_kbn:data_kbn};if(contents_type!='')pars.contents_type=contents_type;new Ajax.Request('/js/getModuleData.php',{method:'get',parameters:$H(pars),onComplete:function(x){callback(eval(x.responseText),args);}});}
var DelayDoFunction = function(t, func){var timer = setInterval(function() {var tPs = j$(t).offset().top;var bSc = j$(this).scrollTop() + j$(window).height();if(bSc > tPs && j$(t).css('display') != 'none') {clearInterval(timer);func();}}, 500);}
