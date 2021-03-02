var mwVuePoint;

$(document).ready(function(){

    $('#mw-btn-save').on('click', function(){
        popupConfirm();
    });

    const id = '#mw-vue-point';

    mwVuePoint = new Vue({
        el: id,
        data: {
            have_connote: false,
            pointModels: $(id).data('model'),
            provinces: $(id).data('province'),
            pointChosens: [],
            ctmChosen: $(id).data('customer'),
            isCod: false,
            search_point: '',
        },
        methods: {
            addTarget: function(){
                popupTarget();
            },
            removeTarget: function(pointModel){
                postRemovePoint(pointModel);
            },
            addValueToPdfUrl: function(pointModel) {
                pointModel.url_param = '?customer_ref=';
                pointModel.url_param += !!(pointModel.customer_ref) ? pointModel.customer_ref : '';
                pointModel.url_param += '&cod=';
                pointModel.url_param += !!(pointModel.value) ? pointModel.value : '';
            }
        }
    });
});

function postRemovePoint(model)
{
    swal({
        title: 'คุณต้องการลบปลายทาง <br/><small class="mw-black">'+model.person+' '+model.name+'</small>',
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

        if (click_confirm) {
            swalWaiting();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'post',
                url: $('#mw-url-remove-point').data('url'),
                data: {
                    point_id: model.id,
                },
                success: function(result) {

                    if (result.status == 'success') {
                        mwVuePoint.pointModels = result.model;
                        mwVuePoint.pointChosens = [];
                    }
                    swalStop();
                },
                error: function(result) {
                    console.log(result);
                    swalStop();
                }
            });
        }
    });
}

function popupTarget()
{
    const html = genFormTarget();

    const myBootBox = bootbox.dialog({
        className: 'mw-bootbox-target',
        title : 'เพิ่มที่อยู่ปลายทางใหม่',
        message : html,
        size: 'large',
        onEscape: true,
        backdrop: true,
    });

    $(myBootBox).on('click', '#mw-btn-add', function(){
        postAddPoint($(myBootBox));
    });

}

function genFormTarget()
{
    const provinces = mwVuePoint.provinces;

    var form = '';
    form += '<div class="row">';
    form += '<div class="col-sm-12">';
        form += '<div class="form-group" >';
            form += '<label class="control-label">ชื่อผู้รับ</label>';
            form += '<input type="text" name="point_person" placeholder="ชื่อผู้รับ"';
            form += 'class="form-control mw-mg-0 mw-font-size-1em">';
            form += '<div class="mw-text-error" id="form-error-person"></div>'
        form += '</div>';
        form += '<div class="form-group" >';
            form += '<label class="control-label">ชื่อบริษัท</label>';
            form += '<input type="text" name="point_company" placeholder="ชื่อบริษัทผู้รับ"';
            form += 'class="form-control mw-mg-0 mw-font-size-1em">';
            form += '<div class="mw-text-error" id="form-error-company"></div>'
        form += '</div>'
        form += '<div class="form-group" >';
            form += '<label>ที่อยู่</label>';
            form += '<textarea name="point_address" class="mw-input-new-point form-control mw-mg-0 mw-font-size-1em"></textarea>';
            form += '<div class="mw-text-error" id="form-error-address"></div>'
        form += '</div>';
    form += '</div>';
    form += '</div>';
    form += '<div class="row">';
    form += '<div class="col-sm-4">';
        form += '<div class="form-group" >';
            form += '<label class="control-label">เขต/อำเภอ</label>';
            form += '<input type="text" name="point_district" placeholder="เขต/อำเภอ"';
            form += 'class="mw-input-new-point form-control mw-mg-0 mw-font-size-1em">';
            form += '<div class="mw-text-error" id="form-error-district"></div>'
        form += '</div>';
    form += '</div>';
    form += '<div class="col-sm-4">';
        form += '<div class="form-group" >';
            form += '<label class="control-label">จังหวัด</label>';
            form += '<select id="mw-select-point-province" ';
            form += 'class="mw-input-new-point form-control mw-mg-0 mw-font-size-1em">';
            $.each(provinces, function(i, province){
                form += '<option class="mw-font">'+province+'</option>';
            });
            form += '</select>';
            form += '<div class="mw-text-error" id="form-error-person"></div>'
        form += '</div>';
    form += '</div>';
    form += '<div class="col-sm-4">';
        form += '<div class="form-group" >';
            form += '<label class="control-label">รหัสไปรษณีย์</label>';
            form += '<input type="text" name="point_postcode" placeholder="รหัสไปรษณีย์"';
            form += 'class="mw-input-new-point form-control mw-mg-0 mw-font-size-1em">';
            form += '<div class="mw-text-error" id="form-error-postcode"></div>'
        form += '</div>';
    form += '</div>';
    form += '<div class="col-sm-12">';
        form += '<div class="form-group" >';
            form += '<label class="control-label">เบอร์ติดต่อ</label>';
            form += '<input type="text" name="point_phone" placeholder="เบอร์ติดต่อ"';
            form += 'class="form-control mw-mg-0 mw-font-size-1em">';
            form += '<div class="mw-text-error" id="form-error-phone"></div>'
        form += '</div>';
    form += '</div>';
    form += '</div>';
    form += '<hr />';
    form += '<div class="row">';
    form += '<div class="col-md-4 col-md-offset-4 col-sm-12" style="margin-top: 30px">';
        form += '<div id="mw-btn-add" class="mw-center btn btn-block btn-success">เพิ่ม</div>';
    form += '</div>';
    form += '</div>';

    return form;
}

