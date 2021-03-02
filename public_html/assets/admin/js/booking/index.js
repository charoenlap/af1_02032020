
$(document).ready(function(){

	$('.mw-btn-choose-msg').on('click', function(){
		popupChooseMsg($('#mw-model-'+$(this).data('id')).data('model'), $('#mw-data-msg').data('model'));
	});

	$('.mw-btn-change-msg').on('click', function(){
		popupChooseMsg($('#mw-model-'+$(this).data('id')).data('model'), $('#mw-data-msg').data('model'));
	});

	$('.mw-btn-view-detail').on('click', function(){
		popupViewDetail($('#mw-model-'+$(this).data('id')).data('model'));
	});

	$('#mw-btn-search').on('click', function(){
		mwGetUrl(function(url){
			window.location.href = url;
		});
	});

	$('.mw-btn-clear-filter').on('click', function(){
		mwClearFilter();
	});
});

function mwClearFilter()
{
	$('.mw-input-search').val('');
	$('.mw-input-search-date').val(mwToday());
	$('#mw-search-status').val('all');
}

function mwToday()
{
 	var date = new Date();
	return date.getDate()+'/'+(date.getMonth()+1)+'/'+date.getFullYear();
}

function mwGetUrl(callback)
{
	var start = $('input[name=search_start_date]').val();
	if (start == '') start = mwToday();
	start = start.split('/');
	const start_date = start[2]+'-'+start[1]+'-'+start[0];

	var end = $('input[name=search_end_date]').val();
	if (end == '') end = mwToday();
	end = end.split('/');
	const end_date = end[2]+'-'+end[1]+'-'+end[0];

	const status = $('#mw-search-status').find('option:selected').val();
	const booking_key = $('input[name=search_booking_key]').val();
	const ctm_name = $('input[name=search_ctm_name]').val();
	const created_by = $('input[name=search_created_by]').val();
	const cs_name = $('input[name=search_cs_name]').val();
	const msg_name = $('input[name=search_msg_name]').val();

	var url = $('#mw-url-booking-index').data('url');
	var search = '';

	if (start_date == '' && end_date == '' && status == 'all') {

		return callback(url);

	} else {

		search = '?';
		if (start_date != '') search += '&start_date='+start_date;
		if (end_date != '') search += '&end_date='+end_date;
		if (status != '') search += '&status='+status;
		if (booking_key != '') search += '&booking_key='+booking_key;
		if (ctm_name != '') search += '&ctm_name='+ctm_name;
		if (created_by != '') search += '&created_by='+created_by;
		if (cs_name != '') search += '&cs_name='+cs_name;
		if (msg_name != '') search += '&msg_name='+msg_name;

		return callback(url+search);
	}
}


function popupViewDetail(booking)
{
	const html = genViewDetail(booking);

	const myBootBox = bootbox.dialog({
        className: 'mw-bootbox-booing-detail',
        title : 'Booking Key : '+booking.key,
        message : html,
        size: 'large',
        onEscape: true,
        backdrop: true,
    });

	$(myBootBox).on('click', '#mw-btn-remove', function(){
		mwPostRemoveBooking(booking);
	});
}

function mwPostRemoveBooking(booking)
{
	swal({
        title: 'คุณต้องการยกเลิกงาน <br/><small class="mw-black">'+booking.key+' '+booking.customer_name+'</small>',
        text: 'แน่ใจหรือไม่ ?',
        html: true,
        customClass: 'mw-bg-white',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'แน่ใจ',
        cancelButtonText: 'ไม่',
        closeOnConfirm: false,
        closeOnCancel: true,
    }, function(click_confirm) {

    	swalWaiting();

    	if (click_confirm) {


			$.ajax({
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				type: 'post',
				url: $('#mw-url-remove-booking').data('url'),
				data: {
					booking_id: booking.id,
				},
				success: function(result) {
					window.location.reload();
				},
				error: function(result) {

					swalStop();
					mwShowErrorMessage(result);
				}
			})
		}
	});
}

