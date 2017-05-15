(function ($) {
	$(":input").inputmask();
	// Add Payment || One order to multi payments //
	//============================================================================================================//
	var addPayments = function(event){
            event.preventDefault();
            var $formGroup = $(this).closest('tr');
            var $formGroupClone = $formGroup.clone();

           // $(this)
                $formGroupClone.find('.btn-add-pays').toggleClass('color-blue btn-add-pays color-red btn-remove-pays')
                .html('<i class="fa fa-minus"></i>');

            $formGroupClone.find('.btn-remove-pays.reload').remove();
            $formGroupClone.find('input.charger').val('').hide();
            $formGroupClone.find('input.received').val('');
            $formGroupClone.find('input.pay_id').val('0');
            $formGroupClone.find('input.time').val('');
            $formGroupClone.find('select.bank option').removeAttr('selected');
            $formGroupClone.find('select.bank option[value=""]').prop('selected',true);
            $formGroupClone.insertAfter($formGroup);
			order.process();
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
			order.total();
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
	payments.remain();	// Table order process price //
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
			$('input.fee').on('click',function(e){
				$(this).select();
			});
			$('input.fee').on('blur',function(e){
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
			var acc 	= $('input[name="account_id"]').val();
			console.log('acc is ' + acc);
			//if(oStatus == '9' || acc != '0'){
					console.log('auto complete');
					$('input.client_id').autocomplete({
						source 		: _base + '/order-client',
						minLength 	: 2,
						select 		: function( event, ui){
							var row = $(this).closest('.panel-order');
							var div = row.find('.panel-order-list');
							loadTable( div, ui.item.id );
							
							row.find('input.name').val( ui.item.name );
							row.find('input.invoice').val( ui.item.invoice );
							order.btnSave();

							
							//return false;
						},
						change : function(event, ui){
							if(ui.item === null || ui.item === undefined){
								$('input[name="client_id"]').val('');
							}
						}
					});
					var loadTable = function(div,id){
						console.log(_base);
						
						div.html('<center><img src="'+ _base +'/public/images/loading.gif"/><br/>Loading...</center>');
						$.ajax({
							url : _base + '/order-table/' + id ,
							success : function(data){
								div.html(data);
								order.active();
								order.totalPrice();
							},
							error : function(){
								div.html('Error!! Please refresh page.');
							}
						});
					}
			//	}
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
		btnSave : function(){
			var btn = '<button class="btn btn-info confirm" type="submit" name="btn-save"><i class="fa fa-save"></i> Save</button>';
			$('.saving').html('');
			$('.saving:last').html(btn);
		},
		
		onSubmit : function(){
			$('form').on('submit',function(event){
				var user 		= $('input[name="userLevel"]').val(),
					saleStatus 	= $('.sale-status').val(),
					rmk 		= $('input[name="head-remark"]').val(),
					oStatus 	= $('input[name="status"]').val(),
					tk 			= $('.ticket_type'),
					$rcv			= $('.received'),
					$bank		= $('.bank'),
					$pmt		= $('.pmt'),
					$time		= $('.time');
				// on sale submit form order //
				if(saleStatus == 'cancel' && ( rmk === null || rmk === '' || rmk === undefined)){
					event.preventDefault();
					alert('Please input comment reason select cancel status.');
					$('input[name="head-remark"]').focus();
					return false;
				}
					
				if(saleStatus == '' || saleStatus === null || saleStatus === undefined){
					event.preventDefault();
					alert('Please select sale status');
					$('select[name="sale_status"]').focus();
					return false;
				}
				if( saleStatus == 'done'){
					$rcv.each(function(i,v){
						$rows = $(this).closest('tr');
						
						if($(this).val() === '' || $(this).val() === null){
							alert('Please input comment reason select cancel status.');
							$(this).focus();
							event.preventDefault();
							return false;
						}else if( $rows.find('.bank').val() === '' || $rows.find('.bank') === null ){
							alert('Please input bank');
							$rows.find('.bank').focus();
							event.preventDefault();
							return false;
						}else if( $rows.find('.pmt').val() === '' || $rows.find('.pmt') === null ){
							alert('Please input payment date');
							$rows.find('.pmt').focus();
							event.preventDefault();
							return false;
						}else if( $rows.find('.time').val() === '' || $rows.find('.time') === null ){
							alert('Please input time');
							$rows.find('.time').focus();
							event.preventDefault();
							return false;
							
						}
						
					});
				}
				
				if( tk.length > 0 ){
					tk.each(function(i,v){
						if( $(this).val() == '' ){
							alert('Please select ticket type');
							$(this).focus();
							e.preventDefault();
							return false;
						}
					});
				}
			});
		},

		active : function(){
			var oStatus = $('input[name="status"]').val();
			var user 	= $('input[name="userLevel"]').val();
			var acc 	= $('input[name="account_id"]').val();
			
			$('#type_list').on('click','a',function(e){
				e.preventDefault();
				var btn = $(this).closest('.input-group-btn');
				btn.find('button[name="btn-paid"]')
							.html( $(this).text() + ' <span class="caret"></span>' );
				btn.find('input[name="paid_type"]').val( $(this).attr('href') );
				payments.onProcess();			
			});

			order.bank();
			if( oStatus == '0' || oStatus == '1' ){
				order.unActive('.dep');
				order.unActive('.order');
				order.unActive('.unit_price');
				order.unActive('input.invoice');
				order.unActive('input[name="less"]');
				order.unActive('input[name="delivery"]');
				order.unActive('input[name="client_id"]');
				order.unActive('input.name');
				order.unActive('.ticket');
				order.unActive('.type');
				order.unActive('.spect');
				order.unActive('.qty');
				order.unActive('.unit_price');
				if(acc !== '0'){
					order.unUse('select.bank');
					order.unUse('.btn-paid_type');
					order.unActive('input[name="paid"]');
					order.unUse('#type_list');
					order.unActive('input.time');
					order.unActive('input.pmt');
					order.unActive('input.charger');
					order.unActive('input.received');
					$('.btn-add-pays').hide();
				}
			}else if( oStatus == '9' ){
				order.unActive('.dep');
				order.unActive('.order');
				order.unActive('.unit_price');
				order.unActive('input.name');
				order.unActive('input.invoice');
				order.unActive('input[name="less"]');
				order.unActive('input[name="delivery"]');
				order.unActive('input[name="paid"]');
				order.unActive('input.received');
				order.unUse('select.bank');
				order.unUse('.btn-paid_type');
				order.unUse('#type_list');
				order.unActive('input.time');
				order.unActive('input.pmt');
				order.unActive('input.charger');
				order.unActive('.ticket');
				order.unActive('.type');
				order.unActive('.spect');
				order.unActive('.qty');
				order.unActive('.unit_price');
			}else{ 
				order.unActive('input[type="text"],select');
			}

				
			$('.ticket').tooltip({
						 placement: "top",
						 trigger: "focus"
					});
				
			$('.confirm').click(function(e){
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
	
	order.process();
	order.total();
	order.onStatus();
	order.onSubmit();
	order.active();
	

}(jQuery));