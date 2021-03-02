<section class="page-section" style="padding-top: 0px; margin-top: -40px; font-family: sans-serif !important">
    <div class="col-md-10 col-md-offset-1 col-sm-12 col-xs-12 mw-pd-lr-10 mw-bg-white mw-mg-tp-20" style="padding: 40px">
        <!-- HEADER -->
        <div class="mw-pd-tb-10 mw-bg-white">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="mw-center">เปิดงานใหม่ <small>[ส่งพัสดุ]</small> </h3>
                </div>
            </div>
        </div>
        <!-- BODY -->
        <div class="page-body mw-bg-white" style="margin-top: 10px">
            <!-- GENERAL INFORMATION -->
            <div class="row mw-mg-bm-10">
                <div class="">
                    <h4 class="mw-mg-0">{{ '1. รายละเอียดการรับสินค้า' }}</h4>
                    <hr class="mw-mg-0 mw-bg-color">
                </div>
                <div class="row mw-mg-tp-20">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'ชื่อผู้ติดต่อ' }}</small></span>
                            <input name="person_name" type="text" class="form-control mw-mg-0 mw-font-size-1em"
                            value="{{ $customerModel->person }}" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'เบอร์ติดต่อ' }}</small></span>
                            <input name="person_mobile" type="text" class="form-control mw-mg-0 mw-font-size-1em"
                            value="{{ $customerModel->mobile }}" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'วันที่ให้ไปรับ' }}</small></span>
                            <input name="get_date" type="text" class="mw-input-datepicker form-control mw-mg-0 mw-font-size-1em"
                            value="{{ date('d/m/Y') }}" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'เวลาที่ให้ไปรับ' }}</small></span>
                            <input name="get_time" type="text" class="mw-input-clockpicker form-control mw-mg-0 mw-font-size-1em"
                            value="{{ date('H:i') }}" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'ประเภทรถ' }}</small></span>
                            <select id="mw-select-vehicle" class="form-control mw-font-size-1em">
                                @foreach ($carModels as $carModel)
                                <option value="{{ $carModel->id }}">{{ $carModel->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'ประเภทงาน' }}</small></span>
                            <select id="mw-select-express" class="form-control mw-font-size-1em">
                                @foreach ($expresses as $key => $express)
                                <option value="{{ $express['value'] }}">{{ $express['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'ขนาด' }}</small></span>
                            <input name="size" type="text" class="form-control mw-mg-0 mw-font-size-1em"
                            value="" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'หมายเหตุ' }}</small></span>
                            <input name="note_that" type="text" class="form-control mw-mg-0 mw-font-size-1em"
                            value="" />
                        </div>
                    </div>
                </div>
            </div>
            <!-- LOCATION -->
            <div class="row mw-mg-bm-10">
                <div class="mw-mg-bm-10">
                    <h4 class="mw-mg-0">{{ '2. สถานที่รับสินค้า' }}</h4>
                    <hr class="mw-mg-0 mw-bg-color">
                </div>
                <div class="row mw-mg-tp-20">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'สถานที่รับของ' }}</small></span>
                            <textarea name="address" class="form-control mw-font-size-1em" rows="4">{!! $customerModel->address !!}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'เขต / อำเภอ' }}</small></span>
                            <input name="district" type="text" class="form-control mw-mg-0 mw-font-size-1em"
                            value="{{ $customerModel->district }}" />
                        </div>
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'จังหวัด' }}</small></span>
                            <select id="mw-select-province" class="form-control mw-font-size-1em">
                                @foreach ($provinces as $province)
                                <option class="mw-font">{{ $province }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'รหัสไปรษณีย์' }}</small></span>
                            <input name="postcode" type="text" class="form-control mw-mg-0 mw-font-size-1em"
                            value="{{ $customerModel->postcode }}" />
                        </div>
                    </div>
                </div>
            </div>
            <!-- CONNOTE -->
            <div class="row mw-mg-bm-10" id="mw-vue-point"
            data-model="{{ $pointModels }}"
            data-province="{{ json_encode($provinces) }}"
            data-customer="{{ $customerModel }}">
                <div class="mw-mg-bm-10">
                    <h4 class="mw-mg-0">{{ '3. ใบนำส่ง' }}</h4>
                    <div class="checkbox-custom checkbox-success">
                        <input type="checkbox" id="mw-checkbox-have-connote" v-model="have_connote">
                        <label for="mw-checkbox-have-connote">ลูกค้ามีใบ Connote แล้ว</label>
                    </div>
                    <h5 v-if="have_connote" style="color: #D6494B">** เพื่อความรวดเร็วในการรับสินค้า ลูกค้าควรเตรียมสินค้าและใบนำส่งให้พร้อมก่อนเวลานัด **</h5>
                    <hr class="mw-mg-0 mw-bg-color">
                </div>
                <div v-if="!have_connote">
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-sm-8">
                            <h5 style="font-family: 'sans-serif' !important; margin-bottom: 0;">กรุณาเลือกที่อยู่ปลายทาง
                                <a class="<?php /*mw-btn-add-target */ ?>btn grey" id="btn-add-point"  data-toggle="modal" data-target="#ModalAddPoint"<?php /*@click="addTarget()"*/ ?>>
                                    <i class="fa-plus"></i> เพิ่มที่อยู่ใหม่
                                </a>
                            </h5>
                        </div>
                        <div class="col-sm-4">
                            <?php /*<input type="text" v-model="search_point" class="form-control mw-mg-0"
                            style="height: 30px; min-height: 30px;"
                            placeholder="ค้นหา">*/ ?>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                              ค้นหา
                            </button>
                        </div>
                    </div>
                    <div class="row" style="max-height: 360px; overflow-y: auto;">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-listcustomer" id="table-listcustomer" style="font-size: 0.9em">
                                <thead>
                                    <tr>
                                        <th width="10%" class="mw-middle mw-center">เลือกที่อยู่ปลายทาง</th>
                                        <th width="35%" class="mw-middle">ชื่อ: บริษัท: ที่อยู่</th>
                                        <th width="20%" class="mw-middle">Customer Reference</th>
                                        <th width="20%" class="mw-center">
                                            <div class="checkbox-custom checkbox-danger">
                                                <input type="checkbox" v-model="isCod" id="mw-checkout-cod">
                                                <label for="mw-checkout-cod">ต้องการเก็บเงินปลายทาง</label>
                                            </div>
                                        </th>
                                        <th width="15%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                   <!-- <tr v-for="(pointModel, key) in pointModels"
                                    v-if="(!!(pointModel.person) && !!(pointModel.name))
                                    && (pointModel.person.indexOf(search_point) !== -1
                                    || pointModel.name.indexOf(search_point) !== -1)">
                                        <td class="mw-middle mw-center">
                                            <div class="checkbox-custom checkbox-info">
                                                <input type="checkbox" class="mw-checkbox-point" v-bind:value="pointModel"  v-model="pointChosens">
                                                <label></label>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mw-mg-0">@{{ pointModel.person+' '+(!!(pointModel.mobile) ? pointModel.mobile : '') }}</p>
                                            <p class="mw-mg-0">@{{ pointModel.name }}</p>
                                            <p class="mw-mg-0">@{{ pointModel.address+' '+pointModel.district+' '+pointModel.province+' '+pointModel.postcode }}</p>
                                        </td>
                                        <td class="mw-center mw-middle">
                                            <input type="text" v-model="pointModel.customer_ref" class="form-control mw-mg-0"
                                            @keyup="addValueToPdfUrl(pointModel)">
                                        </td>
                                        <td class="mw-center mw-middle">
                                            <div class="input-group">
                                                <input type="number" v-model="pointModel.value" class="form-control" v-bind:disabled="!isCod" @keyup="addValueToPdfUrl(pointModel)">
                                                <span class="input-group-addon">฿</span>
                                            </div>
                                        </td>
                                        <td class="mw-center mw-middle">
                                            <a class="btn btn-info btn-icon mw-white" target="_blank"
                                            v-bind:href="pointModel.url_pdf+pointModel.url_param">
                                                <i class="fa-print" style="margin: 0 1%;"></i>
                                            </a>
                                            <a class="btn btn-icon"
                                            href="#" @click="removeTarget(pointModel)">
                                                <i class="fa-trash" style="margin-right: 2%; "></i>
                                            </a>
                                        </td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- SAVE -->
            <div class="row" style="margin-top: 60px">
                <div id="mw-btn-save" class="btn btn-success" style="min-width: 240px">
                    <i class="fa-save"></i> บันทึก
                </div>
            </div>

        </div>
    </div>
