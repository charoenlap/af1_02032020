var vueEmployee

$(document).ready(function(){

	const id = '#mw-model-employee';

	vueEmployee = new Vue({
		el: id,
		data: {
			empModel: $(id).data('model'),
			posModels: $(id).data('position')
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
		}
	});

	$('#mw-input-key').on('input', function(){
		$(this).val(function() {
		    return this.value.replace(/[^a-zA-Z]/g, '').toUpperCase();
		});
	});
});

function postUpdate()
{
	const post_url 	= $('#mw-url-update').data('url');
	const data      = new FormData();

	data.append('empModel', JSON.stringify(vueEmployee.empModel));

	// Thumbnail.
    if (typeof($('.mw-input-upload-avatar')[0].files) !== 'undefined') {

        jQuery.each($('.mw-input-upload-avatar')[0].files, function(i, file) {
            data.append('avatar', file);
        });
    }

	$.ajax({
		headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
		type: 'POST',
		url: post_url,
		contentType: false,
        processData: false,
        cache: false,
		data: data,
		success: function(result){
			
			swalStop();

			if (result.status == 'success') {
				window.location.href = result.url;
			} else {
				window.location.reload();
			}
		},
		error: function(result){
			swalStop();
			console.log(result)
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
			new_pwd: $('input[name=new_pwd]').val(),
			confirm_pwd: $('input[name=confirm_pwd]').val(),
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