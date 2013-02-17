/*
 * prototype
 * Copyright(c) 2006, Jack Slocum.
 * 
 * This code is licensed under BSD license. Use it as you wish, 
 * but keep this copyright intact.
 */


var Scriptaculous={Version:'1.7.0',require:function(libraryName){document.write('<script type="text/javascript" src="'+libraryName+'"></script>');},load:function(){if((typeof Prototype=='undefined')||(typeof Element=='undefined')||(typeof Element.Methods=='undefined')||parseFloat(Prototype.Version.split(".")[0]+"."+
Prototype.Version.split(".")[1])<1.5)
throw("script.aculo.us requires the Prototype JavaScript framework >= 1.5.0");$A(document.getElementsByTagName("script")).findAll(function(s){return(s.src&&s.src.match(/scriptaculous\.js(\?.*)?$/))}).each(function(s){var path=s.src.replace(/scriptaculous\.js(\?.*)?$/,'');var includes=s.src.match(/\?.*load=([a-z,]*)/);(includes?includes[1]:'builder,effects,dragdrop,controls,slider').split(',').each(function(include){Scriptaculous.require(path+include+'.js')});});}}
Scriptaculous.load();
