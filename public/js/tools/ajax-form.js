var ajaxform = {
	form_id			: '',
	i				: 0,
	return_id		: '',
	start			: function(){
		var n = ++this.i;
		var formid 		= this.form_id;
		var form_data 	= this.form_id.serializeArray();
		var datato = {};
		$.each(form_data,function(i,f){
			var name 	= f.name;
			var val 	= f.value;
			datato[name] = val;
		});
		
		if(formid.find('#tinymce')){
			var eid = formid.find('#tinymce').attr('data-id');
			var ed = tinyMCE.get(eid);
			datato['detail'] = tinyMCE.activeEditor.getContent();
		}
		
			var j = JSON.stringify(datato);
			var jn = JSON.parse(j);
			var $_token =  jn._token;
			$.ajaxSetup({
				headers: { 'X-CSRF-TOKEN': $_token }
			});
	
			$.ajax({
					url : this.form_id.attr('action'),
					data : jn,
					type : 'POST',
					success:function(data){
						var key = $.trim(data);
						console.log('Return ID : ' + key);
						return ajaxform.return_id.val(key);	
					}
			});
	}
}