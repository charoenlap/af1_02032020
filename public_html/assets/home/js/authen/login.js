$(document).ready(function()
{
    Site.run();

	// Click Login Button.
	$('#mw-btn-login').on('click', function() {

		swalWaiting();
		postLogin();
	});

	// Press Enter.
	$(document).keypress(function(e) {
	    if(e.which == 13) {
	   		swalWaiting();
			postLogin();
	    }
	});

});


function postLogin()
{
	const url_login = $('#mw-url-auth').data('value');
	const email 	= $.trim($('#mw-input-email').val());
	const pwd 		= md5($.trim($('#mw-input-pwd').val()));

	$.ajax({
		headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
		type: 'post',
		url: url_login,
		data: {
			'email': email,
			'password': pwd
		},
		success: function(result) {
			window.location.href = result;
		},
		error: function(result) {

			swalStop();
			mwShowErrorMessage(result);
		}
	})
}

function swalWaiting() {
    swal({title: '', html: true, imageUrl: '/assets/themes/remark/images/loading.gif', type:'', showConfirmButton: false});
}
function swalStop() {
    swal.close();
}

/* ERROR MESSAGE */
function mwShowErrorMessage(result) {

    if (result.status === 422) {

        $('.mw-text-error').html('');
        const errors = $.parseJSON(result.responseText);

        $.each(errors, function(key, value) {
            $('#form-error-'+key).html('* '+value[0]);
        });
    }

    if (result.status === 500) {
        $('.mw-text-error').html('* Error 500');
    }
}
