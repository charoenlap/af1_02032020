<div id="mw-vue-contain"
	data-point="{{ $pointModels }}"
>
	<!-- Header -->
	<div class="page-header" style="margin-bottom: 3%">
	    <!-- Title -->
	    <div class="col-sm-8 col-xs-12">
	        <h1 class="mw-page-title page-title">
	            {{ config('labels.menu.point.'.helperLang()) }}
	            <small>CONSIGNEE LOCATION</small>
	        </h1>
	    	<!-- BREADCRUMB -->
	        <ol class="breadcrumb">
	            <li>
	                <a href="{{ \URL::route('admin.customer.index.get') }}">
	                    {{ config('labels.menu.customer.'.helperLang()) }}
	                </a>
	            </li>
	            <li class="active">
	                {{ config('labels.menu.point.'.helperLang()) }}
	            </li>
	        </ol>
	    </div>
	    <div class="col-sm-2 col-xs-4">
	        <a class="btn btn-default btn-outline btn-block pull-right"
	        href="{{ \URL::route('admin.customer.point_excel.get', $ctmModel->id) }}">
	            <i class="fa-file-excel-o" style="margin: 0 5px;"></i> Import Excel
	        </a>
	    </div>
	    <div class="col-sm-2 col-xs-4">
	    	<a class="btn btn-success btn-block pull-right"
	        href="{{ \URL::route('admin.customer.point_update.get', [$ctmModel->id, 0]) }}">
	            <i class="fa-plus" style="margin: 0 5px;"></i> สร้างใหม่
	        </a>
	    </div>
	</div>
	<!-- Panel -->
	<div class="mw-page-content page-content" style="margin-top: 4%;">
		<!-- TABLE -->
		<div class="panel">
			<div class="panel-body container-fluid">
				<h4>{{ $ctmModel->name }}</h4>
				<div class="table-responsive" style="">
				    <table id="mw-table-point" class="table table-striped table-bordered table-hover">
				        <thead>
				            <tr>
				            	<th width="12%" class="">ชื่อผู้รับ</th>
				                <th width="15%" class="">ชื่อผู้รับ</th>
				                <th width="25%" class="">ชื่อบริษัท</th>
				                <th width="35%" class="mw-middle">ที่อยู่</th>
				                <th width="12%" class="mw-center mw-middle">จัดการ</th>
				            </tr>
				        </thead>
				        <tbody>
				            <tr v-for="pointModel in pointModels">
				            	<td>@{{ pointModel.key }}</td>
				                <td class=""><small>@{{ pointModel.person }}</small></td>
				                <td class=""><small>@{{ pointModel.name }}</small></td>
				                <td class="">
				                	<small>@{{ pointModel.address+' '+pointModel.district
				                		+' '+pointModel.province+' '+pointModel.postcode }}
				                	</small>
				                </td>
				                <td class="mw-center mw-middle">
				                    <div class="mw-btn-detail btn btn-sm btn-primary btn-icon"
				                        @click="viewDetail(pointModel)">
				                        <i class="fa-eye"></i>
				                    </div>
				                    <a class="btn btn-sm btn-success btn-icon" v-bind:href="pointModel.update_url">
				                        <i class="fa-pencil"></i>
				                    </a>
				                    <div class="mw-btn-remove btn btn-sm btn-danger btn-icon"
				                    	@click="removeData(pointModel)">
				                        <i class="fa-trash"></i>
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