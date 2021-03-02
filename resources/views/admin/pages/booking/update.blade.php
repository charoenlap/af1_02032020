<!-- Header -->
<div class="page-header" style="margin-bottom: 3%">
    <!-- Title -->
    <div class="col-sm-10 col-xs-12">
        <h1 class="mw-page-title page-title">
            {{ config('labels.menu.booking.'.helperLang()) }} : UPDATE
        </h1>
        <!-- BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <a href="{{ \URL::route('admin.booking.index.get') }}">
                    {{ config('labels.menu.booking.'.helperLang()) }}
                </a>
            </li>
            <li class="active">
                Update
            </li>
        </ol>
    </div>
</div>
<!-- BODY -->
<div class="mw-page-content page-content">
    <div id="mw-vue-booking" class="panel"
    data-model="{{ $ctmModels }}"
    data-province="{{ json_encode($provinces) }}">
        <div class="page-body mw-bg-white container-fluid" style="padding: 30px">
            <!-- CHOOSE CUSTOMER -->
            <div class="row mw-mg-bm-10">
                <div class="">
                    <h4 class="mw-mg-0">{{ '1. ลูกค้า' }}</h4>
                    <hr class="mw-mg-0 mw-bg-color">
                </div>
                <div class="row mw-mg-tp-20">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <span class="control-label"><small>{{ '' }}</small></span>
                            <select id="mw-select-ctm" class="form-control">
                                <option value="0">-- เลือกลูกค้า --</option>
                                <option v-for="(ctmModel, index) in ctmModels" v-bind:value="index">
                                    @{{ ctmModel.key+' '+ctmModel.name }}
                                </option>
                            </select>
                            <div class="mw-text-error" id="form-error-customer_id"></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                    </div>
                </div>
            </div>
            <!-- GENERAL INFORMATION -->
            <div v-if="ctmChosen != 0" class="row mw-mg-bm-10">
                <div class="">
                    <h4 class="mw-mg-0">{{ '2. รายละเอียดการรับสินค้า' }}</h4>
                    <hr class="mw-mg-0 mw-bg-color">
                </div>
                <div class="row mw-mg-tp-20">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'ชื่อผู้ติดต่อ' }}</small></span>
                            <input name="person_name" type="text" class="form-control mw-mg-0 mw-font-size-1em"
                            v-model="ctmChosen.person" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'เบอร์ติดต่อ' }}</small></span>
                            <input name="person_mobile" type="text" class="form-control mw-mg-0 mw-font-size-1em"
                            v-model="ctmChosen.mobile" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'วันที่ให้ไปรับ' }}</small>
                                <img id="mw-wait-datepicker" width="20" src="{{ urlAdminImage().'/ajax-loader-50.gif' }}">
                            </span>
                            <input name="get_date" type="text" disabled=""
                            class="form-control mw-mg-0 mw-font-size-1em mw-input-datepicker"
                            value="{{ date('d/m/Y') }}"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'เวลาที่ให้ไปรับ' }}</small></span>
                            <input name="get_time" type="text" class="form-control mw-mg-0 mw-font-size-1em"
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
                                <option class="mw-font" value="{{ $carModel->id }}">{{ $carModel->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'ประเภทงาน' }}</small></span>
                            <select id="mw-select-express" class="form-control mw-font-size-1em">
                                <option value="0">ส่งพัสดุ</option>
                                <option value="1">ส่งพัสดุด่วน</option>
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
            <div v-if="ctmChosen != 0" class="row mw-mg-bm-10">
                <div class="mw-mg-bm-10">
                    <h4 class="mw-mg-0">{{ '3. สถานที่รับสินค้า' }}</h4>
                    <hr class="mw-mg-0 mw-bg-color">
                </div>
                <div class="row mw-mg-tp-20">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'สถานที่รับของ' }}</small></span>
                            <textarea name="address" class="form-control mw-font-size-1em" rows="4"
                            v-model="ctmChosen.address"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'เขต / อำเภอ' }}</small></span>
                            <input name="district" type="text" class="form-control mw-mg-0 mw-font-size-1em"
                            v-model="ctmChosen.district" />
                        </div>
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'จังหวัด' }}</small></span>
                            <select id="mw-select-province" class="form-control mw-font-size-1em"
                            v-model="ctmChosen.province">
                                @foreach ($provinces as $province)
                                <option class="mw-font">{{ $province }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <span class="control-label"><small>{{ 'รหัสไปรษณีย์' }}</small></span>
                            <input name="postcode" type="text" class="form-control mw-mg-0 mw-font-size-1em"
                            v-model="ctmChosen.postcode" />
                        </div>
                    </div>
                </div>
            </div>
            <!-- CONNOTE -->
            <div class="row mw-mg-bm-10" v-if="ctmChosen != 0">
                <div class="mw-mg-bm-10">
                    <h4 class="mw-mg-0">
                        4. ที่อยู่ปลายทาง
                        <a class="mw-btn-add-target btn grey" @click="addTarget()">
                            <i class="fa-plus"></i> เพิ่มที่อยู่ใหม่
                        </a>
                    </h4>
                    <hr class="mw-mg-0 mw-bg-color">
                </div>
                <!-- ADDED VALUE -->
                <div class="row">
                    <div class="col-sm-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="10%" class="mw-center">เลือกที่อยู่ปลายทาง</th>
                                <th width="50%">ชื่อ: บริษัท: ที่อยู่</th>
                                <th width="20%">Customer Ref.</th>
                                <th width="20%" class="mw-center">
                                    <div class="checkbox-custom checkbox-danger">
                                        <input type="checkbox" v-model="isCod">
                                        <label>เก็บเงินปลายทาง</label>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="" v-for="(pointModel, key) in ctmChosen.points">
                                <td class="mw-middle">
                                    <div class="checkbox-custom checkbox-info">
                                        <input type="checkbox" class="mw-checkbox-point"
                                        v-bind:value="pointModel"  v-model="pointChosens">
                                        <label></label>
                                    </div>
                                </td>
                                <td>
                                    <p class="mw-mg-0">@{{ pointModel.person+' '+pointModel.mobile }}</p>
                                    <p class="mw-mg-0">@{{ pointModel.name }}</p>
                                    <p class="mw-mg-0">@{{ pointModel.address+' '+pointModel.district+' '+pointModel.province+' '+pointModel.postcode }}</p>
                                </td>
                                <td class="mw-center mw-middle">
                                    <input type="text" v-model="pointModel.customer_ref" class="form-control mw-mg-0">
                                </td>
                                <td class="mw-center mw-middle">
                                    <div class="input-group">
                                        <input type="number" v-model="pointModel.value" class="form-control" v-bind:disabled="!isCod">
                                        <span class="input-group-addon">฿</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
            <!-- SAVE -->
            <div v-if="ctmChosen != 0" class="row mw-center" style="margin-top: 40px">
                <div id="mw-btn-save" class="btn btn-success" style="min-width: 200px" @click="confirmSave()">
                    <i class="fa-save"></i> ยืนยันการเปิดงาน
                </div>
            </div>
        </div>
    </div>
</div>
<!-- DATA -->
<div id="mw-url-booking-post" data-url="{{ \URL::route('admin.booking.update.post', $bookingModel->id) }}"></div>
<div id="mw-url-add-point" data-url="{{ \URL::route('admin.booking.add_point.post') }}"></div>