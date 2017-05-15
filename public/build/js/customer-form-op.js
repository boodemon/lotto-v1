(function ($) {
	// Table order process price //
	datepick($('.dep'));
	//============================================================================================================//
	var order = {
		amount : function( _this ){
			var row 	= _this.closest('tr');
			
			var qty 	= order.qty( row ),
				fee 	= order.fee( row ),
				price 	= order.price( row );
			var amount = nb( parseInt(qty) * ( parseFloat( price)  + parseFloat( fee )) );
			row.find('.amount').html(  amount  );
			order.total();
		},
		qty : function( row ){
			var qty = row.find('input.qty').val();
			return ( qty === NaN || qty === undefined || qty === '') ? 0 : qty;
		},
		price : function( row ){
			var price = row.find('input.unit_price').val();
			return ( price === NaN || price === undefined || price === '' ) ? 0 : price;
		},		
		fee : function( row ){
			var fee = row.find('input.fee').val();
			return ( fee === NaN || fee === undefined || fee === '' ) ? 0 : fee;
		},
		
		total : function(){
			var total = 0;
			$( '.amount' ).each(function(e){
				total += parseFloat( $(this).text().replace(',','') );
			});
			
			$('.total').html( nb(total) );
		},
		
		process :function( ){
			$('input.qty, input.unit_price, input.fee').on('blur keyup',function(e){
				var val = $(this).val();
				console.log('val is ' + val);
				if(isNaN(val) ){
					alert('Error!!\nPlease enter number only');
					$(this).val('').focus();
					e.preventDefault();
					return false;
				}
				order.amount( $(this) );
				order.total();
			});
			order.checkKey();
			
			var oStatus = $('input[name="status"]').val();
			var user = $('input[name="userLevel"]').val();
			
			// Sale user process //
			if( user == 'sale'){
				if(oStatus == 9){
					console.log('auto complete');
					$('input[name="client_id"]').autocomplete({
						source 		: '/order-client',
						minLength 	: 2,
						select 		: function( event, ui){
							loadTable( ui.item.id );
							$('input[name="name"]').val( ui.item.name );
							$('input[name="pmt"]').val( ui.item.pmt );
							$('input[name="invoice"]').val( ui.item.invoice );
							
							//return false;
						},
						change : function(event, ui){
							if(ui.item === null || ui.item === undefined){
								$('input[name="client_id"]').val('');
							}
						}
					});
					var loadTable = function(id){
					$('#order-list').html('<center><img src="/public/images/loading.gif"/><br/>Loading...</center>');
						$.ajax({
							url : '/order-table/' + id ,
							success : function(data){
								$('#order-list').html(data);
								order.active();
								//order.onSubmit();
							},
							error : function(){
								$('#order-list').html('Error!! Please refresh page.');
							}
						});
					}
				}
			}
			
			
			// Account user process //
			if($('input[name="userLevel"]').val() == 'account'){
				$('input[name="paid"], input#received ,input[name="less"]').on('blur keyup',function(e){
					order.remain();
				});
				order.remain();
			}
			// End Account user process //
		},
		remain : function(){
					var $paid 		= 	( $('input[name="paid"]').val() != '' && $('input[name="paid"]').val() !== undefined ) ? $('input[name="paid"]').val() : 0;
					var $received 	= 	( $('input[name="received"]').val() != '' && $('input[name="received"]').val() !== undefined ) ? $('input[name="received"]').val() : 0;
					var $less 		= 	( $('input[name="less"]').val() != ''  && $('input[name="less"]').val() !== undefined ) ? $('input[name="less"]').val() : 0;
					var $charger 		= 	( $('input[name="charger"]').val() != ''  && $('input[name="charger"]').val() !== undefined ) ? $('input[name="charger"]').val() : 0;
					var $total 		=	parseFloat($received) - (parseFloat($less) + parseFloat($paid)) + parseFloat($charger);
		console.log('received : ' + $received + ' | less : ' + $less + ' | paid : ' + $paid );
						$total === NaN ? 0: $total;
					$('#remain').val(  $total );
			
		},
		
		checkKey : function(){
			$("input[type='text']").keydown(function (e) {		
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
					opStatus 	= $('select[name="op_status"]'),
					tk 			= $('.ticket_type');
				// on sale submit form order //
				console.log('user is ' + user + ' | op status is ' + opStatus.val() );
				// on OP sumit form order //
				if(user == 'op'){

					if( opStatus.val() === '' || opStatus.val() === null ){
							alert('Please select OP status');
							$('select[name="op_status"]').focus();
							e.preventDefault();
							return false;
					}else if(opStatus.val() == 'done'){
						console.log('run done status');
						var ck = $('input[name="shipping"]:checked');
						if( ck.length == 0){
							e.preventDefault();
							alert('Please check shipping status');
							return false;
						}else{
							var track = $('input[name="tracking"]');
							console.log( 'ck val : ' + ck.val() + ' tracking : ' + track.val() );
							
							
							if( ck.val() == 'transport' && ( track.val() == '' || track.val() === null ) ){
								alert('Please enter tracking number');
								track.focus();
								e.preventDefault();
								return false;
							}
							
						}
						
						if($('input[name="delivery"]').val() === '' || $('input[name="delivery"]').val() === null){
							alert('Please enter Delever input');
							$('input[name="delivery"]').focus();
							e.preventDefault();
							return false;
						}else if($('input[name="less"]').val() === '' || $('input[name="less"]').val() === null){
							alert('Please enter Less input');
							$('input[name="less"]').focus();
							e.preventDefault();
							return false;
						}
						
						$('.dep').each(function(i,v){
							var $r = $(this).closest('tr');
							var $dep = $(this),
								$sup = $r.find('.order');
								if( $dep.val() === '' || $dep.val() === null){
									alert('Please enter dep input');
									$dep.focus();
									e.preventDefault();
									return false;
								}else if( $sup.val() == '' || $sup.val() === null ){
									alert('Please enter supplier input');
									$sup.focus();
									e.preventDefault();
									return false;
								}
						});
						
					}else{
						if( !confirm('Please confirm to continue') ){
							e.preventDefault();
							return false;
						}
					}
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
			if(user == 'op'){
				if(oStatus == '2' || oStatus == '3'){
					order.unActive('input[name="client_id"]');
					order.unActive('input[name="name"]');
					order.unActive('input[name="paid"]');
					order.unUse('.btn-paid_type');


					order.unActive('input.pmt');
					order.unActive('input.received');
					order.unUse('select.bank');
					order.unActive('input.time');

					order.unActive('.ticket');
					order.unActive('.type');
					order.unActive('.spect');
					order.unActive('.qty');
					order.unActive('.unit_price');
					order.unActive('.fee');
					var ship = 'input[name="shipping"]';
						order.tracking(ship);
					order.unActive('input.charger');
					var delivery = $('input[name="delivery"]');
					$('#email').on('click',function(e){
						delivery.val( 'Email' );
					});
					$('#transport').on('click',function(e){
						delivery.val( 'Kerry Express' );
						$('input[name="tracking"]').focus();
					});
					$('#pickup').on('click',function(e){
						delivery.val( 'SELF' );
					});
				}else{
					order.unActive('input[type="text"],select');
				}
			}
		},
		unActive : function(input){
			$(input).prop('readOnly',true);
		},
		unUse : function(input){
			$(input).prop('disabled', true);
		}
	}
	
	order.process();
	order.onSubmit();
	order.active();

}(jQuery));