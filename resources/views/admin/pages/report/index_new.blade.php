<!-- Header -->
<div class="page-header" style="margin-bottom: 3%">
    <!-- Title -->
    <div class="col-xs-12">
        <h1 class="mw-page-title page-title">
			Connote Report
        </h1>
    </div>
</div>
<!-- Panel -->
<div id="mw-page-report" class="mw-page-content page-content">
	<div class="panel">
        <!-- DURATION -->
        <div class="panel-body" style="">
        	<div class="row">
        		<div class="col-sm-12 col-xs-12">
	            	<h3 class="control-label">Filters <small>กรองข้อมูล</small></h3>
	        	</div>
	        </div>
	        <div class="row">
	        	<div class="col-sm-4 col-xs-12">
	                <div class="form-group">
	                    <h5 class="control-label">Status <small>สถานะ</small></h5>
	                    <select id="status_chosen" class="form-control">
	                    	<option value="all">ALL</option>
	                    	<option value="pending" <?php echo ($status_chosen=='pending'?'selected':'');?>>
	                    		ยังไม่เปิดงานส่ง
	                    	</option>
	                    	<option value="new" <?php echo ($status_chosen=='new'?'selected':'');?>>
	                    		รออนุมัติ
	                    	</option>
	                    	<option value="inprogress" <?php echo ($status_chosen=='inprogress'?'selected':'');?>>
	                    		กำลังไปส่ง
	                    	</option>
	                    	<option value="complete" <?php echo ($status_chosen=='complete'?'selected':'');?>>
	                    		ส่งเรียบร้อย
	                    	</option>
	                    	<option value="fail" <?php echo ($status_chosen=='fail'?'selected':'');?>>
	                    		ส่งไม่สำเร็จ
	                    	</option>
	                    	<option value="cancel" <?php echo ($status_chosen=='cancel'?'selected':'');?>>
	                    		ยกเลิกการส่ง
	                    	</option>
	                    </select>
	                </div>
	        	</div>
	        	<div class="col-sm-8 col-xs-12">
	                <div class="form-group">
	                    <h5 class="control-label">Shipper Company <small>บริษัทผู้ส่ง</small></h5>
	                    <select id="customers" class="form-control">
	                    	<option value="all">ALL</option>
	                    	@foreach ($customer_array as $data)
							<option value="{{ $data['id'] }}" {{ $data['selected'] }}>{{ $data['name'] }}</option>
	                    	@endforeach
	                    </select>
	                </div>
	        	</div>
	            <div class="col-sm-4 col-xs-12">
	                <div class="form-group">
	                    <h5 class="control-label">Booking Start Time <small>เวลาที่เริ่มเปิดงาน Booking</small></h5>
	                    <div class="input-group">
	                        <input type="text" class="mw-center mw-input-datepicker mw-date-bkg-start form-control"
	                        name="bkg_start"  id="book_date_start" value="<?php echo (isset($_GET['book_date_start'])?$_GET['book_date_start']:date('d/m/Y')); ?>">
	                        <span class="input-group-addon mw-bg-white">TO</span>
	                        <input type="text" class="mw-center mw-input-datepicker mw-date-bkg-end form-control"
	                        name="bkg_end" id="book_date_end" value="<?php echo (isset($_GET['book_date_end'])?$_GET['book_date_end']:date('d/m/Y')); ?>">
	                    </div>
	                </div>
	        	</div>
	        	<div class="col-sm-4 col-xs-12">
	                <div class="form-group">
	                    <h5 class="control-label">Messenger Start Time <small>เวลาที่แมสเริ่มไปรับของ</small></h5>
	                    <div class="input-group">
	                        <input type="text" class="mw-center mw-input-datepicker mw-date-msg-start form-control"
	                        name="msg_start"  id="msg_date_start" value="<?php echo (isset($_GET['msg_date_start'])?$_GET['msg_date_start']:''); ?>">
	                        <span class="input-group-addon mw-bg-white">TO</span>
	                        <input type="text" class="mw-center mw-input-datepicker mw-date-msg-end form-control"
	                        name="msg_end"  id="msg_date_end" value="<?php echo (isset($_GET['msg_date_end'])?$_GET['msg_date_end']:''); ?>">
	                    </div>
	                </div>
	        	</div>
	        	<div class="col-sm-4 col-xs-12">
	                <div class="form-group">
	                    <h5 class="control-label">Job Received Time <small>เวลาที่ส่งสำเร็จ</small></h5>
	                    <div class="input-group">
	                        <input type="text" class="mw-center mw-input-datepicker mw-date-job-start form-control"
	                        name="job_start" id="job_date_start" value="<?php echo (isset($_GET['job_date_start'])?$_GET['job_date_start']:''); ?>">
	                        <span class="input-group-addon mw-bg-white">TO</span>
	                        <input type="text" class="mw-center mw-input-datepicker mw-date-job-end form-control"
	                        name="job_end" id="job_date_end" value="<?php echo (isset($_GET['job_date_end'])?$_GET['job_date_end']:''); ?>">
	                    </div>
	                </div>
	        	</div>
	        </div>
	        <div class="row">
		    	<div class="col-xs-12">
		         	<div class="btn btn-primary" style="min-width: 150px;" id="btn-filter">
		         		Submit
		         	</div>
		        </div>
	        </div>
	    </div>
	    <hr />
	    <!-- TABLE -->
	    <div class="panel-body" style="padding-top: 0">
		    <div class="row">
		    	<!-- GENERATE EXCEL BUTTON -->
		    	<div class="col-sm-12">
	    			<h3 class="pull-left">Connote Table <small>ตารางแสดงใบนำส่ง</small></h3>
		    		<!-- <div class="pull-right">
		        		<div class="btn btn-warning btn-outline btn-block" @click="genExcel()">
		        			<i class="fa-file-excel-o" style="margin-right: 5px"></i> Export to Excel
		        		</div>
		        	</div> -->
	        	</div>
	        </div>
	        <div class="row" style="padding: 10px 0">
	        	<div class="col-sm-12">
			    	<div class="table-responsive">
			    		<table id="mw-table-order" class=" table-hover table-bordered" style="font-size: 0.9em">
				    		<thead>
				    			<th>ลำดับ</th>
								<th>Booking No</th>
								<th>เวลาที่เริ่ม Booking</th>
								<th>Company ID</th>
								<th width="300px">บริษัทผู้ส่ง</th>
								<th>เขต อำเภอ</th>
								<th>จังหวัด</th>
								<th>รหัสไปรษณีย์</th>
								<th>ชื่อผู้ส่ง</th>
								<th>ที่อยู่ผู้ส่ง</th>
								<th>เบอร์ติดต่อ </th>
								<th>Service Level</th>
								<th>SERVICE TYPE</th>
								<th>SIZE & WEIGHT</th>
								<th>เวลาที่ Pickup</th>
								<th>หมายเหตุ</th>
								<th>Customer REF</th>
								<th>เวลาเริ่มส่ง</th>
								<th>ชื่อผู้รับ</th>
								<th>เบอร์ติดต่อ</th>
								<th>บริษัทผู้รับ</th>
								<th>ที่อยู่ผู้รับ</th>
								<th>เขต อำเภอ</th>
								<th>จังหวัด</th>
								<th>รหัสไปรษณีย์</th>
								<th>เลข Connote</th>
								<th>สถานะ</th>
								<th>ชื่อผู้เซ็นรับของ</th>
								<th>เวลาที่ส่งสินค้า</th>
								<th>เวลาที่ Return Invoice</th>
								<th>หมายเหตุ</th>
							</thead>
				    		<tbody>
				    			@foreach ($result_report_data as $data)
								<tr>
									<td>
				    					{{ $data['no'] }}
				    				</td>
				    				<td>
				    					{{ $data['booking_no'] }}
				    				</td>
				    				<td>
				    					{{ $data['date_start'] }}
				    				</td>
				    				<td>{{ $data['company_key'] }}</td>
									<td>{{ $data['company_sender'] }}</td>
									<td>{{ $data['company_district'] }}</td>
									<td>{{ $data['company_province'] }}</td>
									<td>{{ $data['company_postcode'] }}</td>
									<td>{{ $data['company_person'] }}</td>
									<td>{{ $data['company_address'] }}</td>
									<td>{{ $data['company_phone'] }}</td>
									<td>{{ $data['service_level'] }}</td>
									<td>{{ $data['service_type'] }}</td>
									<td>{{ $data['size_box'] }}</td>
									<td>{{ $data['time_pickup'] }}</td>
									<td>{{ $data['service_comment'] }}</td>
									<td>{{ $data['customer_ref'] }}</td>
									<td>{{ $data['time_send'] }}</td>
									<td>{{ $data['consignee_name'] }}</td>
									<td>{{ $data['consignee_phone'] }}</td>
									<td>{{ $data['consignee_company'] }}</td>
									<td>{{ $data['consignee_address'] }}</td>
									<td>{{ $data['consignee_districe'] }}</td>
									<td>{{ $data['consignee_province'] }}</td>
									<td>{{ $data['consignee_postcode'] }}</td>
									<td>{{ $data['connote_key'] }}</td>
									<td>{{ $data['connotes_status'] }}</td>
									<td>{{ $data['sign_name'] }} {{ $data['comment'] }}</td>
									<td>{{ $data['time_send'] }}</td>
									<td>{{ $data['time_return'] }}</td>
									<td>{{ $data['return_comment'] }}</td>
				    			</tr>
				    			@endforeach
				    		</tbody>
				    	</table>
			    	</div>
		        </div>
		    </div>
	    </div>
	</div>
