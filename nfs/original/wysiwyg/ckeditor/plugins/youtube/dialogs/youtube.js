CKEDITOR.dialog.add( 'youtube', function( editor )
{	
	return {
		title : 'Youtube',
		minWidth : 200,
		minHeight : 100,
		contents : [
			{
				id : 'youtube',
				label : 'youtube',
				title : 'Youtube',
				elements :
				[
					{
						id : 'youtube_id',
						type : 'text',
						label : 'URL ex) http://www.youtube.com/watch?v=bESGLojNYSo'
					}
				]
			}
		],
		onOk : function()
		{
			var id = this.getValueOf('youtube','youtube_id');
			var youtube_id = "[youtube:" + id + "]";
			editor.insertText(youtube_id);
		}
	};
} );
