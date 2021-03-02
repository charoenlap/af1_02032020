<!-- Header -->
<div class="page-header" style="margin-bottom: 3%">
    <!-- Title -->
    <div class="col-sm-10 col-xs-12">
        <h1 class="mw-page-title page-title">
            {{ config('labels.menu.position.'.helperLang()) }}
        </h1>
    </div>
</div>
<!-- Panel -->
<div class="mw-page-content page-content">
    <!-- TABLE -->
    <div class="panel">
        <div class="panel-body container-fluid">
            <div class="table-responsive" style="">
                <table id="mw-table-position" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <!-- KEY -->
                            <th width="10%" class="mw-center">ลำดับ</th>
                            <!-- DETAIL -->
                            <th width="30%" class="">ตำแหน่ง</th>
                            <th width="50%" class="">สิทธิ์การเข้าถึง</th>
                            <th width="10%" class="mw-center">บันทึก</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posModels as $posModel)
                        <tr class="mw-position-tr">
                            <td class="mw-center">
                                {{ $posModel->id }}
                            </td>
                            <td class="">
                                {{ $posModel->label }}
                            </td>
                            <td>
                                @foreach($posModel->access as $access => $method)
                                <div class="row">
                                    <div class="col-sm-4">{{ ucfirst($access) }}</div>
                                    <div class="col-sm-8">{{ $method }}</div>
                                </div>
                                @endforeach
                            </td>
                            <td class="mw-center">
                                <button class="mw-btn-update btn btn-success btn-icon"
                                data-model="{{ $posModel }}">
                                    <i class="fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- DATA -->
<div id="mw-data-permission" data-model="{{ json_encode($permissions) }}"></div>
<div id="mw-url-post" data-url="{{ \URL::route('admin.position.update.post') }}"></div>

