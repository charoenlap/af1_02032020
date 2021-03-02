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
	// alert(1);
	const url_login = $('#mw-url-auth').data('value');
	const emp_id 	= $.trim($('#mw-input-empid').val());
	const pwd 		= $.trim($('#mw-input-pwd').val());
	// alert($('meta[name="csrf-token"]').attr('content'));
	$.ajax({
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		type: 'post',
		url: url_login,
		data: {
			'emp_id': emp_id,
			'password': pwd
		},
		success: function(result) {
			// alert(result);
			console.log(result);
			window.location.href = result;
		},
		error: function(result) {
			console.log(url_login);
			console.log(result);
			swalStop();
			if (result.status === 422) {
				mwShowErrorMessage(result)
			}
		}
	})
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

/*SWEET ALERT*/
function swalWaiting() {
    swal({title: '', html: true, imageUrl: '/assets/admin/images/mw_loading.gif', type:'', showConfirmButton: false});
}
function swalProcess() {
    swal({title:'', text: 'Processing', imageUrl: '/assets/themes/remark/images/loading.gif', type:'info', showConfirmButton: false});
}
function swalSuccess() {
    swal({title:'', text: 'Submit Successful', type:'success', confirmButtonText:  'OK', timer: 10000});
}
function swalSuccessCustom(title, msg) {
    swal({title:title, text: msg, type:'success', timer: 5000});
}
function swalStop() {
    swal.close();
}
