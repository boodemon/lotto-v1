var fileUploads = {
	inputid 	: '',
	allowfile 	: '',
	
	process : function(){
		var _this = this;
		_this.inputid.on('change',function(){
			var allow 		= 	_this.allowfile.split(',');
			var permiss		=	0; // เงื่อนไขไฟล์อนุญาต		
			permiss_file	=	$(this).val();
			permiss_file	=	permiss_file.toLowerCase();    
			if(permiss_file !=	""){
				for(i=0;i<allow.length;i++){ // วน Loop ตรวจสอบไฟล์ที่อนุญาต   
					if(permiss_file.lastIndexOf( allow[i].trim() )>=0){  // เงื่อนไขไฟล์ที่อนุญาต   
						permiss=1;
						break;
					}else{
						continue;
					}
				}
			}
			
			//name = _this.inputid[0];
			if(permiss == 0){
					alert("Please Upload file type "+ allow + " Only");
					_this.inputid.val('');
				return false;   
			}
		});
	}
}