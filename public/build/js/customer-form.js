(function($){
	datepick($('input[name="peroid"]'));
	var addPayments = function(event){
            event.preventDefault();
            var $formGroup = $(this).closest('tr');
            var $formGroupClone = $formGroup.clone();

            $(this)
                .toggleClass('btn-add btn-remove btn-success btn-danger')
                .html('<i class="fa fa-minus"></i>');

            $formGroupClone.find('input[type="text"]').val('');
            $formGroupClone.insertAfter($formGroup);
			$(":input").inputmask();
			onkey();
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
			var tang 	= ( tangval ==='' || tangval === null) ? 0 : tangval;
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
		var paid   = paids == '' || paids === undefined ? 0 : paids;
		var amount = parseInt( $('.total').html() );
		var remain = parseInt(amount) - parseInt( paid );
		$('input[name="remain"]').val( remain );
	}
	
	var onkey = function(){
		$('.tang, .tod').on('blur keyup',function(e){
			amount($(this));
			
		});
		
		$('input[name="paid"]').on('blur keyup',function(e){
			remain( $(this).val() );
		});
		
	}
	onkey();

}(jQuery));