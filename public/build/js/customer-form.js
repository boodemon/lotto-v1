var onRemark = function(){
	$('.btn-remark').on('click',function(e){
		var row = $(this).closest('.panel-order');
		var client_id = row.find('input.client_id');
		var remark = row.find('textarea.remark');
		if(client_id.val() == ''){
			alert('Please select client id ');
			client_id.focus();
			e.preventDefault();
			return false;
		}

		if( remark.val() == ''){
			alert('Please enter remark');
			remark.focus();
			e.preventDefault();
			return false;
		}
				
		$.ajax({
			url : _base + '/customer-remark/' + client_id.val() ,
			type : 'POST',
			data : {'_token':$('input[name="_token"]').val(), 'remark' : remark.val()},
			success : function(data){
				var rmk = JSON.parse( JSON.stringify(data) );
				var $html = '<i class="fa fa-user"></i> '
							+ '<strong>'+ rmk.user +'</strong> '
							+ '<i class="fa fa-clock-o"></i> '+ rmk.time +'<br>' + rmk.remark
							+ '<div class="ln_solid"></div>';
					row.find('.remark-display').append( $html );
					remark.val('');
					console.log(data);
			}
		});
	});
}

var onMode = function(){
	$('select[name="mode"]').on('change',function(e){
		window.location.href = $(this).val();
	});
}
onRemark();
onMode();
	 
// Remark form all user //	
var remarkDiv = document.getElementById("user-remark");
	console.log('remarkDiv : ' + remarkDiv);
	if( remarkDiv !== null && remarkDiv !== undefined ){
		var remarkHeight = remarkDiv.offsetHeight;
		if( remarkHeight > 350){
			console.log( remarkHeight );
			remarkDiv.style.height='350px';
			remarkDiv.style.overflowY  = "scroll";
			remarkDiv.scrollTop = remarkDiv.scrollHeight;
		}
	}
(function($){
	if( $('.ticket_type').length == 0 ){
		$('.tk-type').hide();
	}
	console.log($('.ticket_type').length +' number of ticket type');
}(jQuery));