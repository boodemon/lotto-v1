//Image manage file upload preview //
function tools(){
	this.textdel = 'Please confirl delete this!';
	
	this.confirm_del = function(){
		var _this = this;
		if($('.del').length > 0){
			$('.del').on('click',function(){
				console.log('text contirm : ' + _this.textdel );
				if(!confirm(_this.textdel)){
					return false;
				}
			});
		}
	};
	/*---------------------------------
	 :: SET CHECKBOX ALL :: 
	 ----------------------------------*/
	this.checkboxAll = function(){
		$('.checkboxAll').on('click',function(e){	
			var chekednum 	= $('.checkboxAll:checked').length;
			var chekboxnum 	= $('.checkboxAll').length;
			if(chekednum == chekboxnum){
				$('#checkAll').prop('checked',true);
			}else{
				$('#checkAll').prop('checked',false);
			}
		});
		
		$('#checkAll').on('click',function(e){
			if($(this).is(':checked')){
				$('.checkboxAll').prop('checked',true);
			}else{
				$('.checkboxAll').prop('checked',false);
			}
		});
	};
	/*----------------------------
	:: Preload on submit form 
	---------------------------*/
	this.preload = function(){
		$('form').on('submit',function(e){
			$('body').append('<div class="preoverlay text-center"></div>'
							+'<div class="preloading text-center">'
							+'<img src="'+ _base + '/public/images/loading.gif"/><br/>'
							+'Loading...'
							+'</div>');
			var jobHeight = $(document).height();
			$('html, body').animate({scrollTop : 0},800);
			$('.preloading').removeClass('hide').show('slow');
			$('.preoverlay').removeClass('hide').show('slow').css('height',jobHeight);
		});
	};
	/*----------------------------
	:: Open Color Box 
	---------------------------*/
	this.cbox = function(){
		$('.cbox').on('click',function(e){
			e.preventDefault();
			var cWidth 	= $(this).attr('box-width');
			var cHeight = $(this).attr('box-height');
			if(cWidth !== undefined || cHeight !== undefined){
				$( $(this) ).colorbox({
					rel 	: 'cbox',
					href 	: $(this).attr('href'),
					width	: cWidth, 
					height	: cHeight,
					transition:"fade",
					iframe 	: true,
					fixed 	: true
				});
			}else{
				$( $(this) ).colorbox({
					rel 	: 'cbox',
					href 	: $(this).attr('href'),
					transition:"fade"
				
				});
			}
		});
	}
}

(function($){
	var tool = new tools;
	tool.confirm_del();
	tool.checkboxAll();
}(jQuery));