function postAddPoint(bbox)
{
    swalWaiting();


    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'post',
        url: $('#mw-url-add-point').data('url'),
        data: {
            person: $(bbox).find('input[name=point_person]').val(),
            company: $(bbox).find('input[name=point_company]').val(),
            address: $(bbox).find('textarea[name=point_address]').val(),
            district: $(bbox).find('input[name=point_district]').val(),
            province: $(bbox).find('#mw-select-point-province').find('option:selected').val(),
            postcode: $(bbox).find('input[name=point_postcode]').val(),
            phone: $(bbox).find('input[name=point_phone]').val(),
        },
        success: function(result) {

            if (result.status == 'success') {

                mwVuePoint.pointModels = result.model;
                $('.mw-input-new-point').val('');
            }

            swalStop();
            bootbox.hideAll();
        },
        error: function(result) {
            swalStop();
            mwShowErrorMessage(result);
        }
    });
}

function popupConfirm()
{
    const html = genFormConfirm();

    myBootBox = bootbox.dialog({
        className: 'mw-bootbox-booking-confirm',
        title : 'ยืนยันการเปิดงานใหม่',
        message : html,
        size: 'large',
        onEscape: true,
        backdrop: true,
    });

    $('.mw-bootbox-booking-confirm').on('click', '#mw-btn-confirm', function(){
        postConfirm();
    });
}

function postConfirm()
{
    swalWaiting();

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'post',
        url: $('#mw-url-booking-post').data('url'),
        data: {
            customer_id: $('input[name=customer_id]').val(),
            address: $('textarea[name=address]').val(),
            district: $('input[name=district]').val(),
            province: $('#mw-select-province').find('option:selected').val(),
            postcode: $('input[name=postcode]').val(),
            person_name: $('input[name=person_name]').val(),
            person_mobile: $('input[name=person_mobile]').val(),
            get_date: $('input[name=get_date]').val(),
            get_time: $('input[name=get_time]').val(),
            car_id: $('#mw-select-vehicle').find('option:selected').val(),
            cod: (mwVuePoint.isCod) ? '1' : '0',
            express: $('#mw-select-express').find('option:selected').val(),
            have_connote: $('#mw-checkbox-have-connote').prop('checked') ? 1 : 0,
            size: $('input[name=size]').val(),
            note_that: $('input[name=note_that]').val(),
            pointChosens: (mwVuePoint.pointChosens),
            isCod: (mwVuePoint.isCod) ? 1 : 0,
        },
        success: function(result) {

            if (result.status == 'success') {

                swal({
                    title: 'เปิดงานสำเร็จ',
                    text: '<p class="mw-font">Create Complete</p>',
                    type: 'success',
                    html: true,
                    customClass: 'mw-bg-white',
                }, function(isConfirm) {

                    if (isConfirm) {
                        window.location.href = result.url;
                    }
                });

            } else if (result.status == 'fail') swalStop();
        },
       error: function(result) {
            console.log(result);
            mwShowErrorMessage(result);
        }
    });
}

