<div id="mw-vue-contain">
    <!-- Header -->
    <div class="page-header">
        <div class="row">
            <!-- Title -->
            <div class="col-sm-9 col-xs-12">
                {{ $ctmModel->name }}
                <h1 class="mw-page-title page-title">
                    เพิ่มที่อยู่ปลายทางโดย Excel
                </h1>
                <!-- BREADCRUMB -->
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ \URL::route('admin.customer.index.get') }}">
                            {{ config('labels.menu.customer.'.helperLang()) }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ \URL::route('admin.customer.point.get', $ctmModel->id) }}">
                            {{ config('labels.menu.point.'.helperLang()) }}
                        </a>
                    </li>
                    <li class="active">
                        Import by Excel
                    </li>
                </ol>
            </div>
            <div class="col-sm-2">
                <a class="btn btn-default btn-outline" href="{{ urlBase().'/af1-customer-point-template.xlsx'}}">
                    <i class="fa-arrow-circle-down" style="margin: 0 5px;"></i>
                    Download Templete
                </a>
            </div>
        </div>
    </div>
    <div class="page-content">
        <div class="panel">
            <div class="panel-body">
                <div class="row" v-if="!pointData">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{ config('label_employee.import.choose_file.'.helperLang()) }}</label>
                        </div>
                    </div>
                </div>
                <!-- UPLOAD FILE. -->
                <div class="row" v-if="!pointData">
                    <div class="col-md-12">
                        <div class="form-group">
                            <h4>เลือกไฟล์ Excel</h4>
                            <input type="file" name="mw_import_excel" class="mw-input-import-excel form-control"
                            data-widht="300" data-order="1" @change="readData()" accept=".xls,.xlsx"/>
                            <div class="mw-text-error" id="form-error-invalid"></div>
                        </div>
                    </div>
                </div>
                <!-- READED TABLE. -->
                <div class="row" v-if="!!pointData">
                    <div class="col-sm-12" style="margin-bottom: 20px;">
                        <button style="width: 150px" class="btn btn-success pull-right" @click="updateData()">
                            <i class="fa-save"></i>
                            {{ config('labels.btn.save.'.helperLang()) }}
                        </button>
                        <button style="width: 150px" class="btn btn-default pull-right mw-mg-lr-10" @click="clearData()">
                            {{ config('labels.btn.cancel.'.helperLang()) }}
                        </button>
                    </div>
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-stripped table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%" class="mw-center"><small>ลำดับ</small></th>
                                        <th width="15%"><small>ชื่อผู้รับ</small></th>
                                        <th width="15%"><small>สถานที่</small></th>
                                        <th width="20%"><small>ที่อยู่</small></th>
                                        <th width="10%"><small>เขตอำเภอ</small></th>
                                        <th width="10%"><small>จังหวัด</small></th>
                                        <th width="10%"><small>รหัสไปรษณีย์</small></th>
                                        <th width="10%"><small>เบอร์ติดต่อ</small></th>
                                        <th width="5%" class="mw-center"><small>ลบ</small></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(row, r) in pointData" style="cursor: pointer;">
                                        <td class="mw-center"><small>@{{ (r+1) }}</small></td>
                                        <td><small>@{{ (!!(row['A']) ? row['A'] : '-' ) }}</small></td>
                                        <td><small>@{{ (!!(row['B']) ? row['B'] : '-' ) }}</small></td>
                                        <td><small>@{{ (!!(row['C']) ? row['C'] : '-' ) }}</small></td>
                                        <td><small>@{{ (!!(row['D']) ? row['D'] : '-' ) }}</small></td>
                                        <td><small>@{{ (!!(row['E']) ? row['E'] : '-' ) }}</small></td>
                                        <td><small>@{{ (!!(row['F']) ? row['F'] : '-' ) }}</small></td>
                                        <td><small>@{{ (!!(row['G']) ? row['G'] : '-' ) }}</small></td>
                                        <td class="mw-center">
                                            <div class="btn btn-default btn-xs" @click="removeData(r)">
                                                <small><i class="fa-trash"></i></small>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- DATA -->
<div id="mw-url-read-file" data-url="{{ \URL::route('admin.customer.read_excel.post', $ctmModel->id) }}"></div>
<div id="mw-url-update-data" data-url="{{ \URL::route('admin.customer.import_excel.post', $ctmModel->id) }}"></div>
