(function ($) {
	$(":input").inputmask();
	var addPayments = function(event){
            event.preventDefault();
            var $formGroup = $(this).closest('tr');
            var $formGroupClone = $formGroup.clone();

            $formGroupClone.find('.btn-add-pays').toggleClass('color-blue btn-add-pays color-red btn-remove-pays')
                .html('<i class="fa fa-minus"></i>');

            $formGroupClone.find('.btn-remove-pays.reload').remove();
            $formGroupClone.find('input').removeClass('hasDatepicker').val('');
            $formGroupClone.find('select.bank option').removeAttr('selected');
            $formGroupClone.find('select.bank option[value=""]').prop('selected',true);
            $formGroupClone.insertAfter($formGroup);
			datepick($('.pmt'));
			order.checkKey();
			$(":input").inputmask();
			
	}

	var removePayments = function(event){
            event.preventDefault();
			var $val = $(this).attr('href');
			console.log('$val is ' + $val );
			var $this = $(this);
			if($val !=='#'){
				if(!confirm('Please confirm delete this')){
					return false;
				}
				//$.get($val);
				//$.get($val);
				$.ajax({
					url : $val,
					success : function(data){
						if( $this.hasClass('reload')){
							window.location.reload();
						}
						
					}
				});
				
			}
            var $formGroup = $(this).closest('tr');
            $formGroup.remove();
			//order.total();
	}

	$(document).on('click', '.btn-add-pays', addPayments);
	$(document).on('click', '.btn-remove-pays', removePayments);
	
	// Table payments process price //
	//============================================================================================================//
	var payments = {
		received : function(){
			var received = 0;
			$('.received').each(function(e,i){
				var $row 	= $(this).closest('tr');
				var $bank   = $row.find('select.bank');
					
				received 	=  parseFloat(received) + parseFloat( $(this).val().replace(',','') ) ;
				if($bank.val() == 'omise'){
					var $rc = $(this).val();
					var $charge  =  $row.find('input.charger').val().replace(',',''); //$rc* 3 / 100;
						received = parseFloat(received) +  parseFloat($charge);
						console.log('received is ' + received + ' | charger is ' + $charge );
				}
			});
			console.log(received);
			return !isNaN(received) ?  received : 0;
		},
		
		paid : function(){
			var paid = $('input[name="paid"]').val();
			console.log('paid is ' + paid);
			return ( paid != '' && paid !== undefined ) ? parseFloat(paid): 0.00;
		},
		
		bank : function(){
			$('.bank').on('change',function(e){
				var row 		= $(this).closest('tr');
				var $received 	= row.find('.received').val();
				var $charge 	= parseFloat($received * 3 / 100 );
				var $nrec 		= $received - $charge;
				if( $(this).val() == 'omise' ){
					row.find('.charger').show().val( nb2( $charge ) );
					row.find('.received').val(  $nrec.toFixed(2) );
				}else{
					row.find('.charger').hide();
				}
				order.bank();
			});
		},
		
		onProcess : function(){
			var $recv = payments.received(),
				$paid = payments.paid(),
				$pType= $('input[name="paid_type"]'),
				$remain;
				
			console.log('recv ' + $recv ) ;
			$remain = $pType.val() == 'deposit' ? ( parseFloat($recv) +  parseFloat($paid)  ) : ( $recv -  $paid );
			$('input[name="remain"]').val( nb2($remain) );
			$('.total-pays').html( nb2( $recv ) );
			
			
		},
		
		onPress : function(){	
			$('.received').on('focus',function(e){
				var $row 	= $(this).closest('tr');
				var $val 	= $(this).val().replace(',','');
				
				var $bank   = $row.find('select.bank'),
					$charger= $row.find('input.charger').val();
				if($bank.val() == 'omise'){
					$nval = parseFloat( $val ) + parseFloat( $charger );
					console.log('charger : ' + $charger + ' | val : ' + $val + ' | nval : ' + $nval );
					$(this).val( $nval );
				}
				//payments.onProcess();
			});
			
			$('.received').on('blur',function(e){
				var $row 	= $(this).closest('tr');
				var $val 	= $(this).val();//.replace(',','');

				var $bank   = $row.find('select.bank'),
					$charger= $row.find('input.charger'),
					$charge = $val * 3 / 100;

				if( $bank.val() == 'omise' ){
					$nval = parseFloat($val) - parseFloat($charge);
					$(this).val( $nval );
					$charger.val( nb2( $charge ) );
				}
				payments.onProcess();
			});

			$('.received').on('keyup',function(e){
				var $row 	= $(this).closest('tr');
				var $val 	= $(this).val().replace(',','');
				
				var $bank   = $row.find('select.bank'),
					$charger= $row.find('input.charger'),
					$charge = $val * 3 / 100;

				if($bank.val() == 'omise'){
					console.log('charge is ' + $charge);
					$charger.val( nb2( $charge ) );
				}
				
				payments.onProcess();
			});
			
			$('input[name="paid"]').on('keyup blur',function(e){				
				payments.onProcess();
			});
			//order.checkKey();
		},
		
		remain : function(){
			payments.bank();
			payments.onPress();
			payments.onProcess();			
		}
	}
	payments.remain();
	
	// Table order process price //
	//============================================================================================================//
	var order = {
		checkKey : function(){
			$("input[type='text'], select").keydown(function (e) {		
				//alert(e.which);
				if( e.which == 13 )
				{
					var index = $('input[type="text"]').index(this)+1;
					$('input[type="text"]').eq(index).focus();
					e.preventDefault();
				}
			});
		},
		
		onSubmit : function(){
			$('form').on('submit',function(e){
				var user 		= $('input[name="userLevel"]').val(),
					saleStatus 	= $('.sale-status').val(),
					rmk 		= $('input[name="head-remark"]').val(),
					oStatus 	= $('input[name="status"]').val(),
					tk 			= $('.ticket_type');
				
				// on Account submit create new order //
				if( oStatus == 0 ){
					var received 	= $('input.received'),
						bank 		= $('select.bank'),
						time 		= $('input.time'),
						vno 		= 0,
						bno			= 0,
						tno 		= 0;

					received.each(function(i,v){
						if( $(this).val() === '' ){
							vno++;
							e.preventDefault();
							alert('Please enter input received');
							$(this).focus();
							e.stopPropagation();
						}
					});
					console.log('vno : ' + vno);
					if( vno > 0)
						return false;
					

					bank.each(function(i,v){
						if( $(this).val() === '' ){
							bno++;
							alert('Please enter input bank');
							$(this).focus();
							e.preventDefault();
						}
					});
					if( bno > 0)
						return false;
					
					time.each(function(i,v){
						if( $(this).val() === '' ){
							tno++;
							alert('Please enter input time');
							$(this).focus();
							e.preventDefault();
							return false;
						}
					});	
					if( tno > 0)
						return false;
				}
			});
		},
		tracking : function(input){
			var track = $('input[name="tracking"]');
			track.hide();
			var cked = $(input + ':checked');
			console.log('cked : '+ cked.val() );
				if( cked.val() ==  'transport'){
					track.show();
				}
				$(input).on('click',function(e){
					if($(this).val() == 'transport'){
						track.show();
					}else{ 
						track.hide();
					}
				});
			
		},
		
		active : function(){
			var oStatus = $('input[name="status"]').val();
			var user 	= $('input[name="userLevel"]').val();
			
			$('#type_list').on('click','a',function(e){
				e.preventDefault();
				var btn = $(this).closest('.input-group-btn');
				btn.find('button[name="btn-paid"]')
							.html( $(this).text() + ' <span class="caret"></span>' );
				btn.find('input[name="paid_type"]').val( $(this).attr('href') );
				payments.onProcess();			
			});
			if(oStatus == '9'){
					console.log('9');
					order.unActive('input[name="paid"]');
					order.unUse('button[name="btn-paid"]');
			}
			if( oStatus == '1' || oStatus == '0' || oStatus == '9' ){
					console.log('1 0 9');
					order.unActive('input[name="less"]');
					order.unActive('input[name="delivery"]');
//					order.unActive('#tb-orders input[type="text"]');
					order.unActive('.ticket');
					order.unActive('.type');
					order.unActive('.spect');
					order.unActive('.qty');
					order.unActive('.unit_price');
//					order.unUse('#tb-orders select');
					datepick($(".pmt"));
			}else if(oStatus == '3'){
					console.log('3');
					order.unActive('input[name="delivery"]');
					order.unActive('input[name="paid"]');
					order.unActive('input.received');
					order.unActive('input.pmt');
					order.unActive('input.time');
					
					order.unUse('select.bank');
					order.unUse('button.dropdown-toggle');
					order.unUse('input[name="shipping"]');
					order.unUse('input[name="tracking"]');
					order.unActive('input[name="time"]');
					
					order.unActive('.ticket');
					order.unActive('.type');
					order.unActive('.spect');
					order.unActive('.qty');
					order.unActive('.unit_price');
					order.unActive('.fee');
					
					var ship = 'input[name="shipping"]';
						order.tracking(ship);
			}else{
				console.log('unknow')
					order.unActive('input[type="text"]');
					order.unUse('select, input[type="radio"]');
			}
				order.bank();
				order.unActive('input[name="charger[]"]');
				order.unUse('input[name="remain"]');
				order.unActive('input[name="client_id"]');
				order.unActive('input[name="name"]');
				order.unActive('.dep');
				order.unActive('.order');
				order.unActive('.unit_price');
				
				if( $('.ticket_type').length == 0 ){
					$('.tk-type').hide();
				}
				
			$('.confirm').on('click',function(e){
					if( !confirm('Please confirm to continue') ){
						e.preventDefault();
						return false;
					}
			});

		},
		unActive : function(input){
			$(input).prop('readOnly',true);
		},
		unUse : function(input){
			$(input).prop('disabled', true);
		},
		bank : function(){
			var x = 0;
			$('.bank').each(function(e,i){
				if( $(this).val() == 'omise' ){
					x++;
				}
			});
			if( x > 0){
				$('.omise-charger').show();
				$('.omise-head').show();
				$('.td-total').attr('colspan','4');
			}else{
				$('.omise-charger').hide();
				$('.omise-head').hide();
				$('.td-total').attr('colspan','3');
			}
		}
	}
	
	order.onSubmit();
	order.active();
	
}(jQuery));