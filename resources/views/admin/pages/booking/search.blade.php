<div class="panel">
    <div class="panel-body">
        <h4>
            <i class="icon fa-search"></i> ค้นหา
        </h4>
        <div class="row">
            <div class="col-sm-8 col-xs-12">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon btn">ตั้งแต่วันที่</span>
                        <input name="search_start_date" class="mw-input-search-date form-control mw-input-datepicker" type="text"
                        value="{{ isset($searchs['start_date']) ? $searchs['start_date'] : '' }}" />
                        <span class="input-group-addon btn">ถึง</span>
                        <input name="search_end_date" class="mw-input-search-date form-control mw-input-datepicker" type="text"
                        value="{{ isset($searchs['end_date']) ? $searchs['end_date'] : '' }}" />
                    </div>
                </div>
            </div>
             <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon btn">Booking Key</span>
                        <input name="search_booking_key" class="mw-input-search form-control" type="text" placeholder="Booking Key"
                        value="{{ isset($searchs['booking_key']) ? $searchs['booking_key'] : '' }}"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 col-xs-12">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon btn">ชื่อบริษัท</span>
                        <input name="search_ctm_name" class="mw-input-search form-control" type="text" placeholder="ชื่อบริษัท"
                        value="{{ isset($searchs['ctm_name']) ? $searchs['ctm_name'] : '' }}"/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon btn">สถานะ</span>
                        <select id="mw-search-status" class="mw-input-search form-control">
                            <option value="all" {{ ($searchs['status'] == 'all') ? 'selected' : '' }}>ทั้งหมด</option>
                            @foreach ($bookingStatuses as $key => $bookingStatus)
                            <option value="{{ $key }}" {{ ($searchs['status'] == $key) ? 'selected' : '' }}>
                                {{ $bookingStatus }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon btn">เปิดงานโดย</span>
                        <input name="search_created_by" class="mw-input-search form-control" type="text" placeholder="ชื่อผู้เปิดงาน"
                        value="{{ isset($searchs['created_by']) ? $searchs['created_by'] : '' }}"/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon btn">ผู้สั่งงานให้แมส</span>
                        <input name="search_cs_name" class="mw-input-search form-control" type="text" placeholder="ชื่อผู้สั่งงานให้แมส"
                        value="{{ isset($searchs['cs_name']) ? $searchs['cs_name'] : '' }}"/>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon btn">แมส</span>
                        <input name="search_msg_name" class="mw-input-search form-control" type="text" placeholder="ชื่อแมส"
                        value="{{ isset($searchs['msg_name']) ? $searchs['msg_name'] : '' }}"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div id="mw-btn-search" class="btn btn-primary" style="min-width: 120px">
                    {{ config('labels.btn.search.'.helperLang()) }}
                </div>
                <div class="mw-btn-clear-filter btn btn-default" style="min-width: 120px">
                    {{ config('labels.btn.clear.'.helperLang()) }}
                </div>
            </div>
        </div>
    </div>
</div>