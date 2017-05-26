(function($){
	datepick($('input[name="peroid"]'));
	var addPayments = function(event){
            event.preventDefault();
            var $formGroup = $(this).closest('tr');
            var $formGroupClone = $formGroup.clone();

            $(this)
                .toggleClass('btn-add btn-remove btn-success btn-danger')
                .html('<i class="fa fa-minus"></i>');
			var no = $(this).attr('rel');
			++no;
			console.log('no is ' + no);
            $formGroupClone.find('input[type="text"]').val('');
            $formGroupClone.find('input[type="text"]').prop('readonly',false);
            $formGroupClone.find('input[type="checkbox"]').prop('checked',false);
			$formGroupClone.find('input[data-name="number"]').attr('name','number[' + no +']');
			$formGroupClone.find('input[data-name="wingup"]').attr('name','wingup[' + no +']');
			$formGroupClone.find('input[data-name="wingdown"]').attr('name','wingdown[' + no +']');
			$formGroupClone.find('input[data-name="tang"]').attr('name','tang[' + no +']');
			$formGroupClone.find('input[data-name="tod"]').attr('name','tod[' + no +']');
			$formGroupClone.find('button').attr('rel', no);
            $formGroupClone.insertAfter($formGroup);
			$(":input").inputmask();
			onkey();
			onwing();
	}
	
	var removePayments = function(event){
            event.preventDefault();
            var $formGroup = $(this).closest('tr');
            $formGroup.remove();
	}
    
	$(document).on('click', '.btn-add', addPayments);
	$(document).on('click', '.btn-remove', removePayments);
	
	var amount = function(input){
			var row = input.closest('tr');
			var tangval 	= row.find('input.tang').val(),
				todval		= row.find('input.tod').val();
			var wing 	= row.find('input[type="checkbox"]:checked');
			
			var tang 	= ( tangval ==='' || tangval === null) ? 0 : (wing.length > 0 ? tangval * wing.length : tangval );
			var tod 	= ( todval ==='' || todval === null) ? 0 : todval;
			var sum 	= parseInt(tang) + parseInt(tod);
			row.find('.sum').html(sum);
			total();
	}
	
	var total = function(){
		var total = 0;
		$('.sum').each(function(i,v){
			total += parseInt($(this).html());
		});
		$('.total').html(total);
		remain( $('input[name="paid"]').val() );
	}
	
	var remain = function( paids ){
		var paid = ($('input[name="paid"]').val() === '' || $('input[name="paid"]').val() === null ) ? 0 : parseInt( $('input[name="paid"]').val() );
		var discount = ($('input[name="discount"]').val() === '' || $('input[name="discount"]').val() === null ) ? 0 : parseInt( $('input[name="discount"]').val() );
		var total = parseInt( $('.total').html() );
		var remain = parseInt(total)- ( parseInt( paid ) + parseInt( discount )) ;
		$('input[name="remain"]').val( remain );
	}
	
	var onkey = function(){
		$('.tang, .tod').on('blur keyup',function(e){
			amount($(this));
		});
		
		$('input[name="paid"]').on('blur keyup',function(e){
			remain( $(this).val() );
		});		
		
		$('input[name="discount"]').on('blur keyup',function(e){
			remain( $(this).val() );
		});
		
		$('.number').on('blur',function(e){
			var nrow = $(this).closest('tr');
			var wingup = nrow.find('.wingup'),
				wingdown = nrow.find('.wingdown');
			if( (wingup.is(':checked') || wingdown.is(':checked') ) && $(this).val().length > 1 ){
				alert('เลขวิ่ง สามารถป้อนได้หลักเดียวค่ะ');
				$(this).val( $(this).val().substr(0,1) ).focus();
			}
		});
		
	}
	onkey();
	
	var checkedwing = function( wingwing ){
			var row = wingwing.closest('tr');
		if( wingwing.is(':checked') || row.find('input[type="checkbox"]:checked').length > 0 ){
			var num = row.find('.number');
			if( num.val().length > 1 ){
				alert('เลขวิ่ง สามารถป้อนได้หลักเดียวค่ะ');
				num.val( num.val().substr(0,1) ).focus();
			}
				row.find('.tod').val(0).prop('readonly',true);
			
		}else{
			row.find('.tod').prop('readonly',false);
		}
		amount( row.find('.tang') );
	}
	
	var onwing = function(){
		$('.wingup').on('click',function(e){
			checkedwing($(this) );
		});
		
		$('.wingdown').on('click',function(e){
			checkedwing($(this) );
		});
	}
	onwing();
	
	var inputActive = function(){
		$('.number').each(function(i,v){
			console.log('number is '+ $(this).val() );
			checkedwing( $(this) );
		});
	}
	inputActive();
}(jQuery));