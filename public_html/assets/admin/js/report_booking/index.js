var mw_page_report;

$(document).ready(function(){

	const id = '#mw-page-report';

	mw_page_report = new Vue({
		el: id,
		data: {
			start_date: $(id).data('start-date'),
			end_date: $(id).data('end-date'),
			step: $(id).data('step'),
			fields: $(id).data('fields'),
			reportData: $(id).data('report-data'),
			chartData: $(id).data('chart-data'),
			selected_fields: $(id).data('selected-field'),
			default_fields: $(id).data('selected-field'),
			customerModels: $(id).data('customers'),
			customer_id: $(id).data('customer-chosen'),
			statusAll: $(id).data('status-all'),
			status_chosen: $(id).data('status-chosen'),
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
		    $('.mw-date-start').on('change', function(){
                mw_page_report.start_date = $(this).val();
            });
            $('.mw-date-end').on('change', function(){
                mw_page_report.end_date = $(this).val();
            });

			// CHART.
			if (this.chartData.length > 0) {
            	genChart(this.chartData, '#mw-chart-order .line-chart', this.step);
			}

		    // LABELAUTY.
            $('.mw-labelauty-radio').labelauty();

            // DATATABLE.
            dt = $('#mw-table-order').dataTable({
                responsive: true,
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
			start_date: mw_page_report.start_date,
			end_date: mw_page_report.end_date,
			step: mw_page_report.step,
			customer_id: mw_page_report.customer_id,
			status: mw_page_report.status_chosen,
		},
		success: function(result) {

			swalStop();
			mw_page_report.reportData = result.report_data;
			mw_page_report.chartData = result.chart_data;

			// GEN CHART.
			genChart(mw_page_report.chartData, '#mw-chart-order .line-chart', mw_page_report.step);

            // RECALL DATATABLE.
            dt.fnDestroy();
            setTimeout(function(){
                dt = $('#mw-table-order').dataTable({
                    responsive: true,
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
			selected_fields: mw_page_report.selected_fields,
		},
		success: function(result) {

			swalStop();
			mw_page_report.default_fields = mw_page_report.selected_fields;

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
			start_date: mw_page_report.start_date,
			end_date: mw_page_report.end_date,
			selected_fields: mw_page_report.selected_fields,
			customer_id: mw_page_report.customer_id,
			status: mw_page_report.status_chosen,
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