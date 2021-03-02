var vueConnote

$(document).ready(function(){

	const id = '#mw-model-connote';

	vueConnote = new Vue({
		el: id,
		data: {
			connoteModel: $(id).data('model'),
		},
		methods: {
			updateData: function(){
				swalWaiting();
				postUpdate();
			},
			showPoint: function(){
				mwPopupFormChoosePoint(this.connoteModel.booking.customer.points);
			},
			addNewPoint: function() {
				mwPopupFormAddNewPoint(function(connoteModel, pointModel){
					vueConnote.connoteModel = connoteModel;
					mwMapPointToConsignee(pointModel);
				});
			},
			cancelConnote: function() {
				mwCancelConnote(this.connoteModel);
			}
		}
	});
});

function mwCancelConnote(model)
{
	swal({
        title: 'คุณต้องการลบ Connote <br/>'+model.key,
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
				url: $('#mw-url-cancel-connote').data('url'),
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

function mwPopupFormAddNewPoint(callback)
{
	const html = mwGenFormAddNewPoint();

	const myBootBox = bootbox.dialog({
        className: 'mw-bootbox-new-points',
        title : 'เพิ่มที่อยู่ปลายทาง',
        message : html,
        size: 'large',
        onEscape: true,
        backdrop: true,
    });

    $(myBootBox).on('click', '#mw-btn-add-new-point', function(){

    	$.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
			type: 'POST',
			url: $('#mw-url-create-point').data('url'),
			data: {
				person:  $(myBootBox).find('input[name=person]').val(),
				name:  $(myBootBox).find('input[name=name]').val(),
				address:  $(myBootBox).find('textarea[name=address]').val(),
				district:  $(myBootBox).find('input[name=district]').val(),
				province:  $(myBootBox).find('#mw-select-province').find('option:selected').val(),
				postcode:  $(myBootBox).find('input[name=postcode]').val(),
				mobile:  $(myBootBox).find('input[name=mobile]').val(),
			},
			success: function(result){

				if (result.status == 'success') {

					const point = result.pointModel;

					$.ajax({
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						type: 'POST',
						url: $('#mw-url-gen-connote').data('url'),
						data: {
							connoteModel: vueConnote.connoteModel
						},
						success: function(result){

							if (result.status == 'success') {
								bootbox.hideAll();
								return callback(result.connoteModel, point);
							}
							swalStop();
						},
						error: function(result){

						}
					});
				}
			},
			error: function(result){
				swalStop();
				console.log(result)
				mwShowErrorMessage(result);
			}
		});
    });
}

function mwGenFormAddNewPoint()
{
	const provinces = $('#mw-data-province').data('content');

	var html = '';
 	html += '<div class="row">';
    html += '<div class="col-sm-8 col-xs-12">';
        html += '<div class="form-group">';
            html += '<span class="control-label">ชื่อผู้รับ</span>';
            html += '<input name="person" type="text" class="form-control" value=""/>';
            html += '<div class="mw-text-error" id="form-error-person"></div>';
        html += '</div>';
        html += '<div class="form-group">';
            html += '<span class="control-label">ชื่อบริษัท</span>';
            html += '<input name="name" type="text" class="form-control" value=""/>';
            html += '<div class="mw-text-error" id="form-error-name"></div>';
        html += '</div>';
        html += '<div class="form-group">';
            html += '<span class="control-label">ที่อยู่</span>';
            html += '<textarea name="address" type="text" class="form-control"></textarea>';
            html += '<div class="mw-text-error" id="form-error-address"></div>';
            html += '</div>';
        html += '<div class="form-group">';
            html += '<span class="control-label">อำเภอ / เขต</span>';
            html += '<input name="district" type="text" class="form-control" value=""/>';
            html += '<div class="mw-text-error" id="form-error-district"></div>';
        html += '</div>';
        html += '<div class="form-group">';
            html += '<span class="control-label">จังหวัด</span>';
            html += '<select class="form-control" id="mw-select-province">';
            	// html += '<option value="กรุงเทพมหานคร">กรุงเทพมหานคร</option>'
                $.each(provinces, function(i, province){
            		html += '<option value="'+province+'">'+province+'</option>';
            	});
            html += '</select>';
        html += '</div>';
        html += '<div class="form-group">';
            html += '<span class="control-label">รหัสไปรษณีย์</span>';
            html += '<input name="postcode" type="text" class="form-control" value=""/>';
            html += '<div class="mw-text-error" id="form-error-postcode"></div>';
        html += '</div>';
        html += '<div class="form-group">';
            html += '<span class="control-label">เบอร์ติดต่อ</span>';
            html += '<input name="mobile" type="text" class="form-control" value=""/>';
            html += '<div class="mw-text-error" id="form-error-mobile"></div>';
        html += '</div>';
        html += '<div class="form-group">';
            html += '<button id="mw-btn-add-new-point" class="btn btn-success" style="min-width: 100px">';
                html += '<i class="fa-save"></i> บันทึก';
            html += '</button>';
        html += '</div>';
    html += '</div>';
    html += '</div>';

    return html;
}

function mwPopupFormChoosePoint(points)
{
	const html = mwGenPoints(points);

	myBootBox = bootbox.dialog({
        className: 'mw-bootbox-points-detail',
        title : 'ที่อยู่ผู้รับ Points',
        message : html,
        size: 'large',
        onEscape: true,
        backdrop: true,
    });

    $(myBootBox).on('keyup', '#mw-input-search', function(){

    	const search = $(this).val();

    	if (search == '') {

    		$('.mw-tr-point').show();

    	} else {

    		$('.mw-tr-point').each(function(i, tr){
    			if ($(tr).data('info').indexOf(search) == -1) {
    				$(tr).hide();
    			}
    		});
    	}
    });

    $(myBootBox).on('click', '.mw-btn-choose-point', function(){
    	const point_id = $(this).data('point-id');

    	$.each(points, function(i, point){

    		if (point.id == point_id) {
		    	mwMapPointToConsignee(point);
    		}
    	});

    	bootbox.hideAll();
    })
}

function mwGenPoints(points)
{
	var html = '';
	html += '<div class="row mw-mg-tb-10">';
	html += '<div class="col-sm-8">';
	html += '</div>';
	html += '<div class="col-sm-4">';
	html += '<input id="mw-input-search" class="form-control" name="" placeholder="Search" />';
	html += '</div>';
	html += '</div>';
	html += '<div class="row">';
	html += '<div class="col-sm-12">';
        html += '<table class="table table-bordered">';
            html += '<thead><tr>';
            html += '<th width="20%" class="mw-center">ชื่อผู้รับ</th>';
            html += '<th width="30%" class="mw-center">บริษัทผู้รับ</th>';
            html += '<th width="40%" class="">ที่อยู่ผู้รับ</th>';
            html += '<th width="10%" class="">เลือกที่อยู่</th>';
            html += '</tr></thead>'
            html += '<tbody>';
            $.each(points, function(i, point){
                html += '<tr class="mw-tr-point" data-info="'+point.person+point.name+point.address+point.district+point.province+point.postcode+'">';
                html += '<td class="mw-center">'+point.person+'</td>';
                html += '<td class=""><small>'+point.name+'</small></td>';
                html += '<td class=""><small>'+point.address+' '+point.district+' '+point.province+' '+point.postcode+'</small></td>';
				html += '<td class="mw-center">';
				html += '<div class="mw-btn-choose-point btn btn-default" data-point-id="'+point.id+'">เลือก</div>';
				html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
        html += '</table>';
	html += '</div>';
	html += '</div>';

	return html;
}

function postUpdate()
{
	$.ajax({
		headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
		type: 'POST',
		url: $('#mw-url-update').data('url'),
		data: {
			connoteModel: vueConnote.connoteModel
		},
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


function mwMapPointToConsignee(point)
{
	vueConnote.connoteModel.consignee_name = point.person;
	vueConnote.connoteModel.consignee_company = point.name;
	vueConnote.connoteModel.consignee_address = point.address+' '+point.district+' '+point.province+' '+point.postcode;
	vueConnote.connoteModel.consignee_phone = point.mobile;
	vueConnote.connoteModel.csn.address = point.address;
	vueConnote.connoteModel.csn.district = point.district;
	vueConnote.connoteModel.csn.province = point.province;
	vueConnote.connoteModel.csn.postcode = point.postcode;
}