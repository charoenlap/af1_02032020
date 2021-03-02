<!-- Header -->
<div class="page-header" style="margin-bottom: 3%">
    <!-- Title -->
    <div class="col-sm-8 col-xs-12">
        <h1 class="mw-page-title page-title">
            {{ $connoteModel->key }}
            <small>UPDATE CONNOTE</small>
        </h1>
        <!-- BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <a href="{{ \URL::route('admin.connote.index.get') }}">
                    {{ config('labels.menu.connote.'.helperLang()) }}
                </a>
            </li>
            <li class="active">
                Update
            </li>
        </ol>
    </div>
    <div class="col-sm-4 mw-right">
        @if($connoteModel->status == 'complete')
        @endif
        @if($connoteModel->status == 'cancel')
        <h4 class="">สถานะ : <span class="red-700">ยกเลิกการส่ง <i class="fa-close"></i></span></h4>
        @endif
    </div>
</div>
<!-- Panel -->
<div id="mw-model-connote" class="mw-page-content page-content" style="margin-top: 5%;"
    data-model="{{ $connoteModel }}">
    <div class="panel">
        <div class="panel-body container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h4>เลข Connote</h4>
                    <input id="mw-input-key" name="key" type="text" class="form-control"
                    v-model="connoteModel.key"/>
                    <div class="mw-text-error" id="form-error-key"></div>
                </div>
                <div class="col-sm-6">
                    <h4>Customer Reference</h4>
                    <input id="mw-input-customer_ref" name="customer_ref" type="text" class="form-control"
                    v-model="connoteModel.csn.customer_ref"/>
                    <div class="mw-text-error" id="form-error-key"></div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h4>SHIPPER <small>ผู้ส่ง</small></h4>
                    <div class="form-group">
                        <span class="control-label">Sender's Name <small>(ชื่อผู้ส่ง)</small></span>
                        <input type="text" class="form-control" v-model="connoteModel.shipper_name"/>
                        <div class="mw-text-error" id="form-error-shipper_name"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">Sender's Company <small>(บริษัทผู้ส่ง)</small></span>
                        <input type="text" class="form-control" v-model="connoteModel.shipper_company"/>
                        <div class="mw-text-error" id="form-error-shipper_company"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">Shipper Address <small>(ที่อยู่ผู้ส่ง)</small></span>
                        <textarea type="text" class="form-control" v-model="connoteModel.shipper_address"></textarea>
                        <div class="mw-text-error" id="form-error-shipper_address"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">Shipper Phone <small>(โทรศัพท์)</small></span>
                        <input type="text" class="form-control" v-model="connoteModel.shipper_phone"/>
                        <div class="mw-text-error" id="form-error-shipper_phone"></div>
                    </div>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <h4>
                        CONSIGNEE <small>ผู้รับ</small>
                        <span id="mw-btn-choose-point" class="pull-right mw-color-2 mw-mg-lr-5"
                        style="cursor: pointer; text-decoration: underline;" @click="showPoint()">
                            <small class="mw-color-2">เลือกที่อยู่ปลายทางของผู้รับ</small>
                        </span>
                        <span class="pull-right">
                            <small @click="addNewPoint()" class="mw-color-2 mw-mg-lr-5"
                            style="cursor: pointer; text-decoration: underline;">
                                เพิ่มที่อยู่ใหม่
                            </small>
                            /
                        </span>
                    </h4>
                    <div class="form-group">
                        <span class="control-label">Receiver's Name <small>(ชื่อผู้รับ)</small></span>
                        <input type="text" class="form-control" v-model="connoteModel.consignee_name"/>
                        <div class="mw-text-error" id="form-error-consignee_name"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">Consignee Company <small>(บริษัทผู้รับ)</small></span>
                        <input type="text" class="form-control" v-model="connoteModel.consignee_company"/>
                        <div class="mw-text-error" id="form-error-consignee_company"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">Consignee Address <small>(ที่อยู่ผู้รับ)</small></span>
                        <textarea type="text" class="form-control" v-model="connoteModel.consignee_address"></textarea>
                        <div class="mw-text-error" id="form-error-consignee_address"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">Consignee Phone <small>(โทรศัพท์)</small></span>
                        <input type="text" class="form-control" v-model="connoteModel.consignee_phone"/>
                        <div class="mw-text-error" id="form-error-consignee_phone"></div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <hr />
                </div>
                <div class="col-sm-12 col-xs-12">
                    <h4>DETAIL</h4>
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <span class="control-label">SERVICE TYPE <small>(ประเภทบริการ)</small></span>
                                <select class="form-control" v-model="connoteModel.service">
                                    <option value="{{ $connoteRepo->oneway }}">{{ $connoteRepo->label_oneway }}</option>
                                    <option value="{{ $connoteRepo->return }}">{{ $connoteRepo->label_return }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <span class="control-label">EXPRESS  <small>(ด่วน)</small></span>
                                <select class="form-control" v-model="connoteModel.express">
                                    <option value="0">{{ $connoteRepo->label_express_0 }}</option>
                                    <option value="1">{{ $connoteRepo->label_express_1 }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <span class="control-label">COD Value <small>(จำนวนเงินเก็บปลาย)</small></span>
                                <input id="mw-input-key" type="text" class="form-control mw-center"
                                v-model="connoteModel.cod_value" :disabled="connoteModel.cod == 0"/>
                            </div>
                        </div>
                        <div class="form-group">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <hr />
                </div>
                <div class="col-xs-12">
                    <h4>SIZE & WEIGHT <small>ขนาดและน้ำหนัก</small></h4>
                    <div v-for="(piece, i) in connoteModel.detail_pieces">
                        <div class="form-group">
                            @{{ (i*1+1)+'.' }}
                            <div class="input-group">
                                <span class="input-group-addon">กว้าง  (นิ้ว)</span>
                                <input id="mw-input-key" name="key" type="text" class="form-control mw-center"
                                v-model="piece.width"/>
                                <span class="input-group-addon">ยาว (นิ้ว)</span>
                                <input id="mw-input-key" name="key" type="text" class="form-control mw-center"
                                v-model="piece.length"/>
                                <span class="input-group-addon">สูง (นิ้ว)</span>
                                <input id="mw-input-key" name="key" type="text" class="form-control mw-center"
                                v-model="piece.height"/>
                                <span class="input-group-addon">น้ำหนัก (กก.)</span>
                                <input id="mw-input-key" name="key" type="text" class="form-control mw-center"
                                v-model="piece.weight"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <hr />
                </div>

                <div class="col-xs-12">
                    <div class="form-group pull-left">
                        <div @click="cancelConnote()" class="btn btn-danger btn-outline">
                            ยกเลิก Connote
                        </div>
                    </div>
                    <div class="form-group pull-right">
                        <a href="{{ url()->previous() }}"
                        class="btn btn-default" style="min-width: 100px">
                            ย้อนกลับ
                        </a>
                        <button class="btn btn-success" style="min-width: 100px" @click="updateData()">
                            <i class="fa-save"></i> บันทึก
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DATA -->
<div id="mw-url-update" data-url="{{ \URL::route('admin.connote.update.post', $connoteModel->id) }}"></div>
<div id="mw-url-create-point" data-url="{{ \URL::route('admin.customer.point_update.post', [$connoteModel->booking->customer_id, 0]) }}"></div>
<div id="mw-data-province" data-content="{{ json_encode($provinces) }}"></div>
<div id="mw-url-gen-connote" data-url="{{ \URL::route('admin.connote.gen_data.post', $connoteModel->id) }}"></div>
<div id="mw-url-cancel-connote" data-url="{{ \URL::route('admin.connote.cancel.post', $connoteModel->id) }}"></div>