<!-- Header -->
<div class="page-header" style="margin-bottom: 3%">
    <!-- Title -->
    <div class="col-sm-10 col-xs-12">
        <h1 class="mw-page-title page-title">
            ข้อมูลส่วนตัว <small>[PROFILE]</small>
        </h1>
    </div>
</div>
<!-- Panel -->
<div class="mw-page-content page-content">
    <!-- TABLE -->
    <div class="panel">
        <div class="panel-body container-fluid">
            <!-- BODY -->
            <div id="mw-vue-profile" class="page-body mw-bg-white" style="margin-top: 10px"
            data-model="{{ $empModel }}">
                <table id="mw-table-profile" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="30%" class="">ข้อมูล</th>
                            <th width="70%" class="">รายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- KEY -->
                        <tr>
                            <td>รหัสพนักงาน</td>
                            <td class="">@{{ empModel.emp_key }}</td>
                        </tr>
                        <!-- POSITION -->
                        <tr>
                            <td>ตำแหน่ง</td>
                            <td class="mw-row-value">
                                <div class="">
                                @{{ empModel.position.label }}
                                </div>
                            </td>
                        </tr>
                        <!-- NICKNAME -->
                        <tr>
                            <td>ชื่อเล่น</td>
                            <td class="mw-row-value">
                                <div class="mw-btn-value mw-font-underline mw-cursor-pointer">
                                @{{ empModel.nickname }}
                                </div>
                                <div class="mw-input-value input-group hidden">
                                    <input type="text" v-model="empModel.nickname" class="form-control mw-font" autofocus="">
                                    <div class="mw-btn-save mw-white label-info input-group-addon mw-cursor-pointer"
                                    @click="saveData()">
                                        <i class="fa-save"></i>
                                    </div>
                                    <div class="input-group-addon mw-cursor-pointer" @click="changeInputToText()">
                                        <i class="fa-close"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- NAME -->
                        <tr>
                            <td>ชื่อจริง</td>
                            <td class="mw-row-value">
                                <div class="">
                                @{{ empModel.title }} @{{ empModel.firstname }} @{{ empModel.lastname }}
                                </div>
                            </td>
                        </tr>
                        <!-- MOBILE -->
                        <tr>
                            <td>หมายเลขโทรศัพท์</td>
                            <td class="mw-row-value">
                                <div class="mw-btn-value mw-font-underline mw-cursor-pointer">
                                @{{ empModel.phone }}
                                </div>
                                <div class="mw-input-value input-group hidden">
                                    <input type="text" v-model="empModel.phone" class="form-control mw-font" autofocus="">
                                    <div class="mw-btn-save mw-white label-info input-group-addon mw-cursor-pointer"
                                    @click="saveData()">
                                        <i class="fa-save"></i>
                                    </div>
                                    <div class="input-group-addon mw-cursor-pointer" @click="changeInputToText()">
                                        <i class="fa-close"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- ADDRESS -->
                        <tr>
                            <td>ที่อยู่</td>
                            <td class="mw-row-value">
                                <div class="mw-btn-value mw-font-underline mw-cursor-pointer">
                                @{{ empModel.address }}
                                </div>
                                <div class="mw-input-value input-group hidden">
                                    <input type="text" v-model="empModel.address" class="form-control mw-font" autofocus="">
                                    <div class="mw-btn-save mw-white label-info input-group-addon mw-cursor-pointer"
                                    @click="saveData()">
                                        <i class="fa-save"></i>
                                    </div>
                                    <div class="input-group-addon mw-cursor-pointer" @click="changeInputToText()">
                                        <i class="fa-close"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- ID CARD -->
                        <tr>
                            <td>รหัสบัตรประจำตัวประชาชน</td>
                            <td class="mw-row-value">
                                <div class="mw-btn-value mw-font-underline mw-cursor-pointer">
                                @{{ empModel.id_card }}
                                </div>
                                <div class="mw-input-value input-group hidden">
                                    <input type="text" v-model="empModel.id_card" class="form-control mw-font" autofocus="">
                                    <div class="mw-btn-save mw-white label-info input-group-addon mw-cursor-pointer"
                                    @click="saveData()">
                                        <i class="fa-save"></i>
                                    </div>
                                    <div class="input-group-addon mw-cursor-pointer" @click="changeInputToText()">
                                        <i class="fa-close"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- DATA -->
<div id="mw-url-profile-post" data-url="{{ \URL::route('admin.profile.update.post', $empModel->id) }}"></div>