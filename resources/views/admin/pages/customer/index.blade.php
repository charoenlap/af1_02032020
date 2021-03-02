<!-- Header -->
<div class="page-header" style="margin-bottom: 3%">
    <!-- Title -->
    <div class="col-sm-10 col-xs-12">
        <h1 class="mw-page-title page-title">
            {{ config('labels.menu.customer.'.helperLang()) }}
            <small>CUSTOMER</small>
        </h1>
    </div>
    <div class="col-sm-2 col-xs-12">
        <a class="btn btn-success btn-block pull-right" href="{{ \URL::route('admin.customer.update.get', 0) }}">
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
                            <span class="input-group-addon btn">รหัสลูกค้า</span>
                            <input name="search_cus_key" class="form-control" type="text" placeholder="รหัสลูกค้า"
                            value="{{ isset($searchs['key']) ? $searchs['key'] : '' }}" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-5 col-xs-12">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon btn">ชื่อ</span>
                            <input name="search_name" class="form-control" type="text" placeholder="ชื่อลูกค้า"
                            value="{{ isset($searchs['name']) ? $searchs['name'] : '' }}"/>
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
            <p>พบข้อมูลจำนวนทั้งหมด : {{ $ctmModels->total() }}</p>
            <div class="table-responsive" style="">
                <table id="mw-table-customer" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <!-- KEY -->
                            <th width="20%" class="mw-center">รหัสลูกค้า</th>
                            <!-- DETAIL -->
                            <th width="35%" class="mw-middle">ชื่อลูกค้า</th>
                            <!-- LAST UPDATED -->
                            <th width="23%" class="mw-center mw-middle hidden-sm">จังหวัด</th>
                            <th width="22%" class="mw-center mw-middle">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ctmModels as $ctmModel): ?>
                        <tr class="mw-order-tr">
                            <td class="mw-center mw-middle">{{ $ctmModel->key }}</td>
                            <td class="mw-middle">{{ $ctmModel->name }}</td>
                            <td class="mw-center mw-middle hidden-sm">{{ $ctmModel->province }}</td>
                            <td class="mw-center mw-middle">
                                <a class="btn btn-success btn-icon"
                                href="{{ \URL::route('admin.customer.update.get', $ctmModel->id) }}">
                                    <i class="fa-pencil"></i>
                                </a>
                                <div class="mw-btn-detail btn btn-primary btn-icon"
                                    data-model="{{ $ctmModel }}">
                                    <i class="fa-eye"></i>
                                </div>
                                <a class="btn btn-default btn-icon"
                                href="{{ \URL::route('admin.customer.point.get', $ctmModel->id) }}">
                                    <i class="fa-truck"></i>
                                </a>
                                <div class="mw-btn-remove btn btn-danger btn-icon"
                                    data-model="{{ $ctmModel }}"
                                    data-url="{{ \URL::route('admin.customer.remove.post', $ctmModel->id) }}">
                                    <i class="fa-trash"></i>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                {!! $ctmModels->links() !!}
            </div>
        </div>
    </div>
</div>
<!-- DATA -->
<div id="mw-url-customer-index" data-url="{{ \URL::route('admin.customer.index.get') }}"></div>
