var uploadpl = {
	// Setting Variable upload form//
	runtimes 		:	'html5,flash,silverlight,html4',
	btn_browse	 	:	'',
	return_id 		:	'',
	filelist 		:	'',
	limit 			: 	0,
	mime_types 		:	[{title : "Image files", extensions : "jpg,gif,png"},{title : "Zip files", extensions : "zip"}],
	max_file_size 	:	'10mb',
	container_id 	:	'container',
	multipart 		:	false,		
	multipart_params:	{},		
	swf_url			:	'./public/lib/plupload-2.1.8/plupload/Maxie.swf',
	xap_url			:	'./public/lib/plupload-2.1.8/plupload/Moxie.xap',
	sortable		:	true,
	autoupload		: 	false,
	redirect 		:	'',
	form_btn		:	'',
	image_type		:	'',

	process : function(){
			var url = $( '#' + this.container_id ).attr('upload-url');
			var uploader = new plupload.Uploader({
			
				runtimes : this.runtimes,
				browse_button : this.btn_browse, // you can pass in id...
				container: document.getElementById(this.container_id), // ... or DOM Element itself
				//container: $('#' + this.container_id), // ... or DOM Element itself
				url : url ,
				drop_element		: this.container_id,
				
				multipart     		: this.multipart,
				multipart_params  	: this.multipart_params,
				filters 			: {
					max_file_size 	: this.max_file_size,
					mime_types: this.mime_types,
				},
				
				// Flash settings
				flash_swf_url : this.swf_url,
				// Silverlight settings
				silverlight_xap_url : this.xap_url,
			
			});
			return uploader;
	},
	processui : function(){
		$('#'+ this.container_id ).plupload({
			url : this.url,
			filters : [
				{title : "Image files", extensions : "jpg,gif,png"}
			],
			rename: true,
			sortable: true,
			flash_swf_url : this.swf_url,
			silverlight_xap_url : this.xap_url,
		});		
	},
	
	handlePluploadFilesAdded : function ( uploader, files ) {
		var $this = this;
		var $limit = uploadpl.limit;
		var filelist = this.filelist;
		//var no = $(filelist).length;
		var no = $('.img-preview').length;
		var $rows 	= parseInt( files.length ) + parseInt(no);
		
		console.log('limit : ' + $limit + ' | file upload num : ' + files.length +' | rows : '+ $rows + '| No is : ' + no );
		if($limit == 0 || ( $limit > 0 && $limit >= $rows ) ){
				
			for ( var i = 0 ; i < files.length ; i++ ) {
				uploadpl.preview( files[ i ] ,'img-' + (parseInt(no) + parseInt(i)));
			}
		}else{ 
			alert('Maximum upload image ' + $limit +'  File');
			return false;
		}
		removeg();
		uploadpl.imgsortable();
		if(uploadpl.autoupload == true){
			var $dx = uploadpl.loading();
			$dx.modal();
			uploader.start();
		}

		function removeg(){
			$('.thumb-view').on('click','.del-preview',function(e){
				e.preventDefault();
				var link = $(this).attr('href');
				/*
				if(link != ''){
					console.log('remove link image');
					if(!confirm('Please confirm delete this!!')){
						return false;
					}
					$.get(link);
				}
				*/
				uploader.removeFile(link);
				var thumb  = $(this).closest('li');
					console.log('Click Remove ');
					thumb.remove();
			});
		};
    },
	
	preview  : function(file,id){
		var tag = '<li id="thumbs-'+ id + '" class="thumb-view"><a href="'+ file.id +'" class="color-red del-preview glyphicon glyphicon-off"></a><b></b></li>';
		var preID = '#' + this.container_id + ' ' + this.filelist;
		var item = $( tag ).prependTo( preID );
		var image = $( new Image() ).appendTo(item);
		var preloader = new mOxie.Image();
		preloader.onload = function() {
			preloader.downsize( 100, 100 );
			image.prop({ "src": preloader.getAsDataURL(),'id':id,'class':'img-preview'} );
		};
		preloader.load( file.getSource() );	
	},
		
	remove	: function (){
		$('.img-gallery').on('click','.del-preview',function(e){
			e.preventDefault();
			var link = $(this).attr('href');
			if(link != ''){
				console.log('remove link image');
				if(!confirm('Please confirm delete this remove!!')){
					return false;
				}
				$.ajax({
					url : link,
					//type : 'DELETE',
					success : function(data){
						
					}
				});
			}
			var thumb  = $(this).closest('li');
			console.log('Click Remove ');
			thumb.remove();
		});
	},
	
	imgsortable : function(){
		if(this.sortable == true){
			console.log('sorting');
			return $('#' + this.container_id +' ' + this.filelist).sortable();
		}
	},
	
	progress  : function( uploader, file ){
		$('#percent-no').text(uploader.total.percent);
		$('.progress-bar').css('width',uploader.total.percent + '%');	
	},
	

	setparams : function(  uploader, files ){
		var ref_id 		= uploadpl.return_id,
			image_type 	= uploadpl.image_type;
		console.log('[Ref Id : ' +  ref_id.val()  + ' | Type : ' + image_type +' ]');
		uploader.settings.multipart_params = { '_token':$('#_token').val() , 'ref' : ref_id.val() ,'image_type' : image_type,'process' : 'uploader'};
		//uploader.settings.multipart_params = obj;//{ '_token':$('#_token').val() , 'id' : $id };
	},

	run : function(form_id){
		var uploader = uploadpl.process();
		var referrer =  document.referrer;
		uploader.bind( "FilesAdded", 		uploadpl.handlePluploadFilesAdded );
		uploader.bind( "UploadProgress", 	uploadpl.progress );
		
		$(uploadpl.form_btn).on('click',function(e){
			e.preventDefault();				
			var filecount = uploader.files.length;//$(uploadpl.filelist).length;
			console.log('File count : ' + filecount);
				
			uploader.bind( "BeforeUpload", 		uploadpl.setparams );
			console.log('click save');
			if( filecount > 0) {
				uploader.start();
				var $dx = uploadpl.loading();
				$dx.modal();
			}else{
				console.log('save form no upload');
				if(form_id !== undefined){
					form_id.append('<input type="hidden" name="process" value="submit" />').submit();
				}else{
					window.location.href = referrer;
				}
			}			
		});
		
		uploader.bind( "FileUploaded",function(upldr, file, obj){
			
			var data = JSON.stringify( obj );
			var jdata = JSON.parse(data);
			if(form_id !== undefined){
				form_id.append('<input type="hidden" name="gimage[]" value="' + jdata.response +'" />');
			}
			
			console.log('mydata : ' + data + ' | ' + jdata.response );
		});
		
		uploader.bind( "UploadComplete",function(up,files){
			setTimeout(function(){
				console.log('Close upload');
				$('.modal').modal('hide');
				if(form_id !== undefined){
					form_id.append('<input type="hidden" name="process" value="submit" />').submit();
				}else {
					if(uploadpl.redirect == ''){
						location.reload();// = referrer;
					}else if(uploadpl.redirect == 'back'){
						window.location.href = referrer;
					}else{
						window.location.href= uploadpl.redirect;
					}
				}
				
			},600);
		});
		
		uploader.init();
		uploadpl.remove();
	},
		
	loading : function(){
		var filecount = $(uploadpl.filelist).length;
		var $dialog = $('<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' 
			+'<div class="modal-dialog modal-m">'
				+'<div class="modal-content">'
					+'<div class="modal-header"><h3 style="margin:0;">Save & Uploading image</h3></div>'
					+'<div class="modal-body">'
						+'<div class="progress progress-striped active" style="margin-bottom:0;">'
							+'<div class="progress-bar progress-success" style="width:0%"></div>'
						+'</div>'
						+'<h4>Uploading <span id="percent-no">0</span>%</h4>'
					+'</div>'
				+'</div>'
			+'</div>'
		+'</div>');
		return $dialog;
	},

};