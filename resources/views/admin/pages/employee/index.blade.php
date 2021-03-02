<!-- Header -->
<div class="page-header" style="margin-bottom: 3%">
    <!-- Title -->
    <div class="col-sm-10 col-xs-12">
        <h1 class="mw-page-title page-title">
            {{ config('labels.menu.employee.'.helperLang()) }}
            <small>EMPLOYEE</small>
        </h1>
    </div>
    <div class="col-sm-2 col-xs-12">
        <a class="btn btn-success btn-block pull-right" href="{{ \URL::route('admin.employee.update.get', 0) }}">
            <i class="fa-plus"></i> สร้างใหม่
        </a>
    </div>
</div>
<!-- Panel -->
<div class="mw-page-content page-content">
     <!-- SEARCH PANEL -->
    <div class="panel">
        <div class="panel-body">
            <h4>
                <i class="icon fa-search"></i> ค้นหา
            </h4>
            <div class="row">
                <div class="col-sm-4 col-xs-12">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon btn">รหัสพนักงาน</span>
                            <input name="search_emp_key" class="form-control" type="text" placeholder="รหัสพนักงาน"
                            value="{{ isset($searchs['emp_key']) ? $searchs['emp_key'] : '' }}" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-12">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon btn">ชื่อ</span>
                            <input name="search_name" class="form-control" type="text" placeholder="ชื่อ, ชื่อเล่น, นามสกุล"
                            value="{{ isset($searchs['name']) ? $searchs['name'] : '' }}"/>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-12">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon btn">ตำแหน่ง</span>
                            <select id="mw-search-position" class="form-control">
                                <option value="0">ทั้งหมด</option>
                                @foreach ($posModels as $posModel)
                                <option value="{{ $posModel->id }}"
                                {{ (isset($searchs['pos_label']) && $posModel->label == $searchs['pos_label']) ? 'selected' : '' }}>
                                {{ $posModel->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div id="mw-btn-search" class="btn btn-primary" style="min-width: 120px">
                        {{ config('labels.btn.search.'.helperLang()) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- TABLE -->
    <div class="panel">
        <div class="panel-body container-fluid">
            <p>สิ่งที่ค้นหา : {{ implode('+', $searchs) }}</p>
            <p>พบข้อมูลจำนวนทั้งหมด : {{ $empModels->total() }}</p>
            <div class="table-responsive" style="">
                <table id="mw-table-order" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <!-- KEY -->
                            <th width="12%" class="mw-center">รหัสพนักงาน</th>
                            <!-- DETAIL -->
                            <th width="35%" class="mw-middle">ชื่อเล่น</th>
                            <!-- LAST UPDATED -->
                            <th width="12%" class="mw-center mw-middle">ตำแหน่ง</th>
                            <!-- MANAGE -->
                            <th width="12%" class="mw-center mw-middle">เบอร์โทรศัพท์</th>
                            <th width="12%" class="mw-center mw-middle">วันที่เริ่มงาน</th>
                            <th width="17%" class="mw-center mw-middle">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($empModels as $empModel): ?>
                        <tr class="mw-order-tr">
                            <td class="mw-center mw-middle">
                                {{ $empModel->emp_key }}
                            </td>
                            <td class="mw-middle">
                                {{ $empModel->nickname }}
                            </td>
                            <td class="mw-center mw-middle">
                                {{ $empModel->position->label }}
                            </td>
                            <td class="mw-center mw-middle">
                                {{ $empModel->phone }}
                            </td>
                            <td class="mw-center mw-middle">
                                {{ helperThaiFormat($empModel->start_date) }}
                            </td>
                            <td class="mw-center mw-middle">
                                <a class="btn btn-success btn-icon"
                                href="{{ \URL::route('admin.employee.update.get', $empModel->id) }}">
                                    <i class="fa-pencil"></i>
                                </a>
                                <div class="mw-btn-detail btn btn-primary btn-icon"
                                    data-model="{{ $empModel }}">
                                    <i class="fa-eye"></i>
                                </div>
                                <div class="mw-btn-remove btn btn-danger btn-icon"
                                    data-model="{{ $empModel }}"
                                    data-url="{{ \URL::route('admin.employee.remove.post', $empModel->id) }}">
                                    <i class="fa-trash"></i>
                                </div>

                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                {!! $empModels->links() !!}
            </div>
        </div>
    </div>
</div>

<div id="mw-url-employee-index" data-url="{{ \URL::route('admin.employee.index.get') }}"></div>
