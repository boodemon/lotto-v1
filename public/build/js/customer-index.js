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
					$('.wcIcon').on('click',function(e){
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
	datepick('input[name="date"]');
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
}(jQuery));