<!-- Header -->
<div class="page-header" style="margin-bottom: 3%">
    <!-- Title -->
    <div class="col-sm-10 col-xs-12">
        <h1 class="mw-page-title page-title">
            {{ config('labels.menu.job.'.helperLang()) }}
            <small>SHIPPING JOB</small>
        </h1>
    </div>
    <div class="col-sm-2 col-xs-12">
    </div>
</div>
<!-- Panel -->
<div class="mw-page-content page-content">
    <!-- SEARCH PANEL -->
    {!! view('admin.pages.job.search', compact('searchs', 'jobStatuses')) !!}
    <!-- TABLE -->
    <div class="panel">
        <div class="panel-body container-fluid">
            <div id="page-area" class="text-center">
                <ul class="pageing">
                    <li><a href="http://af1express.com/admin/job?&start_date={{ $start_date }}&end_date={{ $end_date }}&status={{ $status }}&page={{ $prev }}">ก่อนหน้า</a></li>
                    <li><input type="text" value="{{ $page }}" class="form-control" id="text-page"></li>
                    <li><a href="http://af1express.com/admin/job?&start_date={{ $start_date }}&end_date={{ $end_date }}&status={{ $status }}&page={{ $next }}">ต่อไป</a></li>
                </ul>
            </div>
            <div class="table-responsive">
                <table id="mw-table-order" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="8%" class="mw-center mw-middle"><small>สถานะ</small></th>
                            <th width="12%" class="mw-center"><small>Connote</small></th>
                            <th width="24%" class="mw-middle"><small>ชื่อผู้รับ</small></th>
                            <th width="14%" class="mw-middle"><small>CustomerRef.</small></th>
                            <th width="12%" class="mw-center mw-middle"><small>อัพเดทล่าสุด</small></th>
                            <th width="8%" class="mw-center mw-middle"><small>ผู้อนุมัติ</small></th>
                            <th width="8%" class="mw-center mw-middle"><small>แมส</small></th>
                            <th width="14%" class="mw-center mw-middle"><small>จัดการ</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($jobModels->count() <= 0)
                        <tr class="mw-tr">
                            <td colspan="8" class="mw-center">ไม่พบข้อมูล</td>
                        </tr>
                        @else
                        @foreach ($jobModels as $jobModel)
                        <tr class="mw-tr" id="{{ 'mw-model-'.$jobModel->id }}" data-model="{{ $jobModel }}">
                            <td class="mw-center mw-middle">
                                <label class="label" style="{{ 'background-color:'.$jobModel->status_color }}">
                                {{ $jobModel->status_label }}
                                </label>
                            </td>
                            <td class="mw-center mw-middle">
                                <small>{{ $jobModel->key }}</small>
                                {!! !empty($jobModel->connote->job_send) ? '<br/><small class="red-600">return</small>' : '' !!}
                            </td>
                            <td class="mw-middle">
                                <small>{{ !empty($jobModel->connote) ? $jobModel->connote->consignee_name.' @ '.$jobModel->connote->consignee_company : '' }}</small>
                            </td>
                            <td class="mw-middle"><small>{{ !empty($jobModel->connote) ? $jobModel->connote->customer_ref : '' }}</small></td>
                            <td class="mw-center mw-middle">
                                <small>{{ $jobModel->updated_label }}</small>
                            </td>
                            <td class="mw-center mw-middle">{{ $jobModel->sup_name }}</td>
                            <td class="mw-center mw-middle">
                                @if(!empty($jobModel->msg_name))
                                    <div class="mw-btn-choose-msg" style="cursor: pointer; text-decoration: underline;"
                                        data-id="{{ $jobModel->id }}">
                                        {{ $jobModel->msg_name }}
                                    </div>
                                @else
                                    <div class="mw-btn-choose-msg btn btn-info btn-outline"
                                        data-id="{{ $jobModel->id }}">
                                        <i class="fa-truck"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="mw-middle">
                                <div class="mw-btn-view-detail btn btn-primary btn-icon btn-sm"
                                data-id="{{ $jobModel->id }}">
                                    <i class="fa-eye"></i>
                                </div>
                                @if($jobModel->status == 'new')
                                <div class="mw-btn-approve btn btn-warning btn-icon btn-sm"
                                data-id="{{ $jobModel->id }}">
                                    <i class="fa-check"></i>
                                </div>
                                @endif
                                @if($jobModel->status == 'inprogress')
                                <div class="mw-btn-send btn btn-warning btn-icon btn-sm"
                                data-id="{{ $jobModel->id }}">
                                    <i class="fa-send-o"></i>
                                </div>
                                @endif
                                <a class="btn btn-success btn-icon btn-sm" target="_blank"
                                href="{{ \URL::route('home.gen_connote.index.get', $jobModel->key) }}">
                                    <i class="fa-print"></i>
                                </a>
                                @if($jobModel->status == 'complete')
                                    <div class="mw-btn-change-photo btn btn-warning btn-icon btn-sm"
                                        data-id="{{ $jobModel->id }}">
                                        <i class="fa-camera"></i>
                                    </div>
                                @endif
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

<div id="mw-url-job-index" data-url="{{ \URL::route('admin.job.index.get') }}"></div>
<div id="mw-url-choose-msg" data-url="{{ \URL::route('admin.job.update_msg.post') }}" ></div>
<div id="mw-url-change-photo" data-url="{{ \URL::route('admin.job.update_photo.post') }}" ></div>
<div id="mw-url-approve" data-url="{{ \URL::route('admin.job.approve.post') }}" ></div>
<div id="mw-url-send" data-url="{{ \URL::route('admin.job.send.post') }}" ></div>
<div id="mw-job-status" data-value="{{ json_encode($jobStatuses) }}"></div>
<div id="mw-data-msg" data-model="{{ $msgModels }}"></div>

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
    $(function(e){
        // alert(1);
        $('#text-page').blur(function(e){
            window.location = 'http://af1express.com/admin/job?page='+$(this).val();
        });
    });
</script>