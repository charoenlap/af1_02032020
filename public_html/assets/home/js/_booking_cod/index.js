var mwVueCod;

$(document).ready(function(){

    $('#mw-btn-save').on('click', function(){
        popupConfirm();
    });

    const id = '#mw-vue-point';

    mwVueCod = new Vue({
        el: id,
        data: {
            provinces: $(id).data('province'),
            codValues: [],
        },
        methods: {
            addRow: function(){
                popupRow();
            },
            removeRow: function(key) {
                this.codValues.splice(key, 1);
            }
        }
    });
});


function popupRow()
{
    const html = genFormRow();

    myBootBox = bootbox.dialog({
        className: 'mw-bootbox-target',
        title : 'เพิ่มที่อยู่เก็บเงินปลายทาง',
        message : html,
        size: 'large',
        onEscape: true,
        backdrop: true,
    });

    $('.mw-bootbox-target').on('click', '#mw-btn-add', function(){
        swalWaiting();
        const data = {};
        data['cod_value'] = $('.mw-bootbox-target').find('input[name=cod_value]').val();
        data['person'] = $('.mw-bootbox-target').find('input[name=person]').val();
        data['address'] = $('.mw-bootbox-target').find('textarea[name=address]').val();
        data['district'] = $('.mw-bootbox-target').find('input[name=district]').val();
        data['province'] = $('.mw-bootbox-target').find('#mw-select-point-province').find('option:selected').val();
        data['postcode'] = $('.mw-bootbox-target').find('input[name=postcode]').val();
        data['phone'] = $('.mw-bootbox-target').find('input[name=phone]').val();
        mwVueCod.codValues.push(data);
        bootbox.hideAll();
        swalStop();
    });
}

function genFormRow()
{
    const provinces = mwVueCod.provinces;

    var form = '';
    form += '<div class="row">';
    form += '<div class="col-sm-12">';
        form += "<div class='form-group' style='width: 50%;'>";
            form += "<label class='control-label'>จำนวนเงิน</label>";
            form += "<div class='input-group'>"
            form += "<input type='text' name='cod_value'";
            form += "class='mw-input-new-phone form-control mw-font-size-1em'>";
            form += "<div class='input-group-addon'>บาท</div>"
            form += "</div>";
        form += "</div>";
        form += "<div class='form-group' style='width: 50%;'>";
            form += "<label class='control-label'>ชื่อผู้รับ</label>";
            form += "<input type='text' name='person' placeholder='ชื่อผู้รับ'";
            form += "class='mw-input-new-phone form-control mw-font-size-1em'>";
        form += "</div>";
        form += "<div class='form-group' >";
            form += "<label class=''>ที่อยู่</label>";
            form += "<textarea name='address' class='mw-input-new-point form-control mw-font-size-1em'></textarea>";
        form += "</div>";
        form += "<div class='form-group' >";
            form += "<label class='control-label'>เขต/อำเภอ</label>";
            form += "<input type='text' name='district' placeholder='เขต/อำเภอ'";
            form += "class='mw-input-new-point form-control mw-font-size-1em'>";
        form += "</div>";
        form += "<div class='form-group' >";
            form += "<label class='control-label'>จังหวัด</label>";
            form += "<select id='mw-select-point-province' ";
            form += "class='mw-input-new-point form-control mw-font-size-1em'>";
            $.each(provinces, function(i, province){
                form += "<option class='mw-font'>"+province+"</option>";
            });
            form += "</select>";
        form += "</div>";
        form += "<div class='form-group' >";
            form += "<label class='control-label'>รหัสไปรษณีย์</label>";
            form += "<input type='text' name='postcode' placeholder='รหัสไปรษณีย์'";
            form += "class='mw-input-new-point form-control mw-font-size-1em'>";
        form += "</div>";
        form += "<div class='form-group' >";
            form += "<label class='control-label'>เบอร์ติดต่อ</label>";
            form += "<input type='text' name='phone' placeholder='เบอร์ติดต่อ'";
            form += "class='mw-input-new-phone form-control mw-font-size-1em'>";
        form += "</div>";

    form += '<div class="row">';
    form += '<div class="col-md-4 col-md-offset-4 col-sm-12" style="margin-top: 30px">';
        form += '<div id="mw-btn-add" class="mw-center btn btn-block btn-success">เพิ่ม</div>';
    form += '</div>';
    form += '</div>';

    return form;
}

function postAddCod(cod_value, person, address, district, province, postcode, phone)
{
    swalWaiting();

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'post',
        url: $('#mw-url-add-point').data('url'),
        data: {
            cod_value: cod_value,
            person: person,
            address: address,
            district: district,
            province: province,
            postcode: postcode,
            phone: phone,
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
            console.log(result);
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
            cod: '1',
            express: $('#mw-select-express').find('option:selected').val(),
            have_connote: $('#mw-checkbox-have-connote').prop('checked') ? 1 : 0,
            size: $('input[name=size]').val(),
            note_that: $('input[name=note_that]').val(),
            codValues: mwVueCod.codValues,
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

            } else if (result.status == 'fail') {

                swalStop();
                bootbox.hideAll();
            }
        },
        error: function(result) {
            swalStop();
            bootbox.hideAll();
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
    form += '</div>';
    form += '</div>';

    form += '<div class="row">';
    form += '<div class="col-sm-12">';
        form += '<h5 class="mw-left">';
        form += 'เก็บเงินปลายทาง : ';
        form += '</h5>';
        form += '<table class="table table-bordered">';
            form += '<thead><tr><th width="20%" class="mw-center">ลำดับที่</th>';
            form += '<th width="30%">จำนวนเงิน</th>';
            form += '<th width="40%">ที่อยู่ปลายทาง</th>';
            form += '<th width="10%" class="mw-center">หน่วย</th></tr>';
            form += '</thead>'
            form += '<tbody>';
            if (mwVueCod.codValues.length > 0) {
                $.each(mwVueCod.codValues, function(i, codValue){
                    form += '<tr>';
                    form += '<td class="mw-center">'+(i+1)+'</td>';
                    form += '<td>'+codValue.cod_value+'</td>';
                    form += '<td>'+codValue.address+' '+codValue.district+' '+codValue.province+' '+codValue.postcode+'</td>';
                    form += '<td class="mw-center">บาท</td>';
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
    form += '<div class="col-md-4 col-md-offset-4 col-sm-12">';
        form += '<div id="mw-btn-confirm" class="mw-center btn btn-block btn-success">ยืนยัน</div>';
    form += '</div>';
    form += '</div>';

    return form;
}