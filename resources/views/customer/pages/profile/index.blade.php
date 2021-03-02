<section class="page-section" style="padding-top: 0px; margin-top: -40px;">
    <div class="col-md-10 col-md-offset-1 col-sm-12 col-xs-12 mw-pd-lr-10 mw-bg-white mw-mg-tp-20" style="padding: 40px">
        <!-- HEADER -->
        <div class="mw-pd-tb-10 mw-bg-white">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="mw-center">
                        ข้อมูลส่วนตัว <small>[PROFILE]</small>
                    </h3>
                </div>
            </div>
        </div>
        <!-- BODY -->
        <div id="mw-vue-profile" class="page-body mw-bg-white" style="margin-top: 10px"
        data-model="{{ $ctmModel }}">
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
                        <td>รหัสลูกค้า</td>
                        <td class="">{{$ctmModel->key}}</td>
                    </tr>
                    <!-- EMAIL -->
                    <tr>
                        <td>Email</td>
                        <td class="mw-row-value">
                            <div class="">{{$ctmModel->email}}</div>
                        </td>
                    </tr>
                    <!-- NAME -->
                    <tr>
                        <td>ชื่อบริษัท</td>
                        <td class="mw-row-value">
                            <div class="">{{$ctmModel->name}}</div>
                        </td>
                    </tr>
                    <!-- PERSON -->
                    <tr>
                        <td>ชื่อผู้ติดต่อ</td>
                        <td class="mw-row-value">
                            <div class="mw-btn-value mw-font-underline mw-cursor-pointer">
                            {{$ctmModel->person}}
                            </div>
                            <div class="mw-input-value input-group hidden">
                                <input type="text" v-model="ctmModel.person" class="form-control mw-font" autofocus="">
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
                    <!-- MOBILE -->
                    <tr>
                        <td>เบอร์ติดต่อ</td>
                        <td class="mw-row-value">
                            <div class="mw-btn-value mw-font-underline mw-cursor-pointer">
                            {{$ctmModel->mobile}}
                            </div>
                            <div class="mw-input-value input-group hidden">
                                <input type="text" v-model="ctmModel.mobile" class="form-control mw-font" autofocus="">
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
                            <div class="">{{$ctmModel->address}}</div>
                        </td>
                    </tr>
                    <!-- DISTRICT -->
                    <tr>
                        <td>เขต / อำเภอ</td>
                        <td class="mw-row-value">
                            <div class="">{{$ctmModel->district}}</div>
                        </td>
                    </tr>
                    <!-- PROVINCE -->
                    <tr>
                        <td>จังหวัด</td>
                        <td class="mw-row-value">
                            <div class="">{{$ctmModel->province}}</div>
                        </td>
                    </tr>
                    <tr>
                        <td>รหัสไปรษณีย์</td>
                        <td class="mw-row-value">
                            <div class="">{{$ctmModel->postcode}}</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- DATA -->
    <div id="mw-url-profile-post" data-url="{{ \URL::route('home.profile.update.post', $ctmModel->id) }}"></div>
</section>