function genViewDetail(booking)
{
	const status_label = $('#mw-booking-status').data('value');
	var html = '';

	html += '<div class="row mw-center">';
	    html += '<div class="pearls row">';
	        html += '<div class="pearl col-xs-3 ';
			html += ((booking.status == 'pending' || booking.status == 'new' || booking.status == 'inprogress' || booking.status == 'complete') ? 'current' : '')+'">';
	            html += '<a href="#">';
	                html += '<div class="pearl-icon">';
	                    html += '<i class="icon fa-child" aria-hidden="true"></i>';
	                html += '</div>';
	                html += '<span class="hidden-xs pearl-title ';
					html += ((booking.status == 'pending') ? 'current' : '')+'">';
	                    html += '<small>รออนุมัติ</small>';
	                html += '</span>';
	            html += '</a>';
	        html += '</div>';
	        html += '<div class="pearl col-xs-3 ';
			html += ((booking.status == 'new' || booking.status == 'inprogress' || booking.status == 'complete') ? 'current' : '')+'">';
	            html += '<a href="#">';
	                html += '<div class="pearl-icon">';
	                    html += '<i class="icon fa-thumbs-o-up" aria-hidden="true"></i>';
	                html += '</div>';
	                html += '<span class="hidden-xs pearl-title ';
					html += ((booking.status == 'new') ? 'current' : '')+'">';
	                    html += '<small>เริ่มงานรับของ</small>';
	                html += '</span>';
	            html += '</a>';
	        html += '</div>';
	        html += '<div class="pearl col-xs-3 ';
			html += ((booking.status == 'inprogress' || booking.status == 'complete') ? 'current' : '')+'">';
	            html += '<a href="#">';
	                html += '<div class="pearl-icon">';
	                    html += '<i class="icon fa-motorcycle" aria-hidden="true"></i>';
	                html += '</div>';
	                html += '<span class="hidden-xs pearl-title ';
					html += ((booking.status == 'inprogress') ? 'current' : '')+'">';
	                    html += '<small>กำลังไปรับ</small>';
	                html += '</span>';
	            html += '</a>';
	        html += '</div>';
	        html += '<div class="pearl col-xs-3 ';
			html += ((booking.status == 'complete') ? 'current' : '')+'">';
	            html += '<a href="#">';
	                html += '<div class="pearl-icon">';
	                    html += '<i class="icon fa-check" aria-hidden="true"></i>';
	                html += '</div>';
	                html += '<span class="hidden-xs pearl-title ';
					html += ((booking.status == 'complete') ? 'current' : '')+'">';
	                    html += '<small>รับของเรียบร้อย</small>';
	                html += '</span>';
	            html += '</a>';
	        html += '</div>';
	    html += '</div>';
	html += '</div>';

	html += '<div style="margin-bottom: 30px">';

	html += '<h4>สถานะปัจจุบัน</h4>';
	html += '<h5 class="mw-left">';
	html += 'สถานะ : '+booking.status_label;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'เปิดงานโดย : '+booking.created_by;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'ผู้อนุมัติ : '+((booking.cs_name) ? booking.cs_name : '');
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'แมส : '+((booking.msg_name) ? booking.msg_name : '');
	html += '</h5>';
	html += '</div>';

	html += '<hr/>';
	html += '<h4>รายละเอียด</h4>';
	html += '<h5 class="mw-left">';
	html += 'ชื่อผู้ติดต่อ : '+booking.person_name;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'เบอร์ติดต่อ : '+booking.person_mobile;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'รหัสบริษัทลูกค้า : '+booking.customer_key;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'ชื่อบริษัทลูกค้า : '+booking.customer_name;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'สถานที่รับของ : '+booking.address+' '+booking.district+' '+booking.province;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'เวลารับสินค้า : '+booking.get_datetime_label;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'ประเภทงาน : '+booking.express_label;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'เก็บเงินปลายทาง : '+booking.is_cod;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'ขนาด : '+booking.size;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'หมายเหตุ : '+booking.note_that;
	html += '</h5>';

	html += '<hr/>';
	html += '<h4>ใบนำส่ง Connote</h4>';
	html += '<div class="row">';
	html += '<div class="col-sm-12">';
        html += '<table class="table table-bordered" id="table-list-connotes">';
            html += '<thead><tr>';
            html += '<th width="8%" class="mw-center mw-middle"><small>ลำดับ</small></th>';
            html += '<th width="14%" class="mw-center mw-middle"><small>เลขใบนำส่ง</small></th>';
            html += '<th width="18%" class="mw-middle"><small>ชื่อผู้ติดต่อ</small></th>';
            html += '<th width="26%" class="mw-middle"><small>ที่อยู่ปลายทาง</small></th>';
            html += '<th width="14%" class="mw-center mw-middle"><small>Customer Ref</small></th>';
            html += '<th width="14%" class="mw-center mw-middle"><small>ประเภทงาน <br/>(เก็บเงินปลายทาง)</small></th>';
            html += '<th width="8%" class="mw-center mw-middle"><small>พิมพ์</small></th>';
            html += '</tr></thead>'
            html += '<tbody>';

            
    //         $.each(booking.connotes, function(i, connote){
    //             html += '<tr>';
    //             html += '<td class="mw-center"><small>'+(i+1)+'</small></td>';
    //             html += '<td class="mw-center"><small>'+((connote.key) ? connote.key : '');
    //             html += '<p class="mw-mg-0 green-600"><small>'+((connote.status == 'confirm') ? '<i class="fa-check"></i> แมสรับของแล้ว' : '')+'</small></p></small></td>';
    //             html += '<td class=""><small>'+((connote.consignee_name) ? connote.consignee_name : '');
    //             html += ' @ '+((connote.consignee_company) ? connote.consignee_company : '')+'</small></td>';
    //             html += '<td class=""><small>'+connote.consignee_address+'</small></td>';
    //             html += '<td class="mw-center"><small>'+((connote.customer_ref) ? connote.customer_ref+'' : '-')+'</small></td>';
    //             html += '<td class="mw-center"><small>'+connote.service_label+((connote.cod_value) ? '<br/>('+connote.cod_value+' บาท)' : '')+'</small></td>';
    //             html += '<td class="mw-center"><a class="btn btn-success btn-icon mw-white" target="_blank" ';
    //             html += 'href="'+connote.url_pdf+'">';
 			// 	html += '<i class="fa-print" style="margin: 0 1%;"></i>';
				// html += '</a></td>';
    //             html += '</tr>';
    //         });

            html += '</tbody>';
        html += '</table>';
	html += '</div>';
	html += '</div>';

	html += '<hr/>';
	html += '<h4>การอัพเดทสถานะ</h4>';
	html += '<div class="row">';
	html += '<div class="col-sm-12">';
        html += '<table class="table table-bordered">';
            html += '<thead><tr>';
            html += '<th width="15%"><small>Status</small></th>';
            html += '<th width="25%"><small>Action by</small></th>';
            html += '<th width="25%"><small>Action at</small></th>';
            html += '<th width="35%"><small>Note</small></th>';
            html += '</tr></thead>'
            html += '<tbody>';
            $.each(booking.logs, function(i, log){
                html += '<tr>';
                html += '<td><small>'+status_label[log.status]+'</small></td>';
                html += '<td><small>'+log.action_by+'</small></td>';
                html += '<td><small>'+log.created_at.slice(0, -3).replace(' ', ' เวลา ')+'</small></td>';
                html += '<td><small>'+log.notes+'</small></td>';
                html += '</tr>';
            });
            html += '</tbody>';
        html += '</table>';
	html += '</div>';
	html += '</div>';

	if (booking.status != 'complete') {
		html += '<div class="row">';
		html += '<div class="col-sm-12 mw-right">';
			html += '<div id="mw-btn-remove" class="btn"><i class="fa-trash"></i></div>';
		html += '</div>';
		html += '</div>';
	}
	return html;
}

