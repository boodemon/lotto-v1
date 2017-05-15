(function($){
	$('.single,.single-text').tooltip();
	$('.remark-note,.wcIcon, .sale-status-icon').tooltip({'placement':'right'});
	$('.icon-status').tooltip({'placement':'top'});
	$('.action a').tooltip({'placement':'left'});
	
	var datepick = function(input){
		console.log($(input).val() );
		if($(input).val() !== undefined )
		$(input).daterangepicker({
          singleDatePicker: true,
          calender_style: "picker_3",
		  format: 'YYYY-MM-DD',
        });
	}
	
	var nl2br = function(str, is_xhtml) {   
				var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
				return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
			}
			
	var note = function(){
					$('.remark-note').on('click',function(e){
						e.preventDefault();
						$('#note-title').text( $(this).attr('href') );
						$('#note-body').html( nl2br( $(this).attr('data-text') ) );
						$('#remark-note').modal('show');
					});
					$('td > .wcIcon').on('click',function(e){
						e.preventDefault();
						$('#note-title').text( 'Status Process' );
						$('#note-body').html( nl2br( $(this).attr('data-original-title') ) );
						$('#remark-note').modal('show');
						
					});
			}
				
	var logs = function(){
					$('.order-logs').on('click',function(e){
						e.preventDefault();
						var client = $(this).attr('data-client'),
							href 	= $(this).attr('href');
						$('#order-title').html('Order logs clident id #' + client );
						$('#order-body').html('<center><img src="/public/images/loading.gif"><br/>Loading....</center>');
						$.ajax({
							url : _base +'/logs-order/' + href ,
							success: function(data){
								$('#order-body').html(data);
							}
						});
						$('#order-logs').modal('show');
					});
			}
	var filter = function(){
					$('#set-filter').on('click',function(e){
						e.preventDefault();
						var page = $(this).attr('data-page');
						$('#order-title').html( page.toUpperCase() +' SHOW/HIDE COLUMNS' );
						$('#order-body').html('<center><img src="'+ _base +'/public/images/loading.gif"><br/>Loading....</center>');
						$.ajax({
							url : _base +'/filter/set/' + page ,
							success: function(data){
								$('#order-body').html(data);
							}
						});
						$('#order-logs').modal('show');
					});
			}
	filter();
							
	var contactinfo = function(){
					$('.contactinfo, .ticket').on('click',function(e){
						console.log('contact info');
						e.preventDefault();
						$('#order-title').html('Ticket/Contact info');
						$('#order-body').html('<center><img src="/public/images/loading.gif"><br/>Loading....</center>');
						$('#order-body').html( nl2br( $(this).attr('data-original-title') ) );
						$('#order-logs').modal('show');
					});
			}
	var omise = function(){
		$('.omise').on('click',function(e){
			e.preventDefault();
			var id 		= $(this).attr('data-id'),
				amount 	= $(this).attr('data-amount'),
				href 	= $(this).attr('href');
						$('#omise-title').html('Omise order ฿' + amount );
						$('#omise-body').html('<center><img src="/public/images/loading.gif"><br/>Loading....</center>');
						$.ajax({
							url : '/omise-list/' + id ,
							success: function(data){
								$('#omise-body').html(data);
							}
						});
						$('#omise').modal('show');
		});
	}
	note();
	logs();
	contactinfo();
	omise();
	datepick('input[name="start"]');
	datepick('input[name="end"]');
	$(":input").inputmask();

	
	$('.quick-load').on('click',function(e){
		e.preventDefault();
		$('#order-title').html( 'Get transfer order quickly' );
		$('#order-body').html('<center><img src="/public/images/loading.gif"><br/>Loading....</center>');
		$('#order-logs').modal('show');
		/*
		setTimeout(function(){
			window.location.reload();		
		},500);
		*/
		$.ajax({
			url : _base + '/cronjob/process-order' ,
			success: function(data){
				$('#order-body').html('<h1 class="text-success text-center">success</h1>');
				setTimeout(function(){
					location.reload();
				},500);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				if (jqXHR.status == 500) {
						$('#order-body').html('Internal error: ' + jqXHR.responseText);
				} else {
						$('#order-body').html('Unexpected error.');
				}
			}
		});
		$.get(_base + '/migrate/sale-done');

	});
	
	$('.confirm').on('click',function(e){
		if( !confirm('Please confirm to continue') ){
			e.preventDefault();
			return false;
		}
	});
	
	$('#frm-search').on('submit',function(e){
		var start 	= $('input[name="start"]'),
			end 	= $('input[name="end"]');
		if(start.val() != '' && end.val() == ''){
			$('.for-end').text('*Please enter end date for filter.');
			$('.for-start').text('');
			end.focus();
			return false;
		}else if(start.val() == '' && end.val() != ''){
			$('.for-end').text('');
			$('.for-start').text('*Please enter start date for filter.');
			start.focus();
			return false;
		}else if(start.val() != '' && end.val() != ''){
			var st = Date.parse( end.val() ),
				en = Date.parse( start.val() );
			console.log('st = ' + st + ' | en = ' + en );
			if( st <= en ){
				$('.for-start').text('*Please enter Start date must be less than end date.');
				$('.for-end').text('*Please enter End date must be greater than start date.');
				return false;
			}
		}
	});
	var ar = [];
	
	$('.pmt').each(function(i,v){
		var trow;// 	= $(this).closest('tr');
		var cs  	= $(this).find('span').attr('class');
		if(cs !== undefined && $.inArray(cs,ar) == -1 ){
			ar.push(cs);
		}
	});
	//ar.unique();
	console.log(ar);
	$.each(ar,function(k,v){
//		console.log(k);
		var sm 	= 'span.' + v;
		var nod = $(sm).length-1; 
		console.log( v + ' is length = ' + nod );
		
		//$(sm + ':last-child').parent().parent().parent().addClass('sum-day');
		$(sm + ':nth(' + nod +')').parent().parent().parent().addClass('sum-day');
	});
}(jQuery));