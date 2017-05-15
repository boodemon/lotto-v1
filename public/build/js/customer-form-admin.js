(function ($) {
	$(":input").inputmask();
	datepick($(".pmt"));
	var mode	= $('input[name="level-mode"]').val();
	// Add Payment || One order to multi payments //
	//============================================================================================================//
	var addPayments = function(event){
            event.preventDefault();
            var $formGroup = $(this).closest('tr');
            var $formGroupClone = $formGroup.clone();

           // $(this)
                $formGroupClone.find('.btn-add-pays').toggleClass('color-blue btn-add-pays color-red btn-remove-pays')
                .html('<i class="fa fa-minus"></i>');

            $formGroupClone.find('input.received').val('');
            $formGroupClone.find('input.pmt').removeClass('hasDatepicker');
            $formGroupClone.find('input.pay_id').val('0');
            $formGroupClone.find('input.time').val('');
            $formGroupClone.find('select.bank option').removeAttr('selected');
            $formGroupClone.find('select.bank option[value=""]').prop('selected',true);
            $formGroupClone.insertAfter($formGroup);
			$(":input").inputmask();
			order.checkKey();
			datepick($(".pmt"));
			order.btn();
			payments.remain();
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
			order.btn();
			payments.remain();
	}
    
	$(document).on('click', '.btn-add-pays', addPayments);
	$(document).on('click', '.btn-remove-pays', removePayments);
	
	// Add Order || One payment to multi orders //
	//============================================================================================================//
	var addOrders = function(event){
            event.preventDefault();
            var $formGroup = $(this).closest('.panel-order');
            var $formGroupClone = $formGroup.clone();

            //$(this)
			$formGroupClone.find('.btn-add-order')
                .toggleClass('btn-success btn-add-order btn-danger btn-remove-order')
                .html('<i class="fa fa-minus"></i>');

            $formGroupClone.find('.panel-payments').html('');
            $formGroupClone.find('.panel-order-list').html('');
            $formGroupClone.find('input').val('').attr('value','');
            $formGroupClone.find('select.bank option').removeAttr('selected');
            $formGroupClone.find('select.bank option[value=""]').prop('selected',true);
            $formGroupClone.insertAfter($formGroup);
			order.process();
			$(":input").inputmask();
			order.checkKey();
			datepick($(".pmt"));
			order.btn();
			onRemark();
		}
	
	var removeOrders = function(event){
            event.preventDefault();
            var $formGroup = $(this).closest('.panel-order');
            $formGroup.remove();
			order.total();
			order.btn();
		}
    
	$(document).on('click', '.btn-add-order', addOrders);
	$(document).on('click', '.btn-remove-order', removeOrders);

	// Table payments process price //
	//============================================================================================================//
	var payments = {
		received : function(){
			var received = 0;
			$('.received').each(function(e,i){
				var $row 	= $(this).closest('tr');
				var $bank   = $row.find('select.bank');
				var rec 	= $(this).val().replace(',','');
					rec 	= ( isNaN(rec) || rec === '' ) ? 0 : rec;
					console.log('rec => ' + rec);
				received 	=  parseFloat(received) + parseFloat( rec ) ;
				if($bank.val() == 'omise'){
					var $rc = $(this).val();
					var $charge  =  $row.find('input.charger').val(); //$rc* 3 / 100;
						received = parseFloat(received) +  parseFloat($charge);
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
			$('.total-pays').html( nb2( $remain ) );
			
			
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
					$nval;
					$(this).val( $nval.toFixed(2) );
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
		
	// Table order process price //
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
			order.totalPrice();
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
		
		total : function(){
			var total = 0;
			$( '.amount' ).each(function(e){
				total += parseFloat( $(this).text().replace(',','') );
			});
			
			$('.total').html( nb(total) );
		},
		
		totalPrice : function(){
			var m = 0;
			$('.total').each(function(i,o){
				m += parseFloat( $(this).text().replace(',','') );
			});
			console.log('m = ' + m);
			$('.total-order').html( nb( m ) );
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
		onSubmit : function(){
			$('form').on('submit',function(e){
				var user 		= $('input[name="userLevel"]').val(),
					saleStatus 	= $('.sale-status').val(),
					rmk 		= $('input[name="head-remark"]').val(),
					oStatus 	= $('input[name="status"]').val(),
					tk 			= $('.ticket_type');
				// on sale submit form order //
				
				// on OP sumit form order //
				if(user == 'op'){
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
					if( $('select[name="op_status"]').val() === '' || $('input[name="op_status"]').val() === null ){
							alert('Please select OP status');
							$('select[name="op_status"]').focus();
							e.preventDefault();
							return false;
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
			
			console.log('mode = ' + $('input[name="level-mode"]').val() );
			order.unActive('input.invoice');	
				if( mode == 'admin'){
					order.unUse('select.bank');
					order.unActive('input[type="text"]');	
				
					order.unActive('input[name="paid"]');
					order.unUse('button[name="btn-paid"]');
					
				}
				
				order.checkKey();
				order.unActive('input.charger,input[name="remain"]');
				//$('.btn-add-pays').hide();
				
			$('.confirm').on('click',function(e){
					if( !confirm('Please confirm to continue') ){
						e.preventDefault();
						return false;
					}
			});
			if( mode == 'account'){
				if(oStatus == '9'){
						console.log('9');
						order.unActive('input[name="paid"]');
						order.unUse('button[name="btn-paid"]');
				}				
				if( oStatus == '0' || oStatus == '9' ){
						console.log('1 0 9');
						order.unActive('input[name="less"]');
						order.unActive('input[name="delivery"]');
						order.unActive('.ticket');
						order.unActive('.type');
						order.unActive('.spect');
						order.unActive('.qty');
						order.unActive('.unit_price');
						datepick($(".pmt"));
					order.unActive('input.client_id');
					order.unActive('input.name');
				}			
			}
			if( mode == 'op'){
				var ship = 'input[name="shipping"]';
					order.tracking(ship);
					order.unActive('input.charger');
			}
			if(mode == 'sale'){
				$('#type_list').on('click','a',function(e){
					e.preventDefault();
					var btn = $(this).closest('.input-group-btn');
					btn.find('button[name="btn-paid"]')
								.html( $(this).text() + ' <span class="caret"></span>' );
					btn.find('input[name="paid_type"]').val( $(this).attr('href') );
					payments.onProcess();			
				});
				
				$('.ticket').tooltip({
						 placement: "top",
						 trigger: "focus"
					});

			}
		},
		
		saleStatus : function(val){
			if(val == 'cancel'){
				$('.comment').show();
			}else{
				$('.comment').hide();
			}
		},
	
		onStatus : function(){
			var st = $('.sale-status');
			order.saleStatus( st.val() );
			st.on('change',function(e){
				order.saleStatus( $(this).val() );
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
		},
		btn : function(){
			var btnPay = $('.btn-add-pays'),
				btnOrder = $('.btn-add-order'),
				recv 	= $('.received'),
				name 	= $('.name');
				if( recv.length > 1){
					btnOrder.hide();
				}else{
					btnOrder.show();
				}
				if(name.length > 1){
					btnPay.hide();	
				}else{
					btnPay.show();
				}
				console.log('recv => ' + recv.length +', name => ' + name.length);
		}
	}
	order.bank();
	order.active();
	order.onSubmit();
	if(mode == 'sale'){
		//order.process();
		//order.total();
		order.onStatus();
	}
	
}(jQuery));