CKEDITOR.plugins.add( 'youtube',
{
	init : function( editor )
	{
		var pluginName = 'youtube';

		// Register the dialog.
		CKEDITOR.dialog.add( pluginName, this.path + 'dialogs/youtube.js' );

		// Register the command.
		editor.addCommand( pluginName, new CKEDITOR.dialogCommand( pluginName ) );

		// Register the toolbar button.
		editor.ui.addButton( 'Youtube',
			{
				label : editor.lang.common.youtube,
				command : pluginName,
				icon: this.path + 'images/tb_youtube_prop.gif'
			});

	}
} );
