<!-- Header -->
<div class="page-header" style="margin-bottom: 3%">
    <!-- Title -->
    <div class="col-sm-10 col-xs-12">
        <h1 class="mw-page-title page-title">
            {{ config('labels.menu.booking.'.helperLang()) }}
            <small>BOOKING</small>
        </h1>
    </div>
    <div class="col-sm-2 col-xs-12">
        <a class="btn btn-success btn-block pull-right" href="{{ \URL::route('admin.booking.update.get', 0) }}">
            <i class="fa-plus"></i> สร้างใหม่
        </a>
    </div>
</div>
<!-- Panel -->
<div class="mw-page-content page-content">
    <!-- SEARCH PANEL -->
    {!! view('admin.pages.booking.search', compact('searchs', 'bookingStatuses')) !!}
    <!-- TABLE -->
    <div class="panel">
        <div class="panel-body container-fluid">
            <div id="page-area" class="text-center">
                <ul class="pageing">
                    <li><a href="http://af1express.com/admin/booking?&start_date={{ $start_date }}&end_date={{ $end_date }}&status={{ $status }}&page={{ $prev }}">ก่อนหน้า</a></li>
                    <li><input type="text" value="{{ $page }}" class="form-control" id="text-page"></li>
                    <li><a href="http://af1express.com/admin/booking?&start_date={{ $start_date }}&end_date={{ $end_date }}&status={{ $status }}&page={{ $next }}">ต่อไป</a></li>
                </ul>
            </div>
            <div class="row" style="margin-bottom:20px;">
                <form action="#" id="form-submit" >
                    <div class="col-md-4">
                        <label for="">สั่งงานให้แมสตาม ที่ เลือก</label>
                    </div>
                    <div class="col-md-4">
                        <select name="emp_id" class="form-control" id="emp_id">
                            <option value="-">-</option>
                            <?php foreach($employee as $val){?>
                            <option value="<?php echo $val['id'];?>"><?php echo $val['nickname'].' '.$val['firstname'].' '.$val['lastname'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="submit" value="บันทึก" class="btn btn-primary" id="btn-form-submit">
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table id="mw-table-order" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="30px;"><input type="checkbox" class="check_all"></th>
                            <th width="10%" class="mw-center mw-middle"><small>สถานะ</small></th>
                            <th width="12%" class="mw-middle mw-center"><small>Booking Key</small></th>
                            <th width="20%" class="mw-middle"><small>ชื่อบริษัท</small></th>
                            <th width="15%" class="mw-center mw-middle"><small>เวลารับสินค้า</small></th>
                            <th width="15%" class="mw-center mw-middle"><small>เปิดงานโดย</small></th>
                            <th width="10%" class="mw-center mw-middle"><small>ผู้สั่งงานให้แมส</small></th>
                            <th width="10%" class="mw-center mw-middle"><small>แมส</small></th>
                            <th width="10%" class="mw-center mw-middle"><small>จัดการ</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($bookingModels->count() <= 0)
                        <tr class="mw-tr">
                            <td colspan="7" class="mw-center">ไม่พบข้อมูล</td>
                        </tr>
                        @else
                        @foreach ($bookingModels as $bookingModel)
                        <tr class="mw-tr" id="{{ 'mw-model-'.$bookingModel->id }}" data-model="{{ $bookingModel }}">
                            <td>
                                <input type="checkbox" class="checkbox_sub" data-id-booking="{{ $bookingModel->id }}">
                            </td>
                            <td class="mw-center mw-middle">
                                <label class="label" style="{{ 'background-color:'.$bookingModel->status_color }}">
                                {{ $bookingModel->status_label }}
                                </label>
                            </td>
                            <td class="mw-center mw-middle">
                                <small>{{ $bookingModel->key }}</small>
                                <div>{!! ($bookingModel->cod) ? '<span class="badge"><small>COD</small></span>' : '' !!}</div>
                            </td>
                            <td class="mw-middle">
                                <small>{{ $bookingModel->customer_name }}</small>
                            </td>
                            <td class="mw-center mw-middle">
                                <small>{{ $bookingModel->get_datetime_label }}</small>
                            </td>
                            <td class="mw-center mw-middle"><small>{{ $bookingModel->created_by }}</small></td>
                            <td class="mw-center mw-middle">{{ $bookingModel->cs_name }}</td>
                            <td class="mw-center mw-middle">
                                @if(!empty($bookingModel->msg_name))
                                    <div class="mw-btn-choose-msg" style="cursor: pointer; text-decoration: underline;"
                                        data-id='{{ $bookingModel->id }}'>
                                        {{ $bookingModel->msg_name }}
                                    </div>
                                @else
                                    <div class="mw-btn-choose-msg btn btn-info btn-outline"
                                        data-id='{{ $bookingModel->id }}'>
                                        <i class="fa-truck"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="mw-center mw-middle">
                                <div class="mw-btn-view-detail btn btn-primary btn-icon"
                                    data-id="{{ $bookingModel->id }}">
                                    <i class="fa-eye"></i>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="mw-url-choose-msg" data-url="{{ \URL::route('admin.booking.update_msg.post') }}" ></div>
<div id="mw-url-remove-booking" data-url="{{ \URL::route('admin.booking.remove.post') }}" ></div>
<div id="mw-url-booking-index" data-url="{{ \URL::route('admin.booking.index.get') }}"></div>
<div id="mw-booking-status" data-value="{{ json_encode($bookingStatuses) }}"></div>
<div id="mw-data-msg" data-model="{{ $msgModels }}"></div>
<script>
    $(document).on('click','#btn-form-submit',function(e){
        e.preventDefault();
        var msg_id = $('#emp_id').val();
        $('.checkbox_sub').each(function(){
           if(this.checked){
                var booking = $(this).attr('data-id-booking');
                console.log(booking, msg_id);
                postUpdateMsg_each(booking, msg_id);
            }
        })
        // window.location.reload();
    });
    function postUpdateMsg_each(booking, msg_id){
        swalWaiting();
        // alert($('#mw-url-choose-msg').data('url'));
        console.log($('#mw-url-choose-msg').data('url'));
        console.log($('meta[name="csrf-token"]').attr('content'));
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: $('#mw-url-choose-msg').data('url'),
            data: {
                booking_id: booking,
                msg_id: msg_id,
            },
            success: function(result) {
                console.log(result);
                window.location.reload();
            },
            error: function(result) {

                swalStop();
                mwShowErrorMessage(result);
            }
        })
    }
