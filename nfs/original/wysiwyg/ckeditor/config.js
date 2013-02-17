/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	//config.docType = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">';
	//config.shiftEnterMode = CKEDITOR.ENTER_BR;
	config.forceEnterMode = true;
	config.enterMode = CKEDITOR.ENTER_BR;
	config.pasteFromWordPromptCleanup = true;
	config.scayt_autoStartup = false;
	config.pasteFromWordNumberedHeadingToList = true;
	//config.pasteFromWordRemoveFontStyles = true;
	//config.pasteFromWordRemoveStyles = true;
	config.forcePasteAsPlainText = false;
	config.image_previewText = 'This space is image preview area.This space is image preview area.';

	//config.plugins = 'about,basicstyles,blockquote,button,clipboard,colorbutton,contextmenu,elementspath,enterkey,entities,filebrowser,find,flash,font,format,horizontalrule,htmldataprocessor,image,indent,justify,keystrokes,link,list,maximize,newpage,pagebreak,pastefromword,pastetext,popup,preview,print,removeformat,resize,save,scayt,smiley,showblocks,sourcearea,stylescombo,table,tabletools,specialchar,tab,templates,toolbar,undo,wysiwygarea,wsc';
	//config.plugins : 'basicstyles,blockquote,button,clipboard,colorbutton,colordialog,contextmenu,elementspath,enterkey,entities,filebrowser,flash,font,format,horizontalrule,htmldataprocessor,image,indent,justify,keystrokes,link,list,maximize,pastefromword,pastetext,popup,removeformat,resize,showblocks,sourcearea,table,tabletools,toolbar,undo,wysiwygarea,wsc',
	//config.removePlugins = 'save,forms,newpage,print,specialchar,tab,pagebreak,stylescombo,preview,scayt,find,undo,about,templates,wsc';
	
	config.extraPlugins = 'iframedialog,youtube,rcmsupload,tableresize,syntaxhighlight';

	config.toolbar = [
    ['Source'],
    ['Undo','Redo','Cut','Copy','Paste','PasteText','PasteFromWord'],
    ['Maximize', 'ShowBlocks'],
    ['SelectAll','RemoveFormat','-','RcmsUpload'],
    ['NumberedList','BulletedList','Outdent','Indent','Blockquote'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
    ['Link','Unlink','Anchor'],
    ['Image','Flash','Table','HorizontalRule','Smiley','Youtube'],
    ['Font','FontSize','Format'],
    ['TextColor','BGColor'],
    ['Bold','Italic','Underline','Strike', 'syntaxhighlight']
	];
	config.width = '720px';
	config.height = '500px';
	config.fontSize_sizes='80%/80%;90%/90%;100%/100%;110%/110%;120%/120%;130%/130%;140%/140%;150%/150%;160%/160%;170%/170%;180%/180%;190%/190%;200%/200%';
	config.font_names='MSゴシック/MS Gothic, Osaka-Mono, monospace; MS Pゴシック/MS PGothic, Osaka, sans-serif; MS UI Gothic/MS UI Gothic, Meiryo, Meiryo UI, Osaka, sans-serif; MS P明朝/MS PMincho, Saimincho, serif; Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS, cursive;Courier New/Courier New, Courier, monospace;Georgia/Georgia, serif;Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Trebuchet MS/Trebuchet MS, Helvetica, sans-serif;Verdana/Verdana, Geneva, sans-serif';
	config.coreStyles_underline = { element : 'ins' };
	config.coreStyles_strike = { element : 'del' };	

	// 絵文字の設定
	config.smiley_columns = 20; //横に絵文字を表示する個数 (2009/12/28 plugin/smiley/dialogs/smiley.js を変更)
	
	config.smiley_path = '/images/modules/mobile/emoji/'; //絵文字へのパス
	
	//絵文字のファイル名
	config.smiley_images = ['001.gif','002.gif','003.gif','004.gif','005.gif','006.gif','007.gif','008.gif','009.gif','010.gif','011.gif','012.gif','013.gif','014.gif','015.gif','016.gif','017.gif','018.gif','019.gif','020.gif','021.gif','022.gif','023.gif','024.gif','025.gif','026.gif','027.gif','028.gif','029.gif','030.gif','031.gif','032.gif','033.gif','034.gif','036.gif','037.gif','038.gif','039.gif','040.gif','041.gif','042.gif','043.gif','044.gif','045.gif','046.gif','047.gif','048.gif','049.gif','050.gif','051.gif','052.gif','053.gif','054.gif','055.gif','056.gif','057.gif','058.gif','059.gif','060.gif','061.gif','062.gif','063.gif','064.gif','065.gif','066.gif','067.gif','068.gif','069.gif','070.gif','071.gif','072.gif','073.gif','074.gif','075.gif','076.gif','077.gif','078.gif','079.gif','080.gif','081.gif','082.gif','083.gif','084.gif','085.gif','086.gif','087.gif','088.gif','089.gif','090.gif','091.gif','092.gif','093.gif','095.gif','096.gif','097.gif','098.gif','099.gif','100.gif','101.gif','102.gif','103.gif','104.gif','105.gif','106.gif','107.gif','110.gif','113.gif','116.gif','119.gif','120.gif','125.gif','126.gif','127.gif','128.gif','129.gif','130.gif','131.gif','132.gif','133.gif','134.gif','135.gif','136.gif','137.gif','138.gif','140.gif','141.gif','142.gif','143.gif','144.gif','145.gif','147.gif','148.gif','149.gif','150.gif','151.gif','152.gif','153.gif','154.gif','155.gif','156.gif','157.gif','158.gif','159.gif','161.gif','162.gif','163.gif','164.gif','165.gif','166.gif','167.gif','168.gif','169.gif','170.gif','172.gif','173.gif','174.gif','175.gif','176.gif','179.gif','180.gif','181.gif','182.gif','184.gif','185.gif','186.gif','187.gif','188.gif','190.gif','191.gif','192.gif','193.gif','194.gif','195.gif','196.gif','197.gif','198.gif','199.gif','200.gif','201.gif','202.gif','203.gif','204.gif','205.gif','206.gif','207.gif','208.gif','209.gif','210.gif','211.gif','212.gif','213.gif','214.gif','215.gif','216.gif','217.gif','218.gif','219.gif','220.gif','221.gif','222.gif','223.gif','224.gif','225.gif','226.gif','227.gif','228.gif','229.gif','230.gif','231.gif','232.gif','233.gif','234.gif','235.gif','236.gif','238.gif','239.gif','240.gif','241.gif','242.gif','243.gif','244.gif','245.gif','246.gif','247.gif','248.gif','249.gif','250.gif','251.gif','252.gif'];
	//autoGrow
	CKEDITOR.config.autoGrow_bottomSpace = 30;
	CKEDITOR.config.autoGrow_onStartup = true;
	CKEDITOR.config.autoGrow_minHeight = 150;
	CKEDITOR.config.autoGrow_maxHeight = 600;
};
CKEDITOR.dtd.del = CKEDITOR.dtd.strike;
CKEDITOR.dtd.ins = CKEDITOR.dtd.u;

