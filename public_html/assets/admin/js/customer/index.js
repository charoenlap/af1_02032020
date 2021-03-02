$(document).ready(function(){

	$('#mw-btn-search').on('click', function(){
		getUrl(function(url){
			window.location.href = url;
		});
	});

	$('#mw-table-customer').on('click', '.mw-btn-remove', function(){
		popupRemoveWarning($(this).data('url'), $(this).data('model'));
	});

	$('#mw-table-customer').on('click', '.mw-btn-detail', function(){
		popupEmpDetail($(this).data('model'));
	});
});

function popupEmpDetail(model)
{
	const html = genEmpDetail(model);

	myBootBox = bootbox.dialog({
        className: 'mw-bootbox-customer',
        title : model.name,
        message : html,
        size: 'large',
        onEscape: true,
        backdrop: true,
    });
}

function genEmpDetail(model)
{

	var html = '';
	html += '<div>';
	html += '<h5 class="mw-left">';
	html += 'รหัสลูกค้า : '+model.key;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'ชื่อลูกค้า : '+model.name;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'อีเมลล์ลูกค้า : '+model.email;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'ที่อยู่ : '+model.address+' '+model.district+' '+model.province+' '+model.postcode;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'ผู้ติดต่อ : '+model.person;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'เบอร์ติดต่อ : '+model.mobile;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'เบอร์บริษัท : '+model.office_tel;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'เบอร์แฟกซ์ : '+model.fax;
	html += '</h5>';
	html += '</div>';
	return html;
}

function popupRemoveWarning(url_remove, model)
{
	swal({
        title: 'คุณต้องการลบ <br/><small class="mw-black">'+model.name+'</small>',
        text: 'แน่ใจหรือไม่ ?',
        html: true,
        customClass: 'mw-bg-white',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'ลบ',
        cancelButtonText: 'ไม่ลบ',
        closeOnConfirm: false,
        closeOnCancel: true,
    }, function(click_confirm) {

    	swalWaiting();

    	if (click_confirm) {

			$.ajax({
				headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
				type: 'POST',
				url: url_remove,
				success: function(result) {
					window.location.reload();
				},
				error: function(result){
					swalStop();
					mwShowErrorMessage(result);
				}
			});
    	}
    });
}

function postRemove(ctm_id)
{
	swalWaiting();
	console.log(ctm_id);
}

function getUrl(callback)
{
	const key = $('input[name=search_cus_key]').val();
	const name = $('input[name=search_name]').val();

	var url = $('#mw-url-customer-index').data('url');
	var search = '';

	if (key == '' && name =='' ) {

		return callback(url);

	} else {

		search = '?';
		if (key != '') search += '&key='+key;
		if (name != '') search += '&name='+name;

		return callback(url+search);
	}

}