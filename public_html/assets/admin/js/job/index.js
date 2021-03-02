$(document).ready(function(){

	$('.mw-btn-view-detail').on('click', function(){
		popupViewDetail($('#mw-model-'+$(this).data('id')).data('model'));
	});

	$('.mw-btn-approve').on('click', function(){
		mwApproveJob($(this).data('id'));
	});

	$('.mw-btn-send').on('click', function(){
		popupSendJob($(this).data('id'));
	});

	$('.mw-btn-change-photo').on('click', function(){
		popupChangePhoto($('#mw-model-'+$(this).data('id')).data('model'));
	});

	$('#mw-btn-search').on('click', function(){

		mwGetUrl(function(url){
			window.location.href = url;
		});
	});

	$('.mw-btn-clear-filter').on('click', function(){
		mwClearFilter();
	});

	$('.mw-btn-choose-msg').on('click', function(){
		popupChooseMsg($('#mw-model-'+$(this).data('id')).data('model'), $('#mw-data-msg').data('model'));
	});
});

function mwApproveJob(job_id)
{
	swalWaiting();

	$.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'post',
        url: $('#mw-url-approve').data('url'),
        data: {
        	job_id: job_id
        },
        success: function(result) {
        	if (result.status == 'success') {
	        	window.location.reload();
        	}
        },
        error: function(result) {
            swalStop();
            mwShowErrorMessage(result);
        }
    });
}

function popupSendJob(job_id)
{
	const html = genSendJob(job_id);

	myBootBox = bootbox.dialog({
        className: 'mw-bootbox-send',
        title : 'ยืนยันการส่ง',
        message : html,
        size: 'large',
        onEscape: true,
        backdrop: true,
    });

    // $('.mw-select-employee').select2();
    $(myBootBox).on('click', '#mw-btn-confirm', function(){

    	// swalWaiting();
    	const receiver_name = $(myBootBox).find('input[name=receiver_name]').val();
    	mwSendJob(job_id, receiver_name);
    });
}

function genSendJob(jobModel, msgs)
{
	var html = '';

	html += '<div class="form-group">';
	html += '<label class="label-control">กรุณาใส่ชื่อผู้เซ็นรับของ</label>'
	html += '<input type="text" name="receiver_name" class="form-control">';
	html += '</div>';
	html += '<div class="form-group">';
	html += '<button id="mw-btn-confirm" class="btn btn-success" style="min-width: 100px">';
	html += '<i class="fa-save"> ยืนยัน</i></button>';
	html += '</div>';

	return html;
}

function mwSendJob(job_id, receiver_name)
{
	swalWaiting();

	$.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'post',
        url: $('#mw-url-send').data('url'),
        data: {
        	job_id: job_id,
        	receiver_name: receiver_name
        },
        success: function(result) {
        	if (result.status == 'success') {
	        	window.location.reload();
        	}
        },
        error: function(result) {
            swalStop();
            mwShowErrorMessage(result);
        }
    });
}

function popupChooseMsg(jobModel, msgs)
{
	const html = genChooseMsg(jobModel, msgs);

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
    	if (msg_id != 0) { postUpdateMsg(jobModel, msg_id); }
    	else { bootbox.hideAll(); }
    });
}

function genChooseMsg(jobModel, msgs)
{
	var html = '';

	html += '<div class="form-group">';
	html += '<label class="label-control">กำหนดแมสเซนเจอร์ที่ไปรับสินค้า</label>'
	html += '<select class="mw-select-employee form-control" style="width: 80%">';
	html += '<option value="0">ไม่เลือก</option>';
	$.each(msgs, function(i, msg){
		html += '<option value="'+msg.id+'"'
		html += ((msg.emp_key == jobModel.msg_key) ? 'selected' : '')+'>';
		html += msg.nickname+' '+msg.firstname+' '+msg.lastname;
		html += '</option>';
	});
	html += '</select>';

	html += '</div>';
	html += '<div class="form-group">';
	html += '<button id="mw-btn-confirm" class="btn btn-success" style="min-width: 100px">';
	html += '<i class="fa-save"> ยืนยัน</i></button>';
	html += '</div>';

	return html;
}

