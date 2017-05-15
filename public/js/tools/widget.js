function widgets(){
	this.options = '';
	this.sidebar = '';
	this.isset	 = false;
	this.count = 0;
	
	this.freetext = function(no){
		return '<small class="text-right">'
						+' <a class="close-link pull-right"><i class="fa fa-close"></i></a>'
					//	+' <a class="collapse-link pull-right"><i class="fa fa-chevron-up"></i></a>&nbsp;'
					+'</small>'
					+'<div class="x_panel">'
							+'<div class="form-group">'
								+'<input type="hidden" name="sidebar['+ no +'][type]" value="freetext">'
								+'<input type="text" name="sidebar['+ no +'][freetext_title]" class="form-control" placeholder="หัวเรื่อง">'
							+'</div>'
							+'<div class="form-group">'
								+'<textarea name="sidebar['+ no +'][freetext_detail]" class="form-control" placeholder="รายละเอียด/Script"></textarea>'
							+'</div>'
					+'</div>';
	};
	this.facebook = function(no){
		return '<small class="text-right">'
						+' <a class="close-link pull-right"><i class="fa fa-close"></i></a>'
					//	+' <a class="collapse-link pull-right"><i class="fa fa-chevron-up"></i></a>&nbsp;'
					+'</small>'
					+'<div class="x_panel">'
							+'<div class="form-group">'
								+'<input type="hidden" name="sidebar['+ no +'][type]" value="facebook">'
								+'<input type="text" name="sidebar['+ no +'][facebook_title]" class="form-control" placeholder="หัวเรื่อง">'
							+'</div>'
							+'<div class="form-group">'
								+'<input type="text" name="sidebar['+ no +'][facebook_link]" class="form-control" placeholder="http://www.facebook.com/yourpage">'
							+'</div>'
					+'</div>';
	};	
	this.stat = function(no){
		return '<small class="text-right">'
						+' <a class="close-link pull-right"><i class="fa fa-close"></i></a>'
					//	+' <a class="collapse-link pull-right"><i class="fa fa-chevron-up"></i></a>&nbsp;'
					+'</small>'
					+'<div class="x_panel">'
							+'<div class="form-group">'
								+'<input type="hidden" name="sidebar['+ no +'][type]" value="stat">'
								+'<input type="text" name="sidebar['+ no +'][stat_title]" class="form-control" placeholder="หัวเรื่อง">'
							+'</div>'
							+'<div class="form-group checkbox">'
								+'<label class="checkbox">'
									+'<input type="checkbox" name="sidebar['+ no +'][stat_all]"> แสดงสถิติทั้งหมด'
								+'</label>'
								+'<label class="checkbox">'
									+'<input type="checkbox" name="sidebar['+ no +'][stat_yesterday]"> แสดงสถิติเมื่อวาน'
								+'</label>'
								+'<label class="checkbox">'
									+'<input type="checkbox" name="sidebar['+ no +'][stat_today]"> แสดงสถิติวันนี้'
								+'</label>'
							+'</div>'
					+'</div>';
	};
	this.title = function(no,tag){
		return '<small class="text-right">'
						+' <a class="close-link pull-right"><i class="fa fa-close"></i></a>'
					//	+' <a class="collapse-link pull-right"><i class="fa fa-chevron-up"></i></a>&nbsp;'
					+'</small>'
					+'<div class="x_panel">'
							+'<div class="form-group">'
								+'<input type="hidden" name="sidebar['+ no +'][type]" value="'+ tag +'">'
								+'<input type="text" name="sidebar['+ no +']['+ tag +'_title]" class="form-control" placeholder="หัวเรื่อง">'
							+'</div>'
					+'</div>';
	};

	this.showhide = function( ){
		//this.count++;
		//console.log('count is ' + this.count);
		$('.sidebar .collapse-link').on('click', function () {
			var x_panel = $(this).closest('li');
			var button = $(this).find('i');
			var content = x_panel.find('.x_panel');

			content.slideToggle(200)
				
			button.toggleClass('fa-chevron-down').toggleClass('fa-chevron-up');
			setTimeout(function () {
				x_panel.resize();
			}, 50);
		});
		
		$('.close-link').on('click',function(e){
			var x_panel = $(this).closest('li');
			x_panel.remove();
		});
	};
	
}