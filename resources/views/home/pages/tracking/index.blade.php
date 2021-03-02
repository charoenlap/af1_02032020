<section class="page-section" style="padding: 20px 0">
    <div id="mw-vue-tracking" class="container"
    data-tracking-key="{{ $tracking_key }}"
    data-connote-model="{{ $connoteModel }}">
        <div class="section-title" style="margin-top: 5%">
            <h3 class="mw-center">
                ติดตามสถานะการส่งพัสดุ <small>[AIRFORCE ONE TRACKING]</small>
            </h3>
        </div>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-xs-12 mw-pd-0">
                <div class="input-group">
                    <input id="mw-input-code mw-font" v-model="tracking_key" type="text" name=""
                    class="form-control mw-center" autofocus
                    style="text-transform:uppercase; font-size: 20px" placeholder="เลขใบนำส่งพัสดุ XXXXXX">
                    <div v-on:click="checkOrderTracking()" class="input-group-addon mw-bg-color mw-white mw-font btn">
                        <i class="fa-search"></i> ตรวจสอบ
                    </div>
                </div>
            </div>
        </div>
        <div v-if="!connoteModel == false" class="row" style="margin-top: 5%">
            {!! view('home.pages.tracking.connote_wizard') !!}
            <div class="col-sm-offset-1 col-sm-10 mw-black">
	            <h4 class="mw-mg-0">รายละเอียด</h4>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="40%">เลขใบนำส่ง : </td>
                            <td width="60%"><b>@{{ connoteModel.key }}</b></td>
                        </tr>
                        <tr>
                            <td>สถานะ : </td>
                            <td>
                                @{{ (!!connoteModel.job) ? connoteModel.job.status_label : 'เตรียมส่งของ' }}
                            </td>
                        </tr>
                        <tr>
                            <td>ชื่อผู้เซ็นรับของ : </td>
                            <td>@{{ (!!connoteModel.job) ? connoteModel.job.receiver_name : '-' }}</td>
                        </tr>
                         <tr>
                            <td>ชื่อผู้รับ : </td>
                            <td>@{{ connoteModel.consignee_name }}</td>
                        </tr>
                        <tr>
                            <td>ชื่อสถานที่ : </td>
                            <td>@{{ connoteModel.consignee_company }}</td>
                        </tr>
                        <tr>
                            <td>สถานที่ปลายทาง : </td>
                            <td>@{{ connoteModel.consignee_address }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-if="connoteModel == false" class="row mw-center" style="margin: 5% 8% 5% 0;">
            ไม่พบข้อมูล
        </div>
    </div>
</section>
<!-- DATA -->
<div id="mw-url-public-tracking" data-url="{{ \URL::route('home.public_tracking.index.post') }}">