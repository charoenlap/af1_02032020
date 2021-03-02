var vueCustomer

$(document).ready(function(){

	const id = '#mw-model-customer';

	vueCustomer = new Vue({
		el: id,
		data: {
			ctmModel: $(id).data('model'),
			provinces: $(id).data('province'),
		},
		methods: {
			updateData: function(){
				swalWaiting();
				postUpdate();
			},
			updatePwd: function(){
				swalWaiting();
				postUpdatePwd();
			}
		},
	});

	$('#mw-input-key').on('input', function(){
		$(this).val(function() {
		    return this.value.replace(/[^a-zA-Z]/g, '').toUpperCase();
		});
	});

});

function postUpdate()
{
	$.ajax({
		headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
		type: 'POST',
		url: $('#mw-url-update').data('url'),
		data: {
			ctmModel: vueCustomer.ctmModel
		},
		success: function(result){
			window.location.href = result.url;
		},
		error: function(result){
			swalStop();
			mwShowErrorMessage(result);
		}
	});
}

function postUpdatePwd()
{
	$.ajax({
		headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
		type: 'POST',
		url: $('#mw-url-update-pwd').data('url'),
		data: {
			new_pwd: md5($('input[name=new_pwd]').val()),
			confirm_pwd: md5($('input[name=confirm_pwd]').val()),
		},
		success: function(result){
			window.location.href = result.url;
		},
		error: function(result){
			swalStop();
			mwShowErrorMessage(result);
		}
	});
}