<section class="page-section" style="padding-top: 0px; margin-top: -40px; font-family: sans-serif !important">
    <div class="col-md-10 col-md-offset-1 col-sm-12 col-xs-12 mw-pd-lr-10 mw-bg-white mw-mg-tp-20" style="padding: 40px">
        <!-- HEADER -->
        <div class="mw-pd-tb-10 mw-bg-white">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="mw-center">
                        ติดตามสถานะการส่งพัสดุ <small>[AIRFORCE ONE TRACKING]</small>
                        <img width="150" src="{{ urlHomeImage().'/tracking.png' }}" style="margin-left: 20px">
                    </h3>
                </div>
            </div>
        </div>
        <!-- BODY -->
        <div id="mw-vue-tracking" class="page-body mw-bg-white" style="margin-top: 10px"
        data-tracking-key="{{ $tracking_key }}"
        data-connote-model="{{ $connoteModel }}"
        data-booking-model="{{ $bookingModel }}">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 col-xs-12 mw-pd-0">
                    <div class="input-group" style="padding: 0 15px;">
                        <input id="mw-input-code mw-font" v-model="tracking_key" type="text" name=""
                        class="form-control mw-center" autofocus
                        style="text-transform:uppercase; font-size: 20px" placeholder="เลขใบนำส่งพัสดุ XXXXXX">
                        <div v-on:click="checkOrderTracking()" class="input-group-addon mw-bg-color mw-white mw-font btn">
                            <i class="fa-search"></i> ตรวจสอบ 
                        </div>
                        <input type="hidden" id="booking_id" value="">
                    </div>
                </div>
            </div>
            <!-- BOOKING -->
            <div v-if="!bookingModel == false" class="mw-pd-tb-20">
                <!-- WIZARD -->
                {!! view('customer.pages.tracking.booking_wizard') !!}
                <div class="row">
                    <div class="col-sm-12 mw-black" style="margin-bottom: 30px">
                        <h4 class="mw-mg-0">รายละเอียด</h4>
                        <table class="table table-bordered" style="font-size: 0.85em;">
                            <tbody>
                                <tr>
                                    <td width="30%">รหัสการส่งพัสดุ </td>
                                    <td width="70%"><b>@{{ bookingModel.key }}</b></td>
                                </tr>
                                <tr>
                                    <td>สถานะ </td>
                                    <td>@{{ bookingModel.status_label }}</td>
                                </tr>
                                <tr>
                                    <td>ประเภทงาน </td>
                                    <td>@{{ bookingModel.express_label }}</td>
                                </tr>
                                <tr>
                                    <td>ชื่อผู้ติดต่อ </td>
                                    <td>@{{ bookingModel.person_name }}</td>
                                </tr>
                                <tr>
                                    <td>ชื่อบริษัท </td>
                                    <td>@{{ bookingModel.customer_name }}</td>
                                </tr>
                                <tr>
                                    <td>เบอร์โทรศัพท์ </td>
                                    <td>@{{ bookingModel.person_mobile }}</td>
                                </tr>
                                <tr>
                                    <td>สถานที่รับของ </td>
                                    <td>@{{ bookingModel.address+' '+bookingModel.district+' '+bookingModel.province+' '+bookingModel.postcode }}</td>
                                </tr>
                                <tr>
                                    <td>วันเวลาที่นัดหมายไว้ </td>
                                    <td>@{{ bookingModel.get_datetime_label }}</td>
                                </tr>
                                <tr>
                                    <td>ขนาด </td>
                                    <td>@{{ bookingModel.size }}</td>
                                </tr>
                                <tr>
                                    <td>หมายเหตุ </td>
                                    <td>@{{ bookingModel.note_that }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                aaa
                <div v-if="bookingModel.connotes.length > 0" class="col-sm-12" style="margin-bottom: 30px">
                    <h4 class="mw-mg-0">ใบนำส่ง</h4>
                    <table class="table table-bordered" style="font-size: 0.75em;">
                        <thead>
                            <tr>
                                <th width="5%" class="mw-middle mw-center">
                                    <p class="mw-mg-0"><small>No</small></p>
                                </th>
                                <th width="15%" class="mw-middle">
                                    หมายเลขใบนำส่ง <p class="mw-mg-0"><small>Connote No.</small></p>
                                </th>
                                <th width="20%" class="mw-middle">
                                    รายละเอียดผู้รับปลายทาง <p class="mw-mg-0"><small>Consignee Detail</small></p>
                                </th>
                                <th width="24%" class="mw-middle">
                                    รายละเอียดของ Connote <p class="mw-mg-0"><small>Connote Detail</small></p>
                                </th>
                                <th width="12%" class="mw-middle mw-center">
                                    ประเภท <p class="mw-mg-0"><small>Service type</small></p>
                                </th>
                                <th width="12%" class="mw-middle mw-center">
                                    Customer Ref.
                                </th>
                                <th width="12%" class="mw-middle mw-center">
                                    เงินเก็บปลายทาง <p class="mw-mg-0"><small>COD</small></p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(connote, index) in bookingModel.connotes">
                                <td class="mw-center">@{{ (index+1) }}</td>
                                <td>@{{ connote.key }}
                                    <p v-if="(connote.status == 'confirm')" class="mw-mg-0" style="color: #46BE8A">
                                        <small><i class="fa-check"></i> แมสรับของแล้ว</small>
                                    </p>
                                    <p v-if="(connote.status == 'cancel')" class="mw-mg-0" style="color: #f96868">
                                        <small><i class="fa-close"></i> ยกเลิกการส่ง</small>
                                    </p>
                                </td>
                                <td>
                                    <p>@{{ ((connote.consignee_name) ? connote.consignee_name : '')+' @ '+((connote.consignee_company) ? connote.consignee_company : '') }}</p>
                                    <p>ที่อยู่ : @{{ connote.consignee_address }}</p>
                                </td>
                                <td>
                                    <div v-if="!connote.job">
                                        <p>สถานะ : <u>พัสดุเตรียมส่ง</u></p>
                                        <p v-if="!connote.job">วันและเวลา : @{{ connote.pending_datetime_label }}</p>
                                    </div>
                                    <div v-if="!!connote.job">
                                        <p>สถานะ : <u>@{{ connote.job.status_label }}</u></p>
                                        <p v-if="connote.job.status == 'new'">
                                            วันและเวลา : @{{ connote.job.new_datetime_label }}
                                        </p>
                                        <p v-if="connote.job.status == 'inprogress'">
                                            วันและเวลา : @{{ connote.job.inprogress_datetime_label }}
                                        </p>
                                        <p v-if="connote.job.status == 'complete'">
                                            วันและเวลา : @{{ connote.job.complete_datetime_label }}
                                        </p>
                                    </div>
                                    <div>
                                        <p>น้ำหนักรวมของสินค้า :
                                        @{{ 1*connote.detail_pieces[0].weight
                                        +1*connote.detail_pieces[1].weight
                                        +1*connote.detail_pieces[2].weight
                                        +1*connote.detail_pieces[3].weight
                                        +1*connote.detail_pieces[4].weight
                                        }}</p>
                                    </div>
                                    <div v-if="!!connote.job">
                                        <p v-if="connote.job.status == 'complete'">
                                            ชื่อผู้เซ็นรับ : @{{ connote.job.receiver_name }}
                                        </p>
                                    </div>
                                </td>
                                <td class="mw-center">@{{ connote.service_label }}</td>
                                <td class="mw-center">@{{ (connote.customer_ref) ? connote.customer_ref : '-' }}</td>
                                <td class="mw-center">@{{ (!!connote.cod_value) ? connote.cod_value+' ฿' : '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- CONNOTE -->
            <div v-if="!connoteModel == false">
                <!-- WIZARD -->
                <div v-if="!connoteModel.send || !connoteModel.job">
                {!! view('customer.pages.tracking.connote_wizard') !!}
                </div>
                <div v-if="!!connoteModel.send && !!connoteModel.job">
                {!! view('customer.pages.tracking.connote_return_wizard') !!}
                </div>
                <div class="row" style="margin-top: 5%">
                    <div class="col-sm-12 mw-black">
                        <h4 class="mw-mg-0">1. ผู้ส่ง Shipper</h4>
                        <table class="table table-bordered" style="font-size: 0.85em">
                            <tbody>
                                <tr>
                                    <td>เปิดงานเมื่อเวลา <small>(Booking Time)</small> </td>
                                    <td>@{{ connoteModel.booking.created_label }}</td>
                                </tr>
                                <tr>
                                    <td width="40%">เลขใบนำส่ง <small>(Connote No.)</small> </td>
                                    <td width="60%"><b>@{{ connoteModel.key }}</b></td>
                                </tr>
                                <tr>
                                    <td>ชื่อผู้ส่ง <small>(Shipper Name)</small> </td>
                                    <td>@{{ connoteModel.shipper_name }}</td>
                                </tr>
                                <tr>
                                    <td>บริษัทผู้ส่ง <small>(Company)</small> </td>
                                    <td>@{{ connoteModel.shipper_company }}</td>
                                </tr>
                                <tr>
                                    <td>ที่อยู่ผู้ส่ง <small>(Shipper Address)</small> </td>
                                    <td>@{{ connoteModel.shipper_address }}</td>
                                </tr>
                                <tr>
                                    <td>เบอร์ติดต่อ <small>(Phone No.)</small> </td>
                                    <td>@{{ connoteModel.shipper_phone }}</td>
                                </tr>
                                <tr>
                                    <td>เลข Booking <small>(Booking No.)</small> </td>
                                    <td>@{{ connoteModel.booking.key }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row" style="margin-top: 2%">
                    <div class="col-sm-12 mw-black">
                        <h4 class="mw-mg-0">2. ประเภทงานและที่อยู่ปลายทาง Service and Destination</h4>
                        <table class="table table-bordered" style="font-size: 0.85em">
                            <tbody>
                                <tr>
                                    <td>รับพัสดุจากผู้ส่งเมื่อเวลา <small>(Get from Sender)</small> </td>
                                    <td>@{{ connoteModel.pending_datetime_label }}</td>
                                </tr>
                                <tr>
                                    <td>ประเภทงาน <small>(Express Service)</small> </td>
                                    <td>@{{ connoteModel.express_label }}</td>
                                </tr>
                                <tr>
                                    <td>ประเภทบริการ <small>(Service Type)</small> </td>
                                    <td><b>@{{ connoteModel.service_label }}</b></td>
                                </tr>
                                <tr>
                                    <td>เก็บเงินปลายทาง <small>(COD)</small> </td>
                                    <td><b>@{{ !!(connoteModel.cod_value) ? connoteModel.cod_value+' บาท' : '' }}</b></td>
                                </tr>
                                <tr>
                                    <td width="40%">ชื่อผู้รับปลายทาง <small>(Consignee Name)</small> </td>
                                    <td width="60%">@{{ connoteModel.consignee_name }}</td>
                                </tr>
                                <tr>
                                    <td>บริษัทผู้รับปลายทาง <small>(Consignee Company)</small> </td>
                                    <td>@{{ connoteModel.consignee_company }}</td>
                                </tr>
                                <tr>
                                    <td>ที่อยู่ปลายทาง <small>(Consignee Address)</small> </td>
                                    <td>@{{ connoteModel.consignee_address }}</td>
                                </tr>
                                <tr>
                                    <td>เบอร์ติดต่อปลายทาง <small>(Phone No.)</small> </td>
                                    <td>@{{ connoteModel.consignee_phone }}</td>
                                </tr>
                                <tr>
                                    <td>น้ำหนักรวมของสินค้า กก. <small>(Weight Total Kg.)</small> </td>
                                    <td>
                                         @{{ 1*connoteModel.detail_pieces[0].weight
                                        +1*connoteModel.detail_pieces[1].weight
                                        +1*connoteModel.detail_pieces[2].weight
                                        +1*connoteModel.detail_pieces[3].weight
                                        +1*connoteModel.detail_pieces[4].weight
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div v-if="!(connoteModel.send) && !!(connoteModel.job)" class="row" style="margin-top: 2%">
                    <div class="col-sm-12 mw-black">
                        <h4 class="mw-mg-0">3. ส่งของ</h4>
                        <table class="table table-bordered" style="font-size: 0.85em">
                            <tbody>
                                <tr>
                                    <td>สถานะ <small>(Status)</small> </td>
                                    <td><b>@{{ connoteModel.job.status_label }}</b></td>
                                </tr>
                                <tr>
                                    <td>เริ่มงานส่งเมื่อเวลา <small>(Start Time)</small> </td>
                                    <td>@{{ connoteModel.job.new_datetime_label }}</td>
                                </tr>
                                <tr>
                                    <td>เซ็นรับของเมื่อเวลา <small>(Received Time)</small> </td>
                                    <td>@{{ connoteModel.job.received_label }}</td>
                                </tr>
                                <tr>
                                    <td width="40%">ชื่อผู้เซ็นรับของ <small>(Receiver Name)</small> </td>
                                    <td width="60%">@{{ connoteModel.job.receiver_name }}</td>
                                </tr>
                                <tr v-if="!connoteModel.send">
                                    <td>รูปหลักฐานการรับของ <small>(Signed Connote Photo)</small> </td>
                                    <td>
                                        <a v-bind:href="connoteModel.job.photo_url" target="_blank">
                                            <img v-bind:src="connoteModel.job.photo_url" width="50%">
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div v-if="!!connoteModel.send && !!connoteModel.job" class="row" style="margin-top: 2%">
                    <div class="col-sm-12 mw-black">
                        <h4 class="mw-mg-0">3. ส่งของ (ขาไป)</h4>
                        <table class="table table-bordered" style="font-size: 0.85em">
                            <tbody>
                                <tr>
                                    <td>สถานะ <small>(Status)</small> </td>
                                    <td><b>@{{ connoteModel.send.status_label }}</b></td>
                                </tr>
                                <tr>
                                    <td>เริ่มงานส่งเมื่อเวลา <small>(Start Time)</small> </td>
                                    <td>@{{ !!(connoteModel.job) ? connoteModel.job.new_datetime_label : '' }}</td>
                                </tr>
                                <tr>
                                    <td>เซ็นรับของเมื่อเวลา <small>(Received Time)</small> </td>
                                    <td>@{{ !!(connoteModel.job) ? connoteModel.job.complete_datetime_label : '' }}</td>
                                </tr>
                                <tr>
                                    <td width="40%">ชื่อเซ็นรับของ <small>(Receiver Name)</small> </td>
                                    <td width="60%">@{{ connoteModel.send.receiver_name }}</td>
                                </tr>
                                <tr>
                                    <td>รูปหลักฐานการรับของ <small>(Signed Connote Photo)</small> </td>
                                    <td>
                                        <a v-bind:href="connoteModel.send.photo_url" target="_blank">
                                            <img v-bind:src="connoteModel.send.photo_url" width="50%">
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div v-if="!!connoteModel.send && !!connoteModel.job" class="row" style="margin-top: 2%">
                    <div class="col-sm-12 mw-black">
                        <h4 class="mw-mg-0">4. ส่งของ (ขากลับ)</h4>
                        <table class="table table-bordered" style="font-size: 0.85em">
                            <tbody>
                                <tr>
                                    <td>สถานะ <small>(Status)</small> </td>
                                    <td><b>@{{ connoteModel.job.status_label }}</b></td>
                                </tr>
                                <tr>
                                    <td>เริ่มงานส่งเมื่อเวลา <small>(Start Time)</small> </td>
                                    <td>@{{ connoteModel.job.new_datetime_return }}</td>
                                </tr>
                                <tr>
                                    <td>เซ็นรับของเมื่อเวลา <small>(Received Time)</small> </td>
                                    <td>@{{ connoteModel.job.complete_datetime_return }}</td>
                                </tr>
                                <tr>
                                    <td width="40%">ชื่อผู้เซ็นรับของ <small>(Receiver Name)</small> </td>
                                    <td width="60%">@{{ connoteModel.job.receiver_name }}</td>
                                </tr>
                                <tr v-if="!connoteModel.send">
                                    <td>รูปหลักฐานการรับของ <small>(Signed Connote Photo)</small> </td>
                                    <td>
                                        <a v-bind:href="connoteModel.job.photo_url" target="_blank">
                                            <img v-bind:src="connoteModel.job.photo_url" width="50%">
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div v-if="!bookingModel && !connoteModel" class="mw-pd-tb-20">
                <div class="row">
                    <div class="col-sm-12 mw-black mw-center" style="margin-bottom: 30px; margin-left: -50px;">
                        <h4 class="mw-mg-0">ไม่พบข้อมูล</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- DATA -->
<div id="mw-url-tracking" data-url="{{ \URL::route('home.tracking.index.post') }}"></div>