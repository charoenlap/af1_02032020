var mwVueTracking;

$(document).ready(function(){

    $('#mw-btn-save').on('click', function(){
        popupConfirm();
    });

    const id = '#mw-vue-tracking';

    mwVueTracking = new Vue({
        el: id,
        data: {
            connoteModel: $(id).data('connote-model'),
            bookingModel: $(id).data('booking-model'),
            tracking_key: $(id).data('tracking-key'),
        },
        mounted: function(){
            $('#mw-input-code').focus();
        },
        methods: {
            checkOrderTracking: function() {
                swalWaiting();
                postTracking();
            },
            clearOrderCode: function() {
                this.tracking_key = '';
                this.bookingModel = null;
            },
        }
    });

    $(document).keypress(function(e) {

        if(e.which == 13) {
            swalWaiting();
            postTracking();
        }
    });
});

function postTracking()
{
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').prop('content')},
        type: 'POST',
        url: $('#mw-url-tracking').data('url'),
        data: {
            tracking_key: mwVueTracking.tracking_key
        },
        success: function(result) {
// console.log(result);
            
            $('#booking_id').val(mwVueTracking.tracking_key);
            $.ajax({
                url: 'http://af1express.com/api/ajax/get_connotes.php?key='+$('#booking_id').val(),
                type: 'GET',
                dataType: 'json',
                // data: {param1: 'value1'},
            })
            .done(function(json) {
                $('#table-connote tbody').html('');
                swalStop();
                // console.log(json);
                var i = 0;
                $.each(json, function(i, item) {
                    // alert(data[i].PageName);
                    console.log(item);
                    var html = '<tr>';
                    html += '<td>'+(i+1)+'</td>';
                    html += '<td>'+item.key_connote;
                    if(item.status=='confirm'){
                        html += '<p class="mw-mg-0" style="color: #46BE8A"><small><i class="fa-check"></i> แมสรับของแล้ว</small></p>';
                    }
                    if(item.status=='cancel'){
                        html += '<p class="mw-mg-0" style="color: #f96868"><small><i class="fa-close"></i> ยกเลิกการส่ง</small></p>';
                    }
                    html += '</td>';
                    html += '<td>';
                    html += '<p>'+item.consignee_name+'@'+item.consignee_company+'</p>';
                    html += '<p>ที่อยู่ : '+item.consignee_address+'</p>';
                    html += '</td>';
                    html += '<td>';
                    
                    if(!item.job){
                        html += '   <div>';
                        html += '       <p>สถานะ : <u>พัสดุเตรียมส่ง</u></p>';
                        html += '       <p>วันและเวลา : '+item.pending_datetime_label+'</p>';
                        html += '   </div>';
                    }
                    // if(item.job){
                        html += '   <div>';
                        if(item.job.status_label){
                            html += '       <p>สถานะ : <u>'+item.job.status_label+'</u></p>';
                        }else{
                            html += '       <p>สถานะ : <u>รอการอนุมัติ</u></p>';
                        }
                        if(item.job.status == 'new'){
                            html += '       <p>';
                            html += '           วันและเวลา : '+item.job.new_datetime_label;
                            html += '       </p>';
                        }
                        if(item.job.status == 'inprogress'){
                            html += '       <p>';
                            html += '           วันและเวลา : '+item.job.inprogress_datetime_label;
                            html += '       </p>';
                        }
                        if(item.job.status == 'complete'){
                            html += '       <p>';
                            html += '           วันและเวลา : '+item.job.complete_datetime_label;
                            html += '      </p>';
                        }
                        html += '   </div>';
                    // }
                    html += '   <div>';
                    if(item.detail_pieces){
                        html += '       <p>น้ำหนักรวมของสินค้า :';
                        if(item.detail_pieces[0].weight){
                            html += '       '+item.detail_pieces[0].weight;
                        }
                        if(item.detail_pieces[1].weight){
                            html += '       '+item.detail_pieces[1].weight;
                        }
                        if(item.detail_pieces[2].weight){
                            html += '       '+item.detail_pieces[2].weight;
                        }
                        if(item.detail_pieces[3].weight){
                            html += '       '+item.detail_pieces[3].weight;
                        }
                        if(item.detail_pieces[4].weight){
                            html += '       '+item.detail_pieces[4].weight;
                        }
                        
                    }
                    html += '       </p>';
                    html += '   </div>';
                    if(item.status == 'complete'){
                    // if(item.job.receiver_name){
                        html += '   <div>';
                        html += '       <p>';
                        html += '           ชื่อผู้เซ็นรับ : '+item.job.receiver_name ;
                        html += '       </p>';
                        html += '   </div>';
                    }
                    html += '</td>';
                    var service_label = '';
                    if(item.service_label){
                        service_label = item.service_label;
                    }
                    html += '<td class="mw-center">'+item.service_label+'</td>';
                    var customer_ref = '-';
                    if(item.customer_ref){
                        customer_ref = item.customer_ref;
                    }
                    html += '<td class="mw-center">'+customer_ref+'</td>';
                    var cod_value = '-';
                    if(item.cod_value){
                        cod_value = item.cod_value+' ฿';
                    }
                    html += '<td class="mw-center">'+cod_value+'</td>';
                    html += '</tr>';
                    $('#table-connote tbody').append(html);
                    i++;
                })
                console.log(json);
                console.log("success");
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
            if (result.status == 'success') {
                mwVueTracking.tracking_key = result.tracking_key;
                mwVueTracking.bookingModel = result.bookingModel;
                mwVueTracking.connoteModel = result.connoteModel;
            } else {
                mwVueTracking.tracking_key = result.tracking_key;
                mwVueTracking.bookingModel = null;
                mwVueTracking.connoteModel = null;
            }
        },
        error: function(result) {
            console.log(result);
        }
    });
}