</section>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width: 800px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ค้นหา</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-8">
                <input type="text" class="form-control" id="txt-search" placeholder="ค้นหา ชื่อ เบอร์โทรศัพท์">
            </div>
            <div class="col-md-4">
                <input type="button" id="btn-search" value="ค้นหา" class="btn btn-primary">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div style="max-height:500px;overflow-y: scroll;">
                    <table class="table" id="table-search-customer">
                        <thead>
                            <th></th>
                            <th>ชื่อลูกค้า</th>
                            <th>ที่อยู่</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="btn-search-submit">ตกลง</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="ModalAddPoint" tabindex="-1" role="dialog" aria-labelledby="AddModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width: 800px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="AddModalLabel">เพิ่มที่อยู่เก็บเงินปลายทาง</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
    </div>
  </div>
</div>
<!-- DATA -->
<input type="hidden" name="customer_id" id="customer_id" value="{{ $customerModel->id }}" />
<div id="mw-url-booking-post" data-url="{{ \URL::route('home.booking.create.post') }}"></div>
<div id="mw-url-add-point" data-url="{{ \URL::route('home.booking.add_point.post', $customerModel->id) }}"></div>
<div id="mw-url-remove-point" data-url="{{ \URL::route('home.booking.remove_point.post', $customerModel->id) }}"></div>

