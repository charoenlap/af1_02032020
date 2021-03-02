$(document).ready(function(){

	$('.mw-btn-view-detail').on('click', function(){
		popupViewDetail($(this).data('model'));
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
	$('input[name=search_start_date]').val(mwToday());
	$('input[name=search_end_date]').val(mwToday());
	$('input[name=search_connote_key]').val('');
	$('input[name=search_shipper_name]').val('');
	$('input[name=search_consignee_name]').val('');
}

function mwToday()
{
 	var date = new Date();
	return date.getDate()+'/'+(date.getMonth()+1)+'/'+date.getFullYear();
}

function mwGetUrl(callback)
{
	const connote_key = $('input[name=search_connote_key]').val();
	const shipper_name = $('input[name=search_shipper_name]').val();
	const consignee_name = $('input[name=search_consignee_name]').val();
	const customer_ref = $('input[name=search_customer_ref]').val();

	var start = $('input[name=search_start_date]').val();
	if (start == '') start = mwToday();
	start = start.split('/');
	const start_date = start[2]+'-'+start[1]+'-'+start[0];

	var end = $('input[name=search_end_date]').val();
	if (end == '') end = mwToday();
	end = end.split('/');
	const end_date = end[2]+'-'+end[1]+'-'+end[0];

	var url = $('#mw-url-connote-index').data('url');
	var search = '?';

	if (start_date != '') search += '&start_date='+start_date;
	if (end_date != '') search += '&end_date='+end_date;
	if (connote_key != '') search += '&connote_key='+connote_key;
	if (shipper_name != '') search += '&shipper_name='+shipper_name;
	if (consignee_name != '') search += '&consignee_name='+consignee_name;
	if (customer_ref != '') search += '&customer_ref='+customer_ref;

	return callback(url+search);
}

function popupViewDetail(connote)
{
	const html = genViewDetail(connote);

	myBootBox = bootbox.dialog({
        className: 'mw-bootbox-booing-detail',
        title : 'Connote Key : '+(!!(connote.key) ? connote.key : ''),
        message : html,
        size: 'large',
        onEscape: true,
        backdrop: true,
    });
}

function genViewDetail(connote)
{
	var html = '';

	/*if (!connote.job) connote.job = {status: 'pending'}

	html += '<div class="row mw-center">';
		html += '<div class="pearls pearls row">';
			html += '<div class="pearl col-xs-3 current">';
				html += '<a href="#">';
				html += '<div class="pearl-icon">';
				html += '<i class="icon fa-dropbox" aria-hidden="true"></i>';
				html += '</div>';
				html += '<span class="hidden-xs pearl-title ';
				html += ((connote.job.status == 'pending') ? 'current' : '')+'">';
				html += '<small>จัดเตรียมพัสดุ</small>';
				html += '</span>';
				html += '</a>';
			html += '</div>';
			html += '<div class="pearl col-xs-3 ';
			html += ((connote.job.status == 'new' || connote.job.status == 'inprogress' || connote.job.status == 'complete') ? 'current' : '')+'">';
				html += '<a href="#">';
				html += '<div class="pearl-icon">';
				html += '<i class="icon fa-thumbs-o-up" aria-hidden="true"></i>';
				html += '</div>';
				html += '<span class="hidden-xs pearl-title ';
				html += ((connote.job.status == 'new') ? 'current' : '')+'">';
				html += '<small>ตรวจสอบข้อมูล</small>';
				html += '</span>';
				html += '</a>';
			html += '</div>';
			html += '<div class="pearl col-xs-3 ';
			html += ((connote.job.status == 'inprogress' || connote.job.status == 'complete') ? 'current' : '')+'">';
				html += '<a href="#">';
				html += '<div class="pearl-icon">';
				html += '<i class="icon fa-motorcycle" aria-hidden="true"></i>';
				html += '</div>';
				html += '<span class="hidden-xs pearl-title ';
				html += ((connote.job.status == 'inprogress') ? 'current' : '')+'">';
				html += '<small>กำลังไปส่ง</small>';
				html += '</span>';
				html += '</a>';
			html += '</div>';
			html += '<div class="pearl col-xs-3 ';
			html += ((connote.job.status == 'complete') ? 'current' : '')+'">';
				html += '<a href="#">';
				html += '<div class="pearl-icon">';
				html += '<i class="icon fa-check" aria-hidden="true"></i>';
				html += '</div>';
				html += '<span class="hidden-xs pearl-title ';
				html += ((connote.job.status == 'complete') ? 'current' : '')+'">';
				html += '<small>ส่งของเรียบร้อย</small>';
				html += '</span>';
				html += '</a>';
			html += '</div>';
		html += '</div>';
	html += '</div>';*/

	html += '<div style="margin-bottom: 30px">';
	html += '<h4>ผู้ส่ง</h4>';
	html += '<h5 class="mw-left">';
	html += 'ชื่อผู้ส่ง : '+connote.shipper_name;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'บริษัทผู้ส่ง : '+connote.shipper_company;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'ที่อยู่ผู้ส่ง : '+connote.shipper_address;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += '<h5 class="mw-left">';
	html += 'เบอร์โทรศัพท์ผู้ส่ง : '+connote.shipper_phone;
	html += '</h5>';
	html += '<hr />';
	html += '<h4>ผู้รับ</h4>';
	html += '<h5 class="mw-left">';
	html += 'ชื่อผู้รับ : '+connote.consignee_name;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'บริษัทผู้รับ : '+connote.consignee_company;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'ที่อยู่ผู้รับ : '+connote.consignee_address;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += '<h5 class="mw-left">';
	html += 'เบอร์โทรศัพท์ผู้รับ : '+connote.consignee_phone;
	html += '</h5>';
	html += '</div>';
	html += '<hr />';
	html += '<h4>รายละเอียด</h4>';
	html += '<h5 class="mw-left">';
	html += 'ประเภท : '+connote.service_label;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'งานด่วน : '+connote.express_label;
	html += '</h5>';
	html += '<h5 class="mw-left">';
	html += 'เก็บเงินปลายทาง : '+((connote.cod_value) ? connote.cod_value : '-');
	html += '</h5>';

	return html;
}