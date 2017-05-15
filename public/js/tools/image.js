//Image manage file upload preview //
var image = {
	inputid 	: '',
	previewid 	: '',
	imageid 	: '',
	imagewidth 	: 200,
	imageheight : 200,
	allowfile 	: '',
		
	preview : function(){
		var _this = this;
		var input 	= _this.inputid[0];
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				_this.imageid.attr('src', e.target.result)
				.width(_this.imagewidth)
				.height(_this.imageheight);
			};
			reader.readAsDataURL(input.files[0]);
			//console.log(reader);
		}else{
			_this.imageid.attr('src','/public/images/user.png');
		}
	},
	inputclick : function(){
		var _this = this;
		_this.inputid.on('change',function(){
			var allow 		= 	_this.allowfile.split('|');
			var permiss		=	0; // เงื่อนไขไฟล์อนุญาต		
			permiss_file	=	$(this).val();
			permiss_file	=	permiss_file.toLowerCase();    
			if(permiss_file !=	""){
				for(i=0;i<allow.length;i++){ // วน Loop ตรวจสอบไฟล์ที่อนุญาต   
					if(permiss_file.lastIndexOf(allow[i])>=0){  // เงื่อนไขไฟล์ที่อนุญาต   
						permiss=1;
						break;
					}else{
						continue;
					}
				}
			}
			//name = _this.inputid[0];
			if(permiss==0){
					alert("Please Upload file type "+ allow + " Only");
					_this.inputid.val('');
					_this.imageid.attr('src',base_path() + '/public/images/user.png');
				return false;   
			}
			_this.preview();
		});
	}
	
};

function images(){
	this.inputid 	= '';
	this.previewid 	= '';
	this.imageid 	= '';
	this.imagewidth = 200;
	this.imageheight= 200;
	this.allowfile 	= '';
		
	this.preview = function(){
		var _this = this;
		var input 	= _this.inputid[0];
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				_this.imageid.attr('src', e.target.result)
				.width( _this.imagewidth != '' ? _this.imagewidth : 'auto')
				.height( _this.imageheight != '' ?  _this.imageheight : 'auto' );
			};
			reader.readAsDataURL(input.files[0]);
			//console.log(reader);
		}else{
			_this.imageid.attr('src','/public/images/user.png');
		}
	};
	this.inputclick = function(){
		var _this = this;
		_this.inputid.on('change',function(){
			var allow 		= 	_this.allowfile.split('|');
			var permiss		=	0; // เงื่อนไขไฟล์อนุญาต		
			permiss_file	=	$(this).val();
			permiss_file	=	permiss_file.toLowerCase();    
			if(permiss_file !=	""){
				for(i=0;i<allow.length;i++){ // วน Loop ตรวจสอบไฟล์ที่อนุญาต   
					if(permiss_file.lastIndexOf(allow[i])>=0){  // เงื่อนไขไฟล์ที่อนุญาต   
						permiss=1;
						break;
					}else{
						continue;
					}
				}
			}
			//name = _this.inputid[0];
			if(permiss==0){
					alert("Please Upload file type "+ allow + " Only");
					_this.inputid.val('');
					_this.imageid.attr('src', '');
				return false;   
			}
			_this.preview();
		});
	}
	
}