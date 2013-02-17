/**
 * Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 *
 * CKFinder 2.x - sample "filepath" plugin.
 *
 * To enable it, add the following line to config.js:
 *     config.extraPlugins = 'filepath';
 */

/**
 * See http://docs.cksource.com/ckfinder_2.x_api/symbols/CKFinder.html#.addPlugin
 */
CKFinder.addPlugin( 'filepath', {

	lang : [ 'en', 'ja' ],

	appReady : function( api ) {
		CKFinder.dialog.add( 'filepathdialog', function( api )
			{
				// CKFinder.dialog.definition
				file = api.getSelectedFile();

				var dialogDefinition =
				{
					title : api.lang.filepath.title,
					minWidth : 300,
					minHeight : 60,
					onShow : function() {
						var dialog = this;
						doc = dialog.getElement().getDocument();
						file = api.getSelectedFile();
						doc.getById('file_path_val').setValue(file.getUrl().replace(/\/files\/photo\/[^\/]+\//,'/files/photo/').replace(/\/files\/photo\/[^\/]+\//,'/files/photo/').replace(/\/files\/photo\/[^\/]+\//,'/files/photo/')); //RCMS
					},
					contents : [
						{
							id : 'tab1',
							label : '',
							title : '',
							expand : true,
							padding : 0,
							elements :
							[
								{
									type : 'html',
									html : '<h3>' +  api.lang.filepath.typeText + '</h3><input type="text" id="file_path_val" size="60">'
								}
							]
						}
					],
					buttons : [CKFinder.dialog.cancelButton ]
				};

				return dialogDefinition;
			} );

		api.addFileContextMenuOption( { label : api.lang.filepath.menuItem, command : "filepathcommand" } , function( api, file )
		{
			api.openDialog('filepathdialog');
		});
	}
});
