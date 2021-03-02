$(document).ready(function(){

	$('#mw-btn-save').on('click', function(){
		swalWaiting();
		postPoint();
	});
});

function postPoint()
{
	const person 	= $('input[name=person]').val();
	const name 		= $('input[name=name]').val();
	const address 	= $('textarea[name=address]').val();
	const district 	= $('input[name=district]').val();
	const province 	= $('#mw-select-province').find('option:selected').val();
	const postcode 	= $('input[name=postcode]').val();
	const mobile 	= $('input[name=mobile]').val();

	$.ajax({
		headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').prop('content')},
		type: 'POST',
		url: $('#mw-url-point-update').data('url'),
		data: {
			person: person,
			name: name,
			address: address,
			district: district,
			province: province,
			postcode: postcode,
			mobile: mobile,
		},
		success: function(result) {
			window.location.href = result.url;
		},
		error: function(result) {
			swalStop();
            mwShowErrorMessage(result);
		}

	});
}