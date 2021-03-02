<!-- Header -->
<div class="page-header" style="margin-bottom: 3%">
    <!-- Title -->
    <div class="col-sm-10 col-xs-12">
        <h1 class="mw-page-title page-title">
            {{ config('labels.menu.customer.'.helperLang()) }} : UPDATE
        </h1>
        <!-- BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <a href="{{ \URL::route('admin.customer.index.get') }}">
                    {{ config('labels.menu.customer.'.helperLang()) }}
                </a>
            </li>
            <li class="active">
                Update
            </li>
        </ol>
    </div>
</div>
<!-- Panel -->
<div id="mw-model-customer" class="mw-page-content page-content"
    data-model="{{ $ctmModel }}"
    data-province="{{ json_encode($provinces) }}">
    <div class="panel">
        <div class="panel-body container-fluid">
            <h3>ข้อมูลทั่วไป</h3>
            <div class="row">
                <div class="col-sm-8 col-xs-12">
                    <div class="form-group">
                        <span class="control-label">{{ 'รหัสลูกค้า * (ตัวอักษรภาษาอังกฤษ 3 ตัว)' }}</span>
                        <input id="mw-input-key" name="key" type="text" class="form-control" value="" placeholder="ABC"
                        v-model="ctmModel.key" maxlength="3" />
                        <div class="mw-text-error" id="form-error-key"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'ชื่อบริษัท *' }}</span>
                        <input name="name" type="text" class="form-control" value="" placeholder="ชื่อบริษัท"
                        v-model="ctmModel.name" />
                        <div class="mw-text-error" id="form-error-name"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'อีเมลล์ลูกค้า *' }}</span>
                        <input name="email" type="text" class="form-control" value="" placeholder="customer@mail.com"
                        v-model="ctmModel.email"/>
                        <div class="mw-text-error" id="form-error-email"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'ที่อยู่ *' }}</span>
                        <textarea name="address" type="text" class="form-control" value="" v-model="ctmModel.address"> </textarea>
                        <div class="mw-text-error" id="form-error-address"></div>
                        </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'อำเภอ / เขต *' }}</span>
                        <input name="district" type="text" class="form-control" value="" v-model="ctmModel.district"/>
                        <div class="mw-text-error" id="form-error-district"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'จังหวัด' }}</span>
                        <select v-model="ctmModel.province" class="form-control">
                            <option v-for="province in provinces" v-bind:value="province">
                                @{{ province }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'รหัสไปรษณีย์ *' }}</span>
                        <input name="postcode" type="text" class="form-control" value="" v-model="ctmModel.postcode"/>
                        <div class="mw-text-error" id="form-error-postcode"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'ผู้ติดต่อ *' }}</span>
                        <input name="person" type="text" class="form-control" value="" v-model="ctmModel.person"/>
                        <div class="mw-text-error" id="form-error-person"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'เบอร์ติดต่อ *' }}</span>
                        <input name="mobile" type="text" class="form-control" value="" v-model="ctmModel.mobile"/>
                        <div class="mw-text-error" id="form-error-mobile"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'เบอร์บริษัท' }}</span>
                        <input name="office_tel" type="text" class="form-control" value="" v-model="ctmModel.office_tel"/>
                        <div class="mw-text-error" id="form-error-office_tel"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'เบอร์แฟกซ์' }}</span>
                        <input name="fax" type="text" class="form-control" value="" v-model="ctmModel.fax"/>
                    </div>
                    <div class="form-group">
                        <a href="{{ \URL::route('admin.customer.index.get') }}"
                        class="btn btn-default" style="min-width: 100px">
                            ย้อนกลับ
                        </a>
                        <button class="btn btn-success" style="min-width: 100px" @click="updateData()">
                            บันทึก
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-body container-fluid">
            <h3>รหัสผ่าน</h3>
            <div class="row">
                <div class="col-sm-8 col-xs-12">
                    <div class="form-group">
                        <span class="control-label">{{ 'New Password' }}</span>
                        <input name="new_pwd" type="password" class="form-control" value=""/>
                        <div class="mw-text-error" id="form-error-new_pwd"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'Confirm Password' }}</span>
                        <input name="confirm_pwd" type="password" class="form-control" value=""/>
                        <div class="mw-text-error" id="form-error-confirm_pwd"></div>
                    </div>
                    <div class="form-group">
                        <a href="{{ \URL::route('admin.customer.index.get') }}"
                        class="btn btn-default" style="min-width: 100px">
                            ย้อนกลับ
                        </a>
                        <button class="btn btn-warning" style="min-width: 100px" @click="updatePwd()">
                            บันทึก
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DATA -->
<div id="mw-url-update" data-url="{{ \URL::route('admin.customer.update.post', $ctmModel->id) }}"></div>
<div id="mw-url-update-pwd" data-url="{{ \URL::route('admin.customer.update_pwd.post', $ctmModel->id) }}"></div>