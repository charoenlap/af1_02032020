<section class="page-section" style="padding-top: 0px; margin-top: -40px;">
    <div class="col-md-10 col-md-offset-1 col-sm-12 col-xs-12 mw-pd-lr-10 mw-bg-white mw-mg-tp-20" style="padding: 40px">
        <!-- HEADER -->
        <div class="mw-pd-tb-10 mw-bg-white">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="mw-center">
                        ประวัติการใช้บริการ <small>[HISTORY]</small>
                        <img width="150" src="{{ urlHomeImage().'/fight.png' }}" style="margin-left: 20px">
                    </h3>
                </div>
            </div>
        </div>
        <!-- BODY -->
        <div class="page-body mw-bg-white" style="margin-top: 10px">
        	<table class="table table-striped table-bordered table-hover">
        		<thead>
        			<tr>
        				<th width="10%" class="mw-center">Booking Key</th>
        				<th width="10%" class="mw-center">ประเภท</th>
        				<th width="10%" class="mw-center">สถานะ</th>
        				<th width="20%" class="mw-center">วันที่รับของ</th>
        				<th width="50%">รายละเอียด</th>
        			</tr>
        		</thead>
        		<tbody>
        			@foreach($bookingModels as $bookingModel)
        			<tr>
        				<td class="mw-center mw-middle">{{ $bookingModel->key }}</td>
        				<td class="mw-center mw-middle">{{ $bookingModel->cod_label }}</td>
        				<td class="mw-center mw-middle">
        					<label class="label" style="{{ 'background-color:'.$bookingModel->status_color }}">
                            {{ $bookingModel->status_label }}
                            </label>
        				</td>
        				<td class="mw-center mw-middle">{{ $bookingModel->get_datetime_label }}</td>
        				<td class="mw-middle" style="font-size: 0.8em">
        					<p>{{ $bookingModel->person_name }}</p>
        					<p>{{ $bookingModel->address }}</p>
        					<p>{{ $bookingModel->customer_name }}</p>
        				</td>
        			</tr>
        			@endforeach
        		</tbody>
        	</table>
        </div>
   	</div>
</section>