<script type="text/javascript" src="{{ urlThemes().'/mist/js/jquery.min.js' }}"></script>

<script>
$(document).on('click','#btn-add-point',function(e){
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
        form += "<div class='form-group' style='width: 50%;'>";
            form += "<label class='control-label'>ชื่อบริษัท</label>";
            form += "<input type='text' name='company' placeholder='ชื่อบริษัท'";
            form += "class='mw-input-new-company form-control mw-font-size-1em'>";
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
            form += "<input type='text' class='form-control' name='province' id='add-point-select-point-province'>";
            // form += "<select id='add-point-select-point-province' ";
            // form += "class='mw-input-new-point form-control mw-font-size-1em'>";
            // form += "<option value=''>กรุงเทพมหานคร</option>";
            // $.each(provinces, function(i, province){
            //     form += "<option class='mw-font'>"+province+"</option>";
            // });
            // form += "</select>";
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
        form += '<div id="btn-add-point-submit" data-dismiss="modal" class="mw-center btn btn-block btn-success">เพิ่ม</div>';
    form += '</div>';
    form += '</div>';
    $('#ModalAddPoint .modal-body').html(form);
});
function formatErrorMessage(jqXHR, exception) {

    if (jqXHR.status === 0) {
        return ('Not connected.\nPlease verify your network connection.');
    } else if (jqXHR.status == 404) {
        return ('The requested page not found. [404]');
    } else if (jqXHR.status == 500) {
        return ('Internal Server Error [500].');
    } else if (exception === 'parsererror') {
        return ('Requested JSON parse failed.');
    } else if (exception === 'timeout') {
        return ('Time out error.');
    } else if (exception === 'abort') {
        return ('Ajax request aborted.');
    } else {
        return ('Uncaught Error.\n' + jqXHR.responseText);
    }
}
$(document).on('click','#btn-add-point-submit',function(e){
    var cod_value = '';
    var person = '';
    var address = '';
    var district = '';
    var province = '';
    var postcode = '';
    var phone = '';
    var company = '';
    cod_value = $('#ModalAddPoint').find('input[name=cod_value]').val();
    person = $('#ModalAddPoint').find('input[name=person]').val();
    company = $('#ModalAddPoint').find('input[name=company]').val();
    address = $('#ModalAddPoint').find('textarea[name=address]').val();
    district = $('#ModalAddPoint').find('input[name=district]').val();
    province = $('#ModalAddPoint').find('input[name=province]').val();
    postcode = $('#ModalAddPoint').find('input[name=postcode]').val();
    phone = $('#ModalAddPoint').find('input[name=phone]').val();
    $.ajax({
        url: 'http://af1express.com/api/ajax/index.php?type=addPoint',
        type: 'POST',
        dataType: 'json',
        data: {
            cod_value: cod_value,
            person: person,
            address: address,
            district: district,
            province: province,
            postcode: postcode,
            phone: phone,
            company: company,
            customer_id: $('#customer_id').val()
        },
    })
    .done(function(json) {
        console.log(json);
        if (json.status == 'success') {
            var html_append = ''+
            '<tr>'+
                '<td class="mw-middle mw-center">'+
                    '<div class="checkbox-custom checkbox-info">'+
                        '<input type="checkbox" class="mw-checkbox-point" checked v-bind:value="pointModel"  v-model="pointChosens" data-connote="'+json.connote_key+'" data-id="'+json.id+'" data-key="'+json.key+'" data-person="'+person+'" data-address="'+address+'" data-district="'+district+'" data-province="'+province+'" data-postcode="'+postcode+'" data-customer-ref="'+json.customer_ref+'" data-customer-key="'+json.customer_key+'" data-name="'+person+'"> '+
                        '<label></label>'+
                    '</div>'+
                '</td> '+
                '<td>'+json.id+
                    '<p class="mw-mg-0">'+person+'</p> '+
                    // '<p class="mw-mg-0">'+json.name+'</p> '+
                    '<p class="mw-mg-0">'+address+'</p>'+
                '</td> '+
                '<td class="mw-center mw-middle">'+
                    '<input type="text" class="form-control mw-mg-0">'+
                '</td> '+
                '<td class="mw-center mw-middle">'+
                    '<div class="input-group">'+
                        '<input type="number" disabled="disabled" class="form-control"> '+
                        '<span class="input-group-addon">฿</span>'+
                    '</div>'+
                '</td> '+
                '<td class="mw-center mw-middle">'+
                    '<input type="hidden" class="connote" value="'+json.connote_key+'">'+
                    '<a target="_blank" href="#" link="http://af1express.com/gen_connote/'+$('#customer_id').val()+'/point/'+json.id+'/'+json.connote_key+'" class="btn btn-info btn-icon mw-white btn-link-pdf">'+
                        '<i class="fa-print" style="margin: 0px 1%;"></i></a> <a href="#" class="btn btn-icon btn-del"><i class="fa-trash" style="margin-right: 2%;"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>';
            $('#table-listcustomer tbody').append(html_append);
            console.log(json);
            // mwVuePoint.pointModels = result.model;
            // $('.mw-input-new-point').val('');
        }
        console.log("success");
    })
    .fail(function(xhr, err) {
        var responseTitle= $(xhr.responseText).filter('title').get(0);
        alert($(responseTitle).text() + "\n" + formatErrorMessage(xhr, err) ); 
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
    
});
$(document).on('click','#mw-checkout-cod',function(e){
    $('#table-listcustomer tr td input[type="number"]').each(function( index ) {
       $(this).removeAttr("disabled");
    })
});
$(document).on('click','#table-listcustomer tr td .btn-del',function(e){
    $(this).parents('tr').remove();
    e.preventDefault();
});    
    $(function(e){
        $('#btn-search').click(function(e){
            $.ajax({
                url: 'http://af1express.com/api/ajax/index.php',
                type: 'GET',
                dataType: 'json',
                data: {datasearch: $('#txt-search').val(),type:'find'},
            })
            .done(function(json) {
                $('#table-search-customer tbody').empty();
                $.each(json, function(i, data) {
                    console.log(json[i]);
                    $('#table-search-customer tbody').append('<tr><td><input type="checkbox" class="chk-customer-search" data-id="'+json[i].id+'" data-person="'+json[i].person+'" data-address="'+json[i].address+'" data-district="'+json[i].district+'" data-province="'+json[i].province+'" data-postcode="'+json[i].postcode+'" data-customer-ref="'+json[i].customer_ref+'"  data-customer-key="'+json[i].customer_key+'" data-name="'+json[i].name+'"></td><td><p>'+json[i].person+'</p><p>'+json[i].name+'</p></td><td>'+json[i].address+'</td></tr>');
                })
                $('#table-search-customer tbody input:checkbox').removeAttr('checked');
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
        });
        
    });
    $(document).on('click','.btn-link-pdf',function(e){
        var link = $(this).attr('link');
        var cod = $('#mw-checkout-cod').val();
        var cr = $(this).parents('tr').find("input[type='text']").val();
        var price = $(this).parents('tr').find("input[type='number']").val();
        if(cr==''){
            cr = 0;
        }
        if(price==''){
            price = 0;
        }
        var win = window.open(link+'/'+cod+'/'+cr+'/'+price, '_blank');
        if (win) {
            win.focus();
        }
    });
    $(document).on('click','#btn-search-submit',function(e){
        // $('#exampleModal').modal('hide');
        $('.chk-customer-search').each(function () {
            // 
            if($(this).prop("checked") == true){
                var id = $(this).attr('data-id');
                var customer_key = $(this).attr('data-customer-key');
                console.log(id + ' '+ customer_key);
                $.ajax({
                    url: 'http://af1express.com/api/ajax/index.php',
                    type: 'GET',
                    dataType: 'json',   
                    data: {
                        type:'findid',
                        datasearch: id},
                    })      
                    .done(function(json) {
                        // console.log(json);
                        var fields = {};
                        fields[this.person] = json.person;
                        var obj = {fields: fields};

                        var html_append = ''+
                        '<tr>'+
                            '<td class="mw-middle mw-center">'+
                                '<div class="checkbox-custom checkbox-info">'+
                                    '<input type="checkbox" class="mw-checkbox-point" checked v-bind:value="pointModel"  v-model="pointChosens" data-connote="'+json.connote_key+'" data-id="'+json.id+'" data-key="'+json.key+'" data-person="'+json.person+'" data-address="'+json.address+'" data-district="'+json.district+'" data-province="'+json.province+'" data-postcode="'+json.postcode+'" data-customer-ref="'+json.customer_ref+'" data-customer-key="'+json.customer_key+'" data-name="'+json.name+'"> '+
                                    '<label></label>'+
                                '</div>'+
                            '</td> '+
                            '<td>'+id+
                                '<p class="mw-mg-0">'+json.person+'</p> '+
                                '<p class="mw-mg-0">'+json.name+'</p> '+
                                '<p class="mw-mg-0">'+json.address+'</p>'+
                            '</td> '+
                            '<td class="mw-center mw-middle">'+
                                '<input type="text" class="form-control mw-mg-0">'+
                            '</td> '+
                            '<td class="mw-center mw-middle">'+
                                '<div class="input-group">'+
                                    '<input type="number" disabled="disabled" class="form-control"> '+
                                    '<span class="input-group-addon">฿</span>'+
                                '</div>'+
                            '</td> '+
                            '<td class="mw-center mw-middle">'+
                                '<input type="hidden" class="connote" value="'+json.connote_key+'">'+
                                '<a href="#" link="http://af1express.com/gen_connote/'+$('#customer_id').val()+'/point/'+json.id+'/'+json.connote_key+'" class="btn btn-info btn-icon mw-white btn-link-pdf">'+
                                    '<i class="fa-print" style="margin: 0px 1%;"></i></a> <a href="#" class="btn btn-icon btn-del"><i class="fa-trash" style="margin-right: 2%;"></i>'+
                                '</a>'+
                            '</td>'+
                        '</tr>';
                        $('#table-listcustomer tbody').append(html_append);
                    })
                    .fail(function() {
                        console.log("error");
                    })
                    .always(function() {
                        console.log("complete");
                });
                $(this).prop('checked', false);
            }
        })
    });
</script>