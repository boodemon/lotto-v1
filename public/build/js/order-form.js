(function ($) {
        var addFormGroup = function (event) {
            event.preventDefault();

            var $formGroup = $(this).closest('tr');
            var $formGroupClone = $formGroup.clone();
			var $num = parseInt($formGroupClone.find('input.category_sort').val()) + 1 ;
			

            $(this)
                .toggleClass('btn-success btn-add btn-danger btn-remove')
                .html('<i class="fa fa-minus"></i>');

            $formGroupClone.find('input').val('');
            $formGroupClone.find('.amount').html('0');
            $formGroupClone.insertAfter($formGroup);
			order.process();
        };

        var removeFormGroup = function (event) {
            event.preventDefault();
            var $formGroup = $(this).closest('tr');
            $formGroup.remove();
			order.total();
        };

        var countFormGroup = function ($form) {
            return $form.find('.form-group').length;
        };

        $(document).on('click', '.btn-add', addFormGroup);
        $(document).on('click', '.btn-remove', removeFormGroup);
	
	var datepick = function(input){
			input.datepicker({
				dateFormat: "yy-mm-dd"
			});
		
	}
	
	
	datepick($("#pmt"));
	datepick($(".dep"));
	
	$(":input").inputmask();
	
	var nb = function(nStr){
				nStr += '';
				x = nStr.split('.');
				x1 = x[0];
				x2 = x.length > 1 ? '.' + x[1] : '.00';
				var rgx = /(\d+)(\d{3})/;
				while (rgx.test(x1)) {
					x1 = x1.replace(rgx, '$1' + ',' + '$2');
				}
				return x1 ;//+ x2;
	};
	
	// Table order process price //
	//============================================================================================================//
	var order = {
		amount : function( _this ){
			var row 	= _this.closest('tr');
			
			var qty 	= order.qty( row ),
				price 	= order.price( row );
			var amount = parseInt(qty) * parseFloat( price);
			row.find('.amount').html( nb( amount ) );
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
		
		total : function(){
			var total = 0;
			$( '.amount' ).each(function(e){
				total += parseFloat( $(this).text().replace(',','') );
			});
			
			$('.total').html( nb(total) );
		},
		
		process :function( ){
			$('input.qty, input.unit_price').on('blur keyup',function(e){
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
			
			$('tr').on('click','td',function(e){
				e.preventDefault();
				$(this).find('a').trigger('click');
			});
			
			var oStatus = $('input[name="status"]').val();
			// Sale user process //
			if($('input[name="userLevel"]').val() == 'sale'){
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
						},
						error : function(){
							$('#order-list').html('Error!! Please refresh page.');
						}
					})
				}
			}
			
			
			// Account user process //
			if($('input[name="userLevel"]').val() == 'account'){
				$('input#paid, input#received ,input[name="less"]').on('blur keyup',function(e){
					order.remain();
				});
				order.remain();
				if(oStatus == 1 || oStatus == 0 || oStatus == 9){
					$('input[name="less"]').prop('disabled',true);
					$('input[name="pmt"]').prop('disabled',true);
					$('input[name="paid"]').prop('disabled',true);
				}
				if(oStatus == 3){
					$('input[name="less"]').prop('disabled',false);
					$('input[name="pmt"]').prop('disabled',false);
					$('input[name="paid"]').prop('disabled',false);
					$('input[name="received"]').prop('disabled',true);
					$('input[name="bank"]').prop('disabled',true);
					$('input[name="time"]').prop('disabled',true);
				}
			}
			// End Account user process //
		},
		remain : function(){
					var $paid 		= 	$('#paid').val() != '' ? $('#paid').val() : 0;
					var $received 	= 	$('#received').val() != '' ? $('#received').val() : 0;
					var $less 		= 	$('input[name="less"]').val() != '' ? $('input[name="less"]').val() : 0;
					var $total 		=	parseFloat($received) - (parseFloat($less) + parseFloat($paid));
					$('#remain').val( $total === NaN ? 0 : $total );
			
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
		}
	}
	
	order.process();
	order.total();
	
			$('.confirm').on('click',function(e){
				if( !confirm('Please confirm to continue') ){
					e.preventDefault();
					return false;
				}
			});
	
}(jQuery));