<section class="page-section" style="padding-top: 0px; margin-top: -40px; font-family: sans-serif !important">
    <div class="col-md-10 col-md-offset-1 col-sm-12 col-xs-12 mw-pd-lr-10 mw-bg-white mw-mg-tp-20" style="padding: 40px">
        <!-- HEADER -->
        <div class="mw-pd-tb-10 mw-bg-white">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="mw-center">เปิดงานใหม่ <small>[เก็บเงินปลายทาง COD]</small> </h3>
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
            data-province="{{ json_encode($provinces) }}">
                <div class="mw-mg-bm-10">
                    <h4 class="mw-mg-0">{{ '3. จำนวนเงินเก็บปลายทาง' }}</h4>
                    <hr class="mw-mg-0 mw-bg-color">
                </div>

                <!-- NEW VALUE -->
                <h5 style="margin-top: 20px; margin-bottom: 0;">กรุณาเลือกที่อยู่ปลายทาง
                    <a class="mw-btn-add-target btn grey" @click="addRow()">
                        <i class="fa-plus"></i> เพิ่มที่อยู่ใหม่
                    </a>
                </h5>
                <div class="row">
                    <div class="col-sm-12">
                        <div v-for="(codValue, key) in codValues"
                        class="checkbox-custom checkbox-info">
                            <input type="checkbox" class="mw-checkbox-point">
                            <label style="width: 100%" >
                                <h5 class="mw-checkbox-label">
                                    <a href="{{ urlHomeImage().'/connote-sample.jpg' }}" target="_blank">
                                        <span><i class="fa-print" style="margin: 0 1%;"></i></span>
                                    </a>
                                    <span @click="removeRow(key)">
                                        <i class="fa-trash" style="margin-right: 2%; "></i>
                                    </span>
                                    @{{ codValue.cod_value+'฿ '+codValue.person+' '+codValue.address+' '+codValue.district+' '+codValue.province+' '+codValue.postcode }}
                                </h5>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- SAVE -->
            <div class="row mw-center" style="margin-top: 60px">
                <div id="mw-btn-save" class="btn btn-success" style="min-width: 200px">
                    <i class="fa-save"></i> บันทึก
                </div>
            </div>

        </div>
    </div>
</section>

<!-- DATA -->
<input type="hidden" name="customer_id" value="{{ $customerModel->id }}" />
<div id="mw-url-booking-post" data-url="{{ \URL::route('home.booking_cod.create.post') }}"></div>