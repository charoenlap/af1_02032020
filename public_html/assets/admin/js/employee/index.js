$(document).ready(function(){

	$('#mw-btn-search').on('click', function(){

		mwGetUrl(function(url){
			window.location.href = url;
		});
	});

	$('#mw-table-order').on('click', '.mw-btn-remove', function(){
		popupRemoveWarning($(this).data('url'), $(this).data('model'));
	});

	$('#mw-table-order').on('click', '.mw-btn-detail', function(){
		popupEmpDetail($(this).data('model'));
	});
});

function popupEmpDetail(model)
{
	const html = genEmpDetail(model);

	myBootBox = bootbox.dialog({
        className: 'mw-bootbox-employee',
        title : model.nickname+' '+model.firstname+' '+model.lastname,
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
	html += 'รหัสพนักงาน : '+model.emp_key;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'ชื่อจริง : '+model.title+' '+model.firstname+' '+model.lastname;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'ชื่อเล่น : '+model.nickname;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'ตำแหน่ง : '+model.position.label;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'หมายเลขโทรศัพท์ : '+model.phone;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'ที่อยู่ : '+model.address;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'รหัสบัตรประจำตัวประชาชน : '+model.id_card;
	html += '</h5>';
	html += '</div>';
	return html;
}

function mwGetUrl(callback)
{
	const emp_key = $('input[name=search_emp_key]').val();
	const name = $('input[name=search_name]').val();
	const position = $('#mw-search-position').find('option:selected').val();

	var url = $('#mw-url-employee-index').data('url');
	var search = '';

	if (emp_key == '' && name == '' && position == 0) {

		return callback(url);

	} else {

		search = '?';
		if (emp_key != '') search += '&emp_key='+emp_key;
		if (name != '') search += '&name='+name;
		if (position != 0) search += '&position='+position;

		return callback(url+search);
	}

}

function popupRemoveWarning(url_remove, model)
{
	swal({
        title: 'คุณต้องการลบ <br/><small class="mw-black">'+model.nickname+'</small>',
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

function postRemove(emp_id)
{
	swalWaiting();
	console.log(emp_id);
}