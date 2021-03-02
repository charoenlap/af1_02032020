var mwVue;

$(document).ready(function(){

	const id = '#mw-page-report';

	mwVue = new Vue({
		el: id,
		data: {
			bkg_start: $(id).data('bkg-start'),
			bkg_end: $(id).data('bkg-end'),
			msg_start: $(id).data('msg-start'),
			msg_end: $(id).data('msg-end'),
			job_start: $(id).data('job-start'),
			job_end: $(id).data('job-end'),
			step: $(id).data('step'),
			fields: $(id).data('fields'),
			reportData: $(id).data('report-data'),
			chartData: $(id).data('chart-data'),
			selected_fields: $(id).data('selected-field'),
			default_fields: $(id).data('selected-field'),
			statusAll: $(id).data('status-all'),
			status_chosen: $(id).data('status-chosen'),
			ctmAll: $(id).data('customer-all'),
			ctm_chosen: $(id).data('customer-chosen'),
		},
		methods: {
			adjustSelectAll: function(){

				selected_fields = [];
				$.each(this.fields, function(field, label){
					selected_fields.push(field);
				});

				this.selected_fields = selected_fields;
			},
			adjustClearAll: function(){
				this.selected_fields = [];
			},
			adjustReset: function(){
				this.selected_fields = this.default_fields;
			},
			saveAdjustReport: function(){
				swalWaiting();
				postAdjustReport();
			},
			genData: function(){
				swalWaiting();
                postGenData();
            },
            genExcel: function(){
            	swalWaiting();
            	postDownloadReport();
            }
		},
		mounted: function() {

			// DATE PICKER.
			$('.mw-input-datepicker').datepicker({
		        format: 'dd/mm/yyyy',
		        autoclose: true
		    });
		    $('.mw-date-bkg-start').on('change', function(){
                mwVue.bkg_start = $(this).val();
            });
            $('.mw-date-bkg-end').on('change', function(){
                mwVue.bkg_end = $(this).val();
            });
            $('.mw-date-msg-start').on('change', function(){
                mwVue.msg_start = $(this).val();
            });
            $('.mw-date-msg-end').on('change', function(){
                mwVue.msg_end = $(this).val();
            });
            $('.mw-date-job-start').on('change', function(){
                mwVue.job_start = $(this).val();
            });
            $('.mw-date-job-end').on('change', function(){
                mwVue.job_end = $(this).val();
            });


			// CHART.
			if (this.chartData.length > 0) {
            	genChart(this.chartData, '#mw-chart-order .line-chart', this.step);
			}

		    // LABELAUTY.
            $('.mw-labelauty-radio').labelauty();

            // DATATABLE.
            dt = $('#mw-table-order').dataTable({
                scrollX: true
            });
		}
	});
});

function postGenData()
{
	$.ajax({
		headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
		type: 'post',
		url: $('#mw-url-get-data').data('url'),
		data: {
			bkg_start: mwVue.bkg_start,
			bkg_end: mwVue.bkg_end,
			msg_start: mwVue.msg_start,
			msg_end: mwVue.msg_end,
			job_start: mwVue.job_start,
			job_end: mwVue.job_end,
			step: mwVue.step,
			status: mwVue.status_chosen,
			ctm_chosen: mwVue.ctm_chosen,
		},
		success: function(result) {

			swalStop();
			mwVue.reportData = result.report_data;
			mwVue.chartData = result.chart_data;

			// GEN CHART.
			genChart(mwVue.chartData, '#mw-chart-order .line-chart', mwVue.step);

            // RECALL DATATABLE.
            dt.fnDestroy();
            setTimeout(function(){
                dt = $('#mw-table-order').dataTable({
                    scrollX: true
                });
            }, 1000);
		}
	});
}

function postAdjustReport()
{
	$.ajax({
		headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
		type: 'post',
		url: $('#mw-url-adjust-report').data('url'),
		data: {
			selected_fields: mwVue.selected_fields,
		},
		success: function(result) {

			swalStop();
			mwVue.default_fields = mwVue.selected_fields;

			$('#mw-accordian-report').removeClass('in');
		}
	});
}

function postDownloadReport()
{
	$.ajax({
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		type: 'post',
		url: $('#mw-url-gen-report').data('url'),
		data: {
			bkg_start: mwVue.bkg_start,
			bkg_end: mwVue.bkg_end,
			msg_start: mwVue.msg_start,
			msg_end: mwVue.msg_end,
			job_start: mwVue.job_start,
			job_end: mwVue.job_end,
			selected_fields: mwVue.selected_fields,
			status: mwVue.status_chosen,
			ctm_chosen: mwVue.ctm_chosen,
		},
		success: function(result) {

			swalStop();
			if (result.status == 'success') {
				$('#mw-report-file').prop('href', result.base_url+'/'+result.sheet_path);
				document.getElementById('mw-report-file').click();
			}
		},
		error: function(result) {
			swalStop();
			mwShowErrorMessage(result);
		}
	});
}


function genChart(models, chart_id, step)
{
    var series = [];
    var labels = [];
    var range = (models.length > 7) ? Math.floor(models.length/7) : 1;

    $.each(models, function(i, model){
        series.push(model.count);
        if (i%range == 0) {
            labels.push(model.label_d+' '+((step == 'h') ? model.label_h : ''));
        } else {
            labels.push('');
        }
    });

    new Chartist.Line(chart_id, {
        labels: labels,
        series: [series]
    }, {
        showArea: true,
        showPoint: false,
        showLine: true,
        lineSmooth: true,
        fullWidth: true,
        chartPadding: {
            top: 10,
            right: 35,
            bottom: 20,
            left: 10
        },
        axisX: {
            showLabel: true,
            showGrid: true,
            offset: 30
        },
        axisY: {
            showLabel: true,
            showGrid: true,
            offset: 30
        },
        plugins: [
            Chartist.plugins.tooltip(),
        ]
    });
}