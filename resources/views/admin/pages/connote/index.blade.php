<!-- Header -->
<div class="page-header" style="margin-bottom: 3%">
    <!-- Title -->
    <div class="col-sm-10 col-xs-12">
        <h1 class="mw-page-title page-title">
            {{ config('labels.menu.connote.'.helperLang()) }}
            <small>CONNOTE</small>
        </h1>
    </div>
    <div class="col-sm-2 col-xs-12">
    </div>
</div>
<!-- Panel -->
<div class="mw-page-content page-content">
    <!-- SEARCH PANEL -->
    {!! view('admin.pages.connote.search', compact('searchs')) !!}
    <!-- TABLE -->
    <div class="panel">
        <div class="panel-body container-fluid">
            <div class="text-right">จำนวนทั้งหมด <span>{{ $count_total_row }}</span></div>
            <div id="page-area" class="text-center">

                <ul class="pageing">
                    <li><a href="http://af1express.com/admin/connote?&start_date={{ $start_date }}&end_date={{ $end_date }}&page={{ $prev }}">ก่อนหน้า</a></li>
                    <li><input type="text" value="{{ $page }}" class="form-control" id="text-page"></li>
                    <li><a href="http://af1express.com/admin/connote?&start_date={{ $start_date }}&end_date={{ $end_date }}&page={{ $next }}">ต่อไป</a></li>
                </ul>
            </div>
            <div class="table-responsive">
                <table id="mw-table-order" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="8%" class="mw-center"><small>Connote</small></th>
                            <th width="18%" class=""><small>ผู้ส่ง</small></th>
                            <th width="18%" class=""><small>ผู้รับ</small></th>
                            <th width="10%" class="mw-center"><small>ประเภท</small></th>
                            <th width="12%" class="mw-center"><small>งานด่วน</small></th>
                            <th width="10%" class="mw-center"><small>COD</small></th>
                            <th width="10%" class="mw-center"><small>CustomerRef.</small></th>
                            <th width="14%" class="mw-center"><small>Manage</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($connoteModels->count() <= 0)
                        <tr class="mw-tr">
                            <td colspan="8" class="mw-center">ไม่พบข้อมูล</td>
                        </tr>
                        @else
                        @foreach ($connoteModels as $connoteModel)
                        <tr class="mw-tr">
                            <td class="mw-center mw-middle">
                                <small>{{ $connoteModel->key }}</small>
                                @if ($connoteModel->status == 'cancel')
                                <small><small class="red-600"><i class="fa-close"></i> ยกเลิก</small></small>
                                @endif
                            </td>
                            <td class="mw-middle">
                                <small>{{ $connoteModel->shipper_name }}</small>
                            </td>
                            <td class="mw-middle">
                                <small>{{ $connoteModel->consignee_name }}</small>
                            </td>
                            <td class="mw-center mw-middle">
                            	<small>{{ $connoteModel->service_label }}</small>
                            </td>
                            <td class="mw-center mw-middle">
                            	<small>{{ $connoteModel->express_label }}</small>
                            </td>
                            <td class="mw-center mw-middle">
                            	<small>{{ $connoteModel->cod_value }}</small>
                            </td>
                            <td class="mw-middle">
                                <small>{{ $connoteModel->customer_ref }}</small>
                            </td>
                            <td class="mw-middle">
                                <div class="mw-btn-view-detail btn btn-primary btn-icon btn-sm"
                                    data-model="{{ $connoteModel }}">
                                    <i class="fa-eye"></i>
                                </div>
                                <a class="btn btn-success btn-icon btn-sm" target="_blank"
                                href="{{ \URL::route('home.gen_connote.index.get', $connoteModel->key) }}">
                                    <i class="fa-print"></i>
                                </a>
                                <a class="btn btn-warning btn-icon btn-sm"
                                href="{{ \URL::route('admin.connote.update.get', $connoteModel->id) }}">
                                    <i class="fa-pencil"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>
<!-- DATA -->
<div id="mw-url-connote-index" data-url="{{ \URL::route('admin.connote.index.get') }}"></div>

<style>
    .pageing {
        list-style: none;
    }
    .pageing li {
        display:inline-block;
    }
    #text-page {
        width:60px;
        text-align: center;
    }
</style>
<script>
    // $(function(e){
    //     // alert(1);
    //     $('#text-page').blur(function(e){
    //         window.location = 'http://af1express.com/admin/connote?page='+$(this).val();
    //     });
    // });
</script>