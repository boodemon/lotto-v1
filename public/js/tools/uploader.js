function uploader(){
	var contain_id 	= 'gallery',
	upload_url 		= '',
	select_button 	= '',
	flash_swf_url	= "./asset/lib/plupload-2.1.2/js/Moxie.swf";
	var upload_preview 	= '#' + this.contain_id + ' .preview';
	var $this = this;
	console.log(this.upload_preview);
	this.process = function(){
		 var uploader = new plupload.Uploader({
				runtimes		: "html5,flash",
				url				: $this.upload_url,
				drop_element	: $this.contain_id,
				browse_button	: $this.select_button,
				container		: $this.contain_id,
				flash_swf_url	: $this.flash_swf_url,
				urlstream_upload: true
			 });
			 
		uploader.bind( "FilesAdded", this.handlePluploadFilesAdded );
        uploader.init();
	};
	
	this.start = function(){
		$('#uploader').plupload('start');
	};
	
    this.handlePluploadFilesAdded = function ( uploader, files ) {
		console.log( "Files selected. " + this );
		for ( var i = 0 ; i < files.length ; i++ ) {
			//console.log('file : ' + files[i]);
			$this.preview( files[ i ] ,'img-'+i);
		}
		$this.remove();
    };
	
	this.preview = function(file,id){
		var item = $( '<li id="thumbs-'+ id + '"><a href="'+ id +'" class="color-red del-preview glyphicon glyphicon-off"></a></li>' ).prependTo( '#' + this.contain_id + ' .preview' );
		var image = $( new Image() ).appendTo(item);
		var preloader = new mOxie.Image();
		preloader.onload = function() {
			preloader.downsize( 100, 100 );
			image.prop({ "src": preloader.getAsDataURL(),'id':id,'class':'img-preview'} );
		};
		preloader.load( file.getSource() );	
	};
			
	this.remove	=	function (){
		$('.del-preview').on('click',function(e){
			e.preventDefault();
			var thumb  = $('#thumbs-' + $(this).attr('href'));
			thumb.remove();
		});
	};
}