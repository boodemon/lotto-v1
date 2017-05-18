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
	}
	
	var removePayments = function(event){
            event.preventDefault();
            var $formGroup = $(this).closest('tr');
            $formGroup.remove();
	}
    
	$(document).on('click', '.btn-add', addPayments);
	$(document).on('click', '.btn-remove', removePayments);
	
	var amount = function(tang){
			var row = tang.closest('tr');
			var mTang 	= tang.val(),
				mTod	= row.find('input.tod').val();
	}

}(jQuery));