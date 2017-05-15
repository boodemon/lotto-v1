var uploadpl = {
	// Setting Variable upload form//
	runtimes 		:	'html5,flash,silverlight,html4',
	btn_browse	 	:	'',
	return_id 		:	'',
	res_id 			:	'',
	filelist 		:	'',
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
	_this 			:	uploadpl,
	func			: 	'',
	form_btn		:	'',
	update_img_url	:   '',

	process : function(){
			var url = $( '#' + this.container_id ).attr('upload-url') + '?_token=' + $('#_token').val();
			console.log('URL : ' + url);
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
		var filelist = this.filelist;
		var no = $(filelist).length;
		for ( var i = 0 ; i < files.length ; i++ ) {
			uploadpl.preview( files[ i ] ,'img-' + (parseInt(no) + parseInt(i)));
		}
		uploadpl.remove();
		uploadpl.imgsortable();
		if(uploadpl.autoupload == true){
			var $dx = uploadpl.loading();
			$dx.modal();
			uploader.start();
		}
    },
	
	preview  : function(file,id){
		var tag = '<li id="thumbs-'+ id + '"><a href="" class="color-red del-preview glyphicon glyphicon-off"></a><b></b></li>';
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
		$('.del-preview').on('click',function(e){
			e.preventDefault();
			var link = $(this).attr('href');
			if(link != ''){
				console.log('remove link image');
				if(!confirm('Please confirm delete this!!')){
					return false;
				}
				$.get(link);
			}
			var thumb  = $(this).closest('li');
			console.log('Click Remove ');
			thumb.remove();
		});
	},
	
	imgsortable : function(){
		if(this.sortable == true){
			return $('#' + this.container_id +' ' + this.filelist).sortable();
		}
	},
	
	progress  : function( uploader, file ){
		$('#percent-no').text(uploader.total.percent);
		$('.progress-bar').css('width',uploader.total.percent + '%');	
	},
	
	uploaded : function(upldr, file, obj){
		var myData;
		try {
			myData = eval(obj.response);
		} catch(err) {
			myData = eval('(' + obj.response + ')');
		}
		var sref 	= uploadpl.return_id;
		var id 		= myData.id;
		var ref 	= myData.ref;
		
		if(ref == 0){
			$this.updateref(id,sref);
		}
	},
	
	updateref : function(id,ref){
		$.get($this.update_img_url+'/'+id+'/'+ref);
	},

	setparams : function(  uploader, files ){
		//var $id = uploadpl.return_id.val();
		var obj = [];
		var $id = uploadpl.multipart_params;
		var rid = {'ref' : uploadpl.res_id};
		obj.push( $id, rid );
		
		console.log('[return id : ' +  uploadpl.res_id  +'][JSON : '+ JSON.stringify(obj) +']');
		uploader.settings.multipart_params = { '_token':$('#_token').val() , 'ref' : uploadpl.res_id };
		//uploader.settings.multipart_params = obj;//{ '_token':$('#_token').val() , 'id' : $id };
	},

	run : function(form_id,return_id){
		var uploader = uploadpl.process();
		var referrer =  document.referrer;
		uploader.bind( "FilesAdded", 		uploadpl.handlePluploadFilesAdded );
		uploader.bind( "UploadProgress", 	uploadpl.progress );
		//uploader.bind( "FileUploaded", 		uploadpl.uploaded );
		
		$(uploadpl.form_btn).on('click',function(e){
			e.preventDefault();
			if(form_id !== undefined){
				var form_data 	= form_id.serializeArray();
				var datato = {};
				$.each(form_data,function(i,f){
					var name 	= f.name;
					var val 	= f.value;
					datato[name] = val;
				});
				
				if(form_id.find('#tinymce')){
					var eid = form_id.find('#tinymce').attr('data-id');
					var ed = tinyMCE.get(eid);
					datato['detail'] = tinyMCE.activeEditor.getContent();
				}
				
				var j = JSON.stringify(datato);
				var jn = JSON.parse(j);
				var $_token =  jn._token;
				$.ajaxSetup({
					headers: { 'X-CSRF-TOKEN': $_token }
				});
			
				$.ajax({
					url : form_id.attr('action'),
					data : jn,
					type : 'POST',
					success:function(data){
						var key = $.trim(data);
						console.log('Return ID : ' + key);
						return_id.val(key);	
						uploadpl.res_id = key;
						/* Start upload */
										
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
								window.location.href = referrer;
							}

						/* End upload */
					}
				});
			}else{
				
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
					window.location.href = referrer;
				}
			}
			
		});
		
		uploader.bind( "UploadComplete",function(up,files){
			setTimeout(function(){
				console.log('Close upload');
				$('.modal').modal('hide');
				
				if(uploadpl.redirect == ''){
					location.reload();// = referrer;
				}else if(uploadpl.redirect == 'back'){
					window.location.href = referrer;
				}else{
					window.location.href= uploadpl.redirect;
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