function popupChooseMsg(booking, msgs)
{
	const html = genChooseMsg(booking, msgs);

	myBootBox = bootbox.dialog({
        className: 'mw-bootbox-employee',
        title : 'เลือกแมสเซนเจอร์',
        message : html,
        size: 'large',
        onEscape: true,
        backdrop: true,
    });

    // $('.mw-select-employee').select2();
    $('.mw-bootbox-employee').on('click', '#mw-btn-confirm', function(){

    	// swalWaiting();
    	const msg_id = $('.mw-bootbox-employee').find('.mw-select-employee').find('option:selected').val();
    	if (msg_id != 0) { postUpdateMsg(booking, msg_id); }
    	else { bootbox.hideAll(); }
    });
}

function genChooseMsg(booking, msgs)
{
	var html = '';

	html += '<div style="margin-bottom: 30px">';
	html += '<h5 class="mw-left">';
	html += 'Booking Key : '+booking.key;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'ชื่อผู้ติดต่อ : '+booking.person_name;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'ชื่อบริษัทลูกค้า : '+booking.customer_name;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'สถานที่รับของ : '+booking.address;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'เขต จังหวัด : '+booking.district+' '+booking.province;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'เวลารับสินค้า : '+booking.get_datetime_label;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'แมสเซนเจอร์ : '+((!!booking.msg) ? booking.msg.nickname+' '+booking.msg_key : '');
	html += '</h5>';
	html += '</div>';

	if (booking.status == 'pending' || booking.status == 'new' || booking.status == 'inprogress') {

		html += '<div class="form-group">';
		html += '<label class="label-control">กำหนดแมสเซนเจอร์ที่ไปรับสินค้า</label>'
		html += '<select class="mw-select-employee form-control" style="width: 80%">';
		html += '<option value="0">ไม่เลือก</option>';
		$.each(msgs, function(i, msg){
			html += '<option value="'+msg.id+'"'
			html += ((msg.emp_key == booking.msg_key) ? 'selected' : '')+'>';
			html += msg.nickname+' '+msg.firstname+' '+msg.lastname;
			html += '</option>';
		});
		html += '</select>';

		html += '</div>';
		html += '<div class="form-group">';
		html += '<button id="mw-btn-confirm" class="btn btn-success" style="min-width: 100px">';
		html += '<i class="fa-save"> ยืนยัน</i></button>';
		html += '</div>';
	}

	return html;
}

function postUpdateMsg(booking, msg_id)
{
	swalWaiting();
	$.ajax({
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		type: 'post',
		url: $('#mw-url-choose-msg').data('url'),
		data: {
			booking_id: booking.id,
			msg_id: msg_id,
		},
		success: function(result) {
			window.location.reload();
		},
		error: function(result) {

			swalStop();
			mwShowErrorMessage(result);
		}
	})
}