function postUpdateMsg(jobModel, msg_id)
{
	swalWaiting();

	$.ajax({
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		type: 'post',
		url: $('#mw-url-choose-msg').data('url'),
		data: {
			job_id: jobModel.id,
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
	const connote_key = $('input[name=search_connote_key]').val();
	const booking_key = $('input[name=search_booking_key]').val();
	const customer_ref = $('input[name=search_customer_ref]').val();
	const sup_name = $('input[name=search_sup_name]').val();
	const msg_name = $('input[name=search_msg_name]').val();

	var url = $('#mw-url-job-index').data('url');
	var search = '';

	if (start_date == '' && end_date == '' && status == 'all') {

		return callback(url);

	} else {

		search = '?';
		if (start_date != '') search += '&start_date='+start_date;
		if (end_date != '') search += '&end_date='+end_date;
		if (status != '') search += '&status='+status;
		if (connote_key != '') search += '&connote_key='+connote_key;
		if (booking_key != '') search += '&booking_key='+booking_key;
		if (customer_ref != '') search += '&customer_ref='+customer_ref;
		if (sup_name != '') search += '&sup_name='+sup_name;
		if (msg_name != '') search += '&msg_name='+msg_name;

		return callback(url+search);
	}
}


function popupViewDetail(job)
{
	const html = genViewDetail(job);

	myBootBox = bootbox.dialog({
        className: 'mw-bootbox-booing-detail',
        title : 'Job Key : '+job.key+((!!job.connote.job_send) ? ' <small class="red-600">[ งาน RETURN ]</small>' : ''),
        message : html,
        size: 'large',
        onEscape: true,
        backdrop: true,
    });
}

function genViewDetail(job)
{
	const status_label = $('#mw-job-status').data('value');

	var html = '';

	html += '<div class="row mw-center">';
		html += '<div class="pearls pearls row">';
			html += '<div class="pearl col-xs-3 current">';
				html += '<a href="#">';
				html += '<div class="pearl-icon">';
				html += '<i class="icon fa-dropbox" aria-hidden="true"></i>';
				html += '</div>';
				html += '<span class="hidden-xs pearl-title ';
				html += ((job.status == 'pending') ? 'current' : '')+'">';
				html += '<small>จัดเตรียมพัสดุ</small>';
				html += '</span>';
				html += '</a>';
			html += '</div>';
			html += '<div class="pearl col-xs-3 ';
			html += ((job.status == 'new' || job.status == 'inprogress' || job.status == 'complete') ? 'current' : '')+'">';
				html += '<a href="#">';
				html += '<div class="pearl-icon">';
				html += '<i class="icon fa-thumbs-o-up" aria-hidden="true"></i>';
				html += '</div>';
				html += '<span class="hidden-xs pearl-title ';
				html += ((job.status == 'new') ? 'current' : '')+'">';
				html += '<small>รออนุมัติ</small>';
				html += '</span>';
				html += '</a>';
			html += '</div>';
			html += '<div class="pearl col-xs-3 ';
			html += ((job.status == 'inprogress' || job.status == 'complete') ? 'current' : '')+'">';
				html += '<a href="#">';
				html += '<div class="pearl-icon">';
				html += '<i class="icon fa-motorcycle" aria-hidden="true"></i>';
				html += '</div>';
				html += '<span class="hidden-xs pearl-title ';
				html += ((job.status == 'inprogress') ? 'current' : '')+'">';
				html += '<small>กำลังไปส่ง</small>';
				html += '</span>';
				html += '</a>';
			html += '</div>';
			html += '<div class="pearl col-xs-3 ';
			html += ((job.status == 'complete') ? 'current' : '')+'">';
				html += '<a href="#">';
				html += '<div class="pearl-icon">';
				html += '<i class="icon fa-check" aria-hidden="true"></i>';
				html += '</div>';
				html += '<span class="hidden-xs pearl-title ';
				html += ((job.status == 'complete') ? 'current' : '')+'">';
				html += '<small>ส่งของเรียบร้อย</small>';
				html += '</span>';
				html += '</a>';
			html += '</div>';
		html += '</div>';
	html += '</div>';

	html += '<div style="margin-bottom: 30px">';
	if(job.connote.job_send){
		html += '<h4>สถานะการส่งของ '+((!!job.connote.job_send) ? ' (ขากลับ)' : '')+'</h4>';
	}
		html += '<div class="row">';
			html += '<div class="col-sm-6">';
			html += '<h5 class="mw-left">';
			html += 'สถานะ : <span class="blue-800">'+job.status_label+'</span>';
			html += '</h5>';
			html += '<h5 class="mw-left">';
			html += 'ชื่อผู้เซ็นรับของ : '+((job.receiver_name) ? job.receiver_name : '');
			html += '</h5>';
			html += '<h5 class="mw-left">';
			html += 'เวลาที่ส่งสินค้า : '+((job.received_label) ? job.received_label : '')
			html += '</h5>';
			html += '<h5 class="mw-left">';
			html += 'ค่าบริการเพิ่มเติม : '+((job.topup_display) ? job.topup_display : '')
			html += '</h5>';
			html += '<h5 class="mw-left">';
			html += 'หมายเหตุ : '+((job.notes) ? job.notes : '')
			html += '</h5>';
			html += '<h5 class="mw-left">';
			html += 'พิกัด : '+((job.lat) ? job.lat : '')+' : '+((job.lng) ? job.lng : '')
			html += '</h5>';
			html += '</div>';
			html += '<div class="col-sm-4">';
			if (!job.connote.job_send) {
				html += '<h5 class="mw-left">';
				html += 'รูปถ่าย : ';
				html += '</h5>';
				html += '<a href="'+job.photo_url+'" target="_blank">';
				html += '<img width="100%" src="'+job.photo_url+'" />'
				html += '</a>';
			}
			html += '</div>';
		html += '</div>';
		html += '<hr/>';

		if (job.connote.job_send) {
			html += '<h4>สถานะการส่งของ (ขาไป)</h4>';
			html += '<div class="row">';
				html += '<div class="col-sm-6">';
					html += '<h5 class="mw-left">';
					html += 'สถานะ : <span class="blue-800">'+job.connote.job_send.status_label+'</span>';
					html += '</h5>';
					html += '<h5 class="mw-left">';
					html += 'ชื่อผู้เซ็นรับของ : '+((job.connote.job_send.receiver_name) ? job.connote.job_send.receiver_name : '');
					html += '</h5>';
					html += '<h5 class="mw-left">';
					html += 'เวลาที่ส่งสินค้า : '+((job.connote.job_send.received_label) ? job.connote.job_send.received_label : '')
					html += '</h5>';
					html += '<h5 class="mw-left">';
					html += 'ค่าบริการเพิ่มเติม : '+((job.connote.job_send.topup) ? job.connote.job_send.topup : '')
					html += '</h5>';
					html += '<h5 class="mw-left">';
					html += 'หมายเหตุ : '+((job.connote.job_send.notes) ? job.connote.job_send.notes : '')
					html += '</h5>';
					html += '<h5 class="mw-left">';
					html += 'พิกัด : '+((job.lat) ? job.lat : '')+' : '+((job.lng) ? job.lng : '')
					html += '</h5>';
				html += '</div>';
				html += '<div class="col-sm-4">';
					html += '<h5 class="mw-left">';
					html += 'รูปถ่าย : ';
					html += '</h5>';
					html += '<a href="'+job.connote.job_send.photo_url+'" target="_blank">';
					html += '<img width="100%" src="'+job.connote.job_send.photo_url+'" />'
					html += '</a>';
				html += '</div>';
			html += '</div>';
			html += '<hr/>';
		}
		html += '<h4>รายละเอียดผู้ส่ง Shipper</h4>';
		html += '<h5 class="mw-left">';
		html += 'Booking No. : '+job.connote.booking.key;
		html += '</h5>';
		html += '<h5 class="mw-left">';
		html += 'ประเภทงาน : '+job.connote.service_label;
		html += '</h5>';
		html += '<h5 class="mw-left">';
		html += 'ชื่อผู้ส่ง : '+job.connote.shipper_name;
		html += '</h5>';
		html += '<h5 class="mw-left">';
		html += 'บริษัทผู้ส่ง : '+job.connote.shipper_company;
		html += '</h5>';
		html += '<h5 class="mw-left">';
		html += 'ที่อยู่ผู้ส่ง : '+job.connote.shipper_address;
		html += '</h5>';
		html += '<h5 class="mw-left">';
		html += 'เบอร์ติดต่อ : '+job.connote.shipper_phone;
		html += '</h5>';
		html += '<h5 class="mw-left">';
		html += 'เวลาที่แมสเริ่มไปรับของ : '+((!!job.connote.booking.inprogress_datetime_label) ? job.connote.booking.inprogress_datetime_label : '')
		html += '</h5>';
		html += '<h5 class="mw-left">';
		html += 'เวลาที่แมสรับของสำเร็จ : '+((!!job.connote.booking.complete_datetime_label) ? job.connote.booking.complete_datetime_label : '')
		html += '</h5>';
		html += '<hr/>';
		html += '<h4>รายละเอียดผู้รับ Consignee</h4>';
		html += '<h5 class="mw-left">';
		html += 'ชื่อผู้รับ : '+((job.connote.consignee_name) ? job.connote.consignee_name : '');
		html += '</h5>';
		html += '<h5 class="mw-left">';
		html += 'บริษัทผู้รับ : '+((job.connote.consignee_company) ? job.connote.consignee_company : '');
		html += '</h5>';
		html += '<h5 class="mw-left">';
		html += 'ที่อยู่ผู้รับ : '+((job.connote.consignee_address) ? job.connote.consignee_address : '');
		html += '</h5>';
		html += '<h5 class="mw-left">';
		html += 'เบอร์ติดต่อ : '+job.connote.consignee_phone;
		html += '</h5>';
		html += '<hr/>';
		html += '<h4>ผู้ดำเนินงาน</h4>';
		html += '<h5 class="mw-left">';
		html += 'ผู้อนุมัติ : '+((job.sup_name) ? job.sup_name : '');
		html += '</h5>';
		html += '<h5 class="mw-left">';
		html += 'แมส : '+((job.msg_name) ? job.msg_name : '');
		html += '</h5>';
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
            html += '<th width="35%"><small>Flow</small></th>';
            html += '</tr></thead>'
            html += '<tbody>';
            $.each(job.logs, function(i, log){
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

	return html;
}


function popupChangePhoto(job)
{
	const html = genChangePhoto(job);

	const data  = new FormData();

	myBootBox = bootbox.dialog({
        className: 'mw-bootbox-booing-detail',
        title : 'Job Key : '+job.key+((!!job.connote.job_send) ? ' <small class="red-600">[ งาน RETURN ]</small>' : ''),
        message : html,
        size: 'large',
        onEscape: true,
        backdrop: true,
    });

    $(myBootBox).on('click', '#mw-btn-update', function(){

	    // PHOTO.
	    if (typeof($(myBootBox).find('.mw-input-upload-photo')[0].files) !== 'undefined') {

	        jQuery.each($(myBootBox).find('.mw-input-upload-photo')[0].files, function(i, file) {
	            data.append('photo', file);
	        });
	    }

	    data.append('model', JSON.stringify(job));

	    mwPostChangePhoto(data);
    });

    $(myBootBox).on('click', '#mw-btn-cancel', function(){
        bootbox.hideAll();
    });
}

function mwPostChangePhoto(data)
{
	swalWaiting();

	$.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'post',
        url: $('#mw-url-change-photo').data('url'),
        contentType: false,
        processData: false,
        cache: false,
        data: data,
        success: function(result) {

            bootbox.hideAll();

        	if (result.status == 'success') {
	        	window.location.reload();
        	} else {
        		swalStop();
        	}
        },
        error: function(result) {
            swalStop();
            mwShowErrorMessage(result);
        }
    });
}

function genChangePhoto(job)
{
	const status_label = $('#mw-job-status').data('value');

	var html = '';

	html += '<div style="margin-bottom: 30px">';
		html += '<h4>เปลี่ยนรูปถ่าย </h4>';
		html += '<div class="row">';
			html += '<div class="col-sm-6">';
				html += '<h5 class="mw-left">';
				html += 'รูปถ่ายเดิม : ';
				html += '</h5>';
				html += '<a href="'+job.photo_url+'" target="_blank">';
				html += '<img width="100%" src="'+job.photo_url+'" />'
				html += '</a>';
			html += '</div>';
			html += '<div class="col-sm-6">';
			html += '<h5 class="mw-left">';
			html += 'เพิ่มรูปถ่ายใหม่ : </span>';
			html += '</h5>';
			html += '<input type="file" name="pic" class="mw-input-upload-photo">';
			html += '</div>';
		html += '</div>';
		html += '<hr/>';
		html += '<div class="row">';
		    html += '<div class="col-sm-12 mw-right">';
		        html += '<div id="mw-btn-cancel" class="btn btn-default mw-width-120" style="margin-right: 10px">';
		        html += 'ยกเลิก</div>';
		        html += '<div id="mw-btn-update" class="btn btn-success mw-width-120">';
		        html += '<i class="fa-save"></i> บันทึก</div>';
		    html += '</div>';
		html += '</div>';
	html += '</div>';

	return html;
}