</script>
<script>
     $( document ).on( "click",".mw-btn-view-detail", function() {
        var html = '';
        var ele = $(this);
        $.ajax({
            url: 'http://af1express.com/api/ajax/get_connotes.php',
            type: 'GET',
            dataType: 'json',
            data: {booking_id: ele.attr('data-id')},
        })
        .done(function(json) {
            // console.log("success");
            // console.log(json);
            var i = 0;
            $.each(json, function(i, item) {
                html += '<tr>';
                html += '<td class="mw-center"><small>'+(i+1)+'</small></td>';
                html += '<td class="mw-center"><small>'+((json[i].key) ? json[i].key : '');
                html += '<p class="mw-mg-0 green-600"><small>'+((json[i].status == 'confirm') ? '<i class="fa-check"></i> แมสรับของแล้ว' : '')+'</small></p></small></td>';
                html += '<td class=""><small>'+((json[i].consignee_name) ? json[i].consignee_name : '');
                html += ' @ '+((json[i].consignee_company) ? json[i].consignee_company : '')+'</small></td>';
                html += '<td class=""><small>'+json[i].consignee_address+'</small></td>';
                
                html += '<td class="mw-center"><small>'+((json[i].customer_ref) ? json[i].customer_ref+'' : '-')+'</small></td>';
                html += '<td class="mw-center"><small>'+json[i].service_label+((json[i].cod_value) ? '<br/>('+json[i].cod_value+' บาท)' : '')+'</small></td>';
                html += '<td class="mw-center"><a class="btn btn-success btn-icon mw-white" target="_blank" ';
                html += 'href="'+json[i].url_pdf+'">';
                html += '<i class="fa-print" style="margin: 0 1%;"></i>';
                html += '</a></td>';
                html += '</tr>';
            })
            $('#table-list-connotes tbody').append(html);
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
    }); 
</script>

<style>
    .pageing {
        list-style: none;
    }
    .pageing li {
        display:inline-block;
    }
    #text-page {
        width:60px;
        text-align: center;
    }
</style>
<script>
    $(document).on('click','.check_all',function(e){
        if(this.checked){
            $('.checkbox_sub').each(function(){
                this.checked = true;
            });
        }else{
             $('.checkbox_sub').each(function(){
                this.checked = false;
            });
        }
    }); 
     $(document).on('click','.checkbox_sub',function(e){
        if(this.checked){
            $('.check_all').each(function(){
                this.checked = true;
            });
        }else{
             $('.check_all').each(function(){
                this.checked = false;
            });
        }
    }); 
    $(function(e){
        // alert(1);
        $('#text-page').blur(function(e){
            window.location = 'http://af1express.com/admin/booking?page='+$(this).val();
        });
    });
</script>