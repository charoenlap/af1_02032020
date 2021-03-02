<!-- Header -->
<div class="page-header" style="margin-bottom: 3%">
    <!-- Title -->
    <div class="col-sm-10 col-xs-12">
        {{ $ctmModel->name }}
        <h1 class="mw-page-title page-title">
            {{ config('labels.menu.point.'.helperLang()) }} : UPDATE
        </h1>
        <!-- BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <a href="{{ \URL::route('admin.customer.index.get') }}">
                    {{ config('labels.menu.customer.'.helperLang()) }}
                </a>
            </li>
            <li>
                <a href="{{ \URL::route('admin.customer.point.get', [$ctmModel->id, $pointModel->id]) }}">
                    {{ config('labels.menu.point.'.helperLang()) }}
                </a>
            </li>
            <li class="active">
                Update
            </li>
        </ol>
    </div>
</div>
<!-- Panel -->
<div id="mw-model-customer-point" class="mw-page-content page-content" style="margin-top: 7%;"
    data-model="{{ $pointModel }}"
    data-province="{{ json_encode($provinces) }}">
    <div class="panel">
        <div class="panel-body container-fluid">
            <div class="row">
                <div class="col-sm-8 col-xs-12">
                    <div class="form-group">
                        <span class="control-label">{{ 'ชื่อผู้รับ' }}</span>
                        <input name="person" type="text" class="form-control" value="{{ $pointModel->person }}"/>
                        <div class="mw-text-error" id="form-error-person"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'ชื่อบริษัท' }}</span>
                        <input name="name" type="text" class="form-control" value="{{ $pointModel->name }}"/>
                        <div class="mw-text-error" id="form-error-name"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'ที่อยู่' }}</span>
                        <textarea name="address" type="text" class="form-control" value=""> {{ $pointModel->address }} </textarea>
                        <div class="mw-text-error" id="form-error-address"></div>
                        </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'อำเภอ / เขต' }}</span>
                        <input name="district" type="text" class="form-control" value="{{ $pointModel->district }}" v-model="pointModel.district"/>
                        <div class="mw-text-error" id="form-error-district"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'จังหวัด' }}</span>
                        <select class="form-control" id="mw-select-province">
                            @foreach ($provinces as $key => $province)
                        	<option value="{{ $province }}" {{ ($province == $pointModel->province) ? 'selected' : '' }} >
                                {{ $province }}
                            </option>;
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'รหัสไปรษณีย์' }}</span>
                        <input name="postcode" type="text" class="form-control" value="{{ $pointModel->postcode }}"/>
                        <div class="mw-text-error" id="form-error-postcode"></div>
                    </div>
                    <div class="form-group">
                        <span class="control-label">{{ 'เบอร์ติดต่อ' }}</span>
                        <input name="mobile" type="text" class="form-control" value="{{ $pointModel->mobile }}"/>
                        <div class="mw-text-error" id="form-error-mobile"></div>
                    </div>
                    <div class="form-group">
                        <a href="{{ \URL::route('admin.customer.point.get', [$ctmModel->id, $pointModel->id]) }}"
                        class="btn btn-default" style="min-width: 100px">
                            ย้อนกลับ
                        </a>
                        <button class="btn btn-success" style="min-width: 100px" id="mw-btn-save">
                            <i class="fa-save"></i> บันทึก
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DATA -->
<div id="mw-url-point-update" data-url="{{ \URL::route('admin.customer.point_update.post', [$ctmModel->id, $pointModel->id]) }}"></div>