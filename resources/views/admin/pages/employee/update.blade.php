<!-- Header -->
<div class="page-header" style="margin-bottom: 3%">
    <!-- Title -->
    <div class="col-sm-10 col-xs-12">
        <h1 class="mw-page-title page-title">
            {{ config('labels.menu.employee.'.helperLang()) }} : UPDATE
        </h1>
        <!-- BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <a href="{{ \URL::route('admin.employee.index.get') }}">
                    {{ config('labels.menu.employee.'.helperLang()) }}
                </a>
            </li>
            <li class="active">
                Update
            </li>
        </ol>
    </div>
</div>
<!-- Panel -->
<div id="mw-model-employee" class="mw-page-content page-content"
    data-model="{{ $empModel }}"
    data-position="{{ $posModels }}">
    <div class="panel">
        <div class="panel-body container-fluid">
            <div class="row">
                <div class="col-sm-8 col-xs-12">
                    <div class="form-group">
                        <span class="control-label">{{ 'รหัสพนักงาน' }}</span>
                        <input name="emp_key" type="text" class="form-control" value="" v-model="empModel.emp_key" />
                        <div class="mw-text-error" id="form-error-emp_key"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'ตำแหน่ง' }}</span>
                        <select class="form-control" v-model="empModel.position_id">
                            <option v-for="posModel in posModels" v-bind:value="posModel.id">
                                @{{ posModel.label }}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'ชื่อเล่น' }}</span>
                        <input name="nickname" type="text" class="form-control" value="" v-model="empModel.nickname"/>
                        <div class="mw-text-error" id="form-error-nickname"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'ชื่อจริง' }}</span>
                        <div class="input-group">
                            <span class="control-label input-group-addon">{{ 'คำนำหน้า' }}</span>
                            <input name="title" type="text" class="form-control" value="" v-model="empModel.title">
                            <span class="control-label input-group-addon">{{ 'ชื่อจริง' }}</span>
                            <input name="firstname" type="text" class="form-control" value="" v-model="empModel.firstname"/>
                        </div>
                        <div class="mw-text-error" id="form-error-title"></div>
                        <div class="mw-text-error" id="form-error-firstname"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'นามสกุล' }}</span>
                        <input name="lastname" type="text" class="form-control" value="" v-model="empModel.lastname"/>
                        <div class="mw-text-error" id="form-error-lastname"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'หมายเลขโทรศัพท์' }}</span>
                        <input name="phone" type="text" class="form-control" value="" v-model="empModel.phone"/>
                        <div class="mw-text-error" id="form-error-phone"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'ที่อยู่' }}</span>
                        <textarea name="address" type="text" class="form-control" value="" v-model="empModel.address"> </textarea>
                        <div class="mw-text-error" id="form-error-address"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'รหัสบัตรประจำตัวประชาชน' }}</span>
                        <input name="id_card" type="text" class="form-control" value="" v-model="empModel.id_card" maxlength="13" />
                        <div class="mw-text-error" id="form-error-id_card"></div>
                    </div>
                    <div class="form-group">
                        <a href="{{ \URL::route('admin.employee.index.get') }}"
                        class="btn btn-default" style="min-width: 100px">
                            ย้อนกลับ
                        </a>
                        <button class="btn btn-success" style="min-width: 100px" @click="updateData()">
                            บันทึก
                        </button>
                    </div>
                </div>
                <!-- PHOTO -->
                <div class="col-sm-3 col-xs-12">
                    <img style="max-width: 100%; height: auto;" v-bind:src="empModel.avatar_url" >
                    <div class="form-group">
                        <input type="file" name="mw_product_thumbnail" class="mw-input-upload-avatar form-control"
                        data-widht="300" data-order="1" />
                        <div class="mw-text-error" id="form-error-avatar"></div>
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
                        <span class="control-label">{{ 'รหัสผ่านใหม่ (ตัวเลข 4 หลัก)' }}</span>
                        <input name="new_pwd" type="text" class="form-control" value="" maxlength="4" />
                        <div class="mw-text-error" id="form-error-new_pwd"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'ยืนยันรหัสผ่านใหม่' }}</span>
                        <input name="confirm_pwd" type="text" class="form-control" value="" maxlength="4"/>
                        <div class="mw-text-error" id="form-error-confirm_pwd"></div>
                    </div>
                    <div class="form-group">
                        <a href="{{ \URL::route('admin.employee.index.get') }}"
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
<div id="mw-url-update" data-url="{{ \URL::route('admin.employee.update.post', $empModel->id) }}"></div>
<div id="mw-url-update-pwd" data-url="{{ \URL::route('admin.employee.update_pwd.post', $empModel->id) }}"></div>