</div>
<?php /* ?>
<div id="mw-page-report" class="mw-page-content page-content"
	data-bkg-start="{{ $bkg_start }}"
	data-bkg-end="{{ $bkg_end }}"
	data-msg-start="{{ $msg_start }}"
	data-msg-end="{{ $msg_end }}"
	data-job-start="{{ $job_start }}"
	data-job-end="{{ $job_end }}"
	data-step="{{ $step }}"
	data-fields="{{ json_encode($fields) }}"
	data-report-data="{{ json_encode($report_data) }}"
	data-chart-data="{{ json_encode($chart_data) }}"
	data-selected-field="{{ json_encode($selected_field) }}"
	data-status-all="{{ json_encode($statusAll) }}"
	data-status-chosen="{{ $status }}"
	data-customer-all="{{ json_encode($ctmAll) }}"
	data-customer-chosen="{{ $ctm_chosen }}">
    <div class="panel">
        <!-- DURATION -->
        <div class="panel-body" style="">
        	<div class="row">
        		<div class="col-sm-12 col-xs-12">
	            	<h3 class="control-label">Filters <small>กรองข้อมูล</small></h3>
	        	</div>
	        </div>
	        <div class="row">
	        	<div class="col-sm-4 col-xs-12">
	                <div class="form-group">
	                    <h5 class="control-label">Status <small>สถานะ</small></h5>
	                    <select v-model="status_chosen" class="form-control">
	                    	<option value="all">ALL</option>
	                    	<option v-for="(label, status) in statusAll" v-bind:value="status">
	                    		@{{ label }}
	                    	</option>
	                    </select>
	                </div>
	        	</div>
	        	<div class="col-sm-8 col-xs-12">
	                <div class="form-group">
	                    <h5 class="control-label">Shipper Company <small>บริษัทผู้ส่ง</small></h5>
	                    <select v-model="ctm_chosen" class="form-control">
	                    	<option value="all">ALL</option>
	                    	<option v-for="customer in ctmAll" v-bind:value="customer.id">
	                    		@{{ customer.name+' ('+customer.key+')' }}
	                    	</option>
	                    </select>
	                </div>
	        	</div>
	            <div class="col-sm-4 col-xs-12">
	                <div class="form-group">
	                    <h5 class="control-label">Booking Start Time <small>เวลาที่เริ่มเปิดงาน Booking</small></h5>
	                    <div class="input-group">
	                        <input type="text" class="mw-center mw-input-datepicker mw-date-bkg-start form-control"
	                        name="bkg_start" v-model="bkg_start">
	                        <span class="input-group-addon mw-bg-white">TO</span>
	                        <input type="text" class="mw-center mw-input-datepicker mw-date-bkg-end form-control"
	                        name="bkg_end" v-model="bkg_end">
	                    </div>
	                </div>
	        	</div>
	        	<div class="col-sm-4 col-xs-12">
	                <div class="form-group">
	                    <h5 class="control-label">Messenger Start Time <small>เวลาที่แมสเริ่มไปรับของ</small></h5>
	                    <div class="input-group">
	                        <input type="text" class="mw-center mw-input-datepicker mw-date-msg-start form-control"
	                        name="msg_start" v-model="msg_start">
	                        <span class="input-group-addon mw-bg-white">TO</span>
	                        <input type="text" class="mw-center mw-input-datepicker mw-date-msg-end form-control"
	                        name="msg_end" v-model="msg_end">
	                    </div>
	                </div>
	        	</div>
	        	<div class="col-sm-4 col-xs-12">
	                <div class="form-group">
	                    <h5 class="control-label">Job Received Time <small>เวลาที่ส่งสำเร็จ</small></h5>
	                    <div class="input-group">
	                        <input type="text" class="mw-center mw-input-datepicker mw-date-job-start form-control"
	                        name="job_start" v-model="job_start" :disabled="(status_chosen != 'complete')"
	                        v-bind:class="(status_chosen != 'complete') ? 'mw-color-disabled' : '' ">
	                        <span class="input-group-addon mw-bg-white">TO</span>
	                        <input type="text" class="mw-center mw-input-datepicker mw-date-job-end form-control"
	                        name="job_end" v-model="job_end" :disabled="(status_chosen != 'complete')"
	                        v-bind:class="(status_chosen != 'complete') ? 'mw-color-disabled' : '' ">
	                    </div>
	                </div>
	        	</div>
	        </div>
	        <div class="row">
		    	<div class="col-xs-12">
		         	<div class="btn btn-primary" style="min-width: 150px;" @click="genData()">
		         		Submit
		         	</div>
		        </div>
	        </div>
	    </div>
	    <hr />
        <!-- GRAPH -->
        <div class="panel-body" style="padding-top: 0">
	        <div class="row" style="margin-top: 30px">
	            <div class="col-sm-6 col-xs-12">
	            	<h3 class="control-label">Connote Chart <small>กราฟแสดงจำนวนใบนำส่ง</small></h3>
	        	</div>
	            <div class="col-sm-6 col-xs-12">
	            	<h5 class="control-label">Period [ความละเอียด]</h5>
		        	<div class="col-xs-4">
		                <input type="radio" class="mw-labelauty-radio" name="mw_step"
		                data-labelauty="Hour" value="h" v-model="step"/>
		            </div>
		            <div class="col-xs-4">
		               	<input type="radio" class="mw-labelauty-radio" name="mw_step"
		               	data-labelauty="Day" value="d" v-model="step"/>
		            </div>
		            <div class="col-xs-4">
		               	<input type="radio" class="mw-labelauty-radio" name="mw_step"
		               	data-labelauty="Week" value="w" v-model="step"/>
		            </div>
		        </div>
		   	</div>
		   	<div class="row">
		    	<div class="col-xs-12">
		         	<div class="btn btn-primary" style="min-width: 150px;" @click="genData()">
		         		Submit
		         	</div>
		        </div>
	        </div>
	        <div class="row" style="margin-top: 30px">
	            <div class="col-sm-12 col-xs-12">
	            	<div id="mw-chart-order">
	        			<div class="ct-chart line-chart height-200"></div>
	        		</div>
	        	</div>
	        </div>
	   	</div>

	   	 <!-- ADJUST REPORT -->
    	<div class="panel-body mw-pd-lr-10">
          	<div class="panel-heading" id="mw-accordian" role="tab">
            	<a class="collapsed btn mw-right" style="display: block;"
            	data-parent="#exampleAccordion" data-toggle="collapse" href="#mw-accordian-report"
            	aria-controls="mw-accordian-report" aria-expanded="false">
            		<i class="fa-cogs"></i> {{ config('labels.report.adjust_report.'.helperLang()) }}
          		</a>
          	</div>
          	<div class="panel-collapse collapse" id="mw-accordian-report" aria-labelledby="mw-accordian"
          		role="tabpanel" aria-expanded="false" style="height: 0px;">
            	<div class="panel-body" style="padding-top: 0px">
              		<div style="border: 1px solid #e4eaec; padding: 20px; border-radius: 20px;">
			        	<div class="row mw-mg-0">
			        		<div class="form-group">
			                    <h5 class="control-label">เรียงลำดับหลักคอลัมน์ในรายงาน: </h5>
			                    <p v-for="field in selected_fields" class="inline">"@{{ fields[field] }}", </p>
			                </div>
			                <!-- ALL FIELDS -->
			                <div class="row" style="margin-bottom: 20px">
				                <div class="form-group">
				                    <div class="col-sm-12">
				                    	<div class="btn btn-default btn-outline btn-xs pull-right" @click="adjustReset()">
					                		<i class="fa-refresh"></i> กลับไปค่าเดิม
					                	</div>
					                	<div class="btn btn-default btn-outline btn-xs pull-right mw-mg-lr-10" @click="adjustClearAll()">
					                		<i class="fa-genderless"></i> ล้างทั้งหมด
					                	</div>
					                	<div class="btn btn-default btn-outline btn-xs pull-right mw-mg-lr-10" @click="adjustSelectAll()">
					                		<i class="fa-check"></i> เลือกทั้งหมด
					                	</div>
				                    </div>
				                </div>
			                </div>
			        		<!-- CHOOSE FIELDS -->
			                <div class="form-group">
			                    <div class="col-sm-4 col-xs-2" v-for="(label, field) in fields">
				                    <div class="checkbox-custom checkbox-info">
					                	<input type="checkbox" v-bind:value="field" v-model="selected_fields">
					                  	<label for="inputChecked">@{{ label }}</label>
					                </div>
			                    </div>
			                </div>
			        	</div>
			        	<!-- CONFIRM BUTTON -->
			        	<div class="row" style="margin-top: 30px">
				        	<div class="col-sm-4 col-xs-12">
				        		<div class="form-group">
				            		<div id="mw-btn-confirm" class="btn btn-info btn-block" @click="saveAdjustReport()">
				            			<i class="fa-save" style="margin-right: 5px"></i> SAVE
				            		</div>
				            	</div>
				            </div>
			        	</div>
		        	</div>
		    	</div>
          	</div>
        </div>
        <hr />
	    <!-- TABLE -->
	    <div class="panel-body" style="padding-top: 0">
		    <div class="row">
		    	<!-- GENERATE EXCEL BUTTON -->
		    	<div class="col-sm-12">
	    			<h3 class="pull-left">Connote Table <small>ตารางแสดงใบนำส่ง</small></h3>
		    		<div class="pull-right">
		        		<div class="btn btn-warning btn-outline btn-block" @click="genExcel()">
		        			<i class="fa-file-excel-o" style="margin-right: 5px"></i> Export to Excel
		        		</div>
		        	</div>
	        	</div>
	        </div>
	        <div class="row" style="padding: 10px 0">
	        	<div class="col-sm-12">

				    	<table id="mw-table-order" class="table table-hover table-bordered" style="font-size: 0.9em">
				    		<thead>
				    			<tr>
				    				<th v-for="field in selected_fields" class="mw-center mw-td-fix"
				    				style="">
				    					@{{ fields[field] }}
				    				</th>
				    			</tr>
				    		</thead>
				    		<tbody>
				    			<tr v-for="data in reportData">
				    				<td v-for="field in selected_fields" class="mw-center">
				    					@{{ data[field] }}
				    				</td>
				    			</tr>
				    		</tbody>
				    	</table>

		        </div>
		    </div>
	    </div>
	</div>

</div>

<!-- DATA -->
<a id="mw-report-file" href="#" download=""></a>
<div id="mw-url-get-data" data-url="{{ \URL::route('admin.report.get_data.post') }}"></div>
<div id="mw-url-adjust-report" data-url="{{ \URL::route('admin.report.adjust_report.post') }}"></div>
<div id="mw-url-gen-report" data-url="{{ \URL::route('admin.report.gen_excel.post') }}"></div>
<?php */?>

<input type="hidden" value="<?php echo $mdir; ?>" name="url" id="url">
<input type="hidden" value="<?php echo $customers; ?>" name="customers" id="customers_id">
<script>
	

</script>