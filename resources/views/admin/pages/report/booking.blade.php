<!-- Header -->
<div class="page-header" style="margin-bottom: 3%">
    <!-- Title -->
    <div class="col-xs-12">
        <h1 class="mw-page-title page-title">
			Booking Report
        </h1>
    </div>
</div>
<!-- Panel -->
<div id="mw-page-report" class="mw-page-content page-content"
	data-start-date="{{ $start_date }}"
	data-end-date="{{ $end_date }}"
	data-step="{{ $step }}"
	data-fields="{{ json_encode($fields) }}"
	data-report-data="{{ json_encode($report_data) }}"
	data-chart-data="{{ json_encode($chart_data) }}"
	data-selected-field="{{ json_encode($selected_field) }}"
	data-customers="{{ $customerModels }}"
	data-customer-chosen="{{ $customer_id }}"
	data-status-all="{{ json_encode($statusAll) }}"
	data-status-chosen="{{ $status }}">
    <div class="panel">
        <!-- DURATION -->
        <div class="panel-body" style="">
        	<div class="row">
	            <div class="col-md-6 col-sm-12 col-xs-12">
	                <div class="form-group">
	                    <h5 class="control-label">Customer [ลูกค้า]</h5>
	                    <select v-model="customer_id" class="form-control">
	                    	<option value="0">ALL</option>
	                    	<option v-for="customerModel in customerModels" v-bind:value="customerModel.id">
	                    		@{{ customerModel.key+' '+customerModel.name }}
	                    	</option>
	                    </select>
	                </div>
	        	</div>
	        	<div class="col-md-6 col-sm-12 col-xs-12">
	                <div class="form-group">
	                    <h5 class="control-label">Status [สถานะ]</h5>
	                    <select v-model="status_chosen" class="form-control">
	                    	<option value="all">ALL</option>
	                    	<option v-for="(label, status) in statusAll" v-bind:value="status">
	                    		@{{ label }}
	                    	</option>
	                    </select>
	                </div>
	        	</div>
	        </div>
	        <div class="row">
	            <div class="col-md-6 col-sm-12 col-xs-12">
	                <div class="form-group">
	                    <h5 class="control-label">Duration [ช่วงเวลา]</h5>
	                    <div class="input-group">
	                        <input type="text" class="mw-center mw-input-datepicker mw-date-start form-control"
	                        name="start_date" v-model="start_date">
	                        <span class="input-group-addon mw-bg-white">TO</span>
	                        <input type="text" class="mw-center mw-input-datepicker mw-date-end form-control"
	                        name="end_date" v-model="end_date">
	                    </div>
	                </div>
	        	</div>
	        	<!-- STEP DURATION -->
	            <div class="col-md-6 col-sm-12 col-xs-12">
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
		    	<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
		         	<div class="btn btn-primary btn-block" @click="genData()">
		         		View
		         	</div>
		        </div>
	        </div>
	    </div>
	    <hr />
        <!-- GRAPH -->
        <div class="panel-body" style="padding-top: 0">
	        <div class="row" style="margin-top: 30px">
	            <div class="col-sm-12 col-xs-12">
	            	<h5 class="control-label">Booking Chart [กราฟแสดงจำนวนงานรับ]</h5>
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
	    			<h5 class="pull-left">Booking Table [ตารางแสดงงานรับ]</h5>
		    		<div class="pull-right">
		        		<div class="btn btn-warning btn-outline btn-block" @click="genExcel()">
		        			<i class="fa-file-excel-o" style="margin-right: 5px"></i> Export to Excel
		        		</div>
		        	</div>
	        	</div>
	        </div>
	        <div class="row" style="padding: 10px 0">
	        	<div class="col-sm-12">
			        <div class="table-responsive">
				    	<table id="mw-table-order" class="table table-hover table-bordered" style="font-size: 0.9em">
				    		<thead>
				    			<tr>
				    				<th v-for="field in selected_fields" class="mw-center">@{{ fields[field] }}</th>
				    			</tr>
				    		</thead>
				    		<tbody>
				    			<tr v-for="data in reportData">
				    				<td v-for="field in selected_fields" class="mw-center">@{{ data[field] }}</td>
				    			</tr>
				    		</tbody>
				    	</table>
			    	</div>
		        </div>
		    </div>
	    </div>
	</div>

</div>

<!-- DATA -->
<a id="mw-report-file" href="#" download=""></a>
<div id="mw-url-get-data" data-url="{{ \URL::route('admin.report_booking.get_data.post') }}"></div>
<div id="mw-url-adjust-report" data-url="{{ \URL::route('admin.report_booking.adjust_report.post') }}"></div>
<div id="mw-url-gen-report" data-url="{{ \URL::route('admin.report_booking.gen_excel.post') }}"></div>