function genFormConfirm()
{
    var form = '';
    form += '<div class="row">';
    form += '<div class="col-sm-12">';
        form += '<h5 class="mw-left">';
        form += 'ชื่อผู้ติดต่อ : '+$('input[name=person_name]').val();
        form += '</h5>';
        form += '<h5 class="mw-left">';
        form += 'เบอร์ติดต่อ : '+$('input[name=person_mobile]').val();
        form += '</h5>';
        form += '<h5 class="mw-left">';
        form += 'วันที่รับสินค้า : '+$('input[name=get_date]').val();
        form += '</h5>';
        form += '<h5 class="mw-left">';
        form += 'เวลาที่ให้ไปรับ : '+$('input[name=get_time]').val()+' น.';
        form += '</h5>';
        form += '<h5 class="mw-left">';
        form += 'สถานที่รับของ : '+$('textarea[name=address]').val()+' ';
        form += $('input[name=district]').val()+' ';
        form += $('#mw-select-province').find('option:checked').val()+' ';
        form += $('input[name=postcode]').val();
        form += '</h5>';
        form += '<h5 class="mw-left">';
        form += 'ประเภทรถ : '+$('#mw-select-vehicle').find('option:selected').text();
        form += '</h5>';
        form += '<h5 class="mw-left">';
        form += 'ประเภทงาน : '+$('#mw-select-express').find('option:selected').text();
        form += '</h5>';
        form += '<h5 class="mw-left">';
        form += 'ขนาด : '+$('input[name=size]').val();
        form += '</h5>';
        form += '<h5 class="mw-left">';
        form += 'หมายเหตุ : '+$('input[name=note_that]').val();
        form += '</h5>';

        if ($('#mw-checkbox-have-connote').prop('checked')) {
            form += '<h5 class="mw-center" style="color: #D6494B">** เพื่อความรวดเร็วในการรับสินค้า ลูกค้าควรเตรียมสินค้าและใบนำส่งให้พร้อมก่อนเวลานัด **</h5>';
        } else {
            form += '<div class="row">';
            form += '<div class="col-sm-12">';
                form += '<h5 class="mw-left">';
                form += 'เก็บเงินปลายทาง : '+((mwVuePoint.isCod) ? 'มี COD' : 'ไม่มี');
                form += '</h5>';
                form += '<table class="table table-bordered">';
                    form += '<thead><tr><th width="10%" class="mw-center">ลำดับที่</th>';
                    form += '<th width="40%">ที่อยู่ปลายทาง</th>';
                    form += '<th width="20%">Customer Ref</th>';
                    if (mwVuePoint.isCod) {
                        form += '<th width="20%" class="mw-center">จำนวนเงิน</th>';
                        form += '<th width="10%" class="mw-center">หน่วย</th></tr>';
                    }
                    form += '</thead>'
                    form += '<tbody>';
                    if (mwVuePoint.pointChosens.length > 0) {
                        $.each(mwVuePoint.pointChosens, function(i, point){
                            form += '<tr>';
                            form += '<td class="mw-center">'+(i+1)+'</td>';
                            form += '<td>'+point.person+'@'+point.name;
                            form += '<p class="mw-mg-0"><small>'+point.address+' '+point.district+' '+point.province+' '+point.postcode+'</small></p></td>';
                            form += '<td>'+point.customer_ref+'</td>';
                            if (mwVuePoint.isCod) {
                                form += '<td class="mw-center">'+((point.value) ? point.value : 0)+'</td>';
                                form += '<td class="mw-center">บาท</td>';
                            }
                            form += '</tr>';
                        });
                    } else {
                        form += '<tr>';
                        form += '<td colspan="3" class="mw-center">ไม่มีจำนวนเงิน</td>';
                        form += '</tr>';
                    }
                    form += '</tbody>';
                form += '</table>';

                if ($('#mw-checkbox-have-connote').prop('checked')) {
                    form += '<h5 class="mw-center" style="color: #D6494B">** เพื่อความรวดเร็วในการรับสินค้า ลูกค้าควรเตรียมสินค้าและใบนำส่งให้พร้อมก่อนเวลานัด **</h5>';
                }
                form += '</div>';
                form += '</div>';
        }
    form += '</div>';

    form += '<div class="row">';
    form += '<div class="col-md-4 col-md-offset-4 col-sm-12" style="margin-top: 30px">';
        form += '<div id="mw-btn-confirm" class="mw-center btn btn-block btn-success">ยืนยัน</div>';
    form += '</div>';
    form += '</div>';

    return form;
}