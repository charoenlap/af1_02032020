$(function(e){
	$('#mw-table-order').DataTable( {
   		dom: 'Bfrtip',
   		buttons: [
            'copy', 'csv', 'excel'
        ]
   });
	// $.ajax({
	// 	url: $('#url').val()+'api/ajax/report.php',
	// 	type: 'GET',
	// 	dataType: 'json',
	// 	data: {
	// 		type: 'get_customers'
	// 	},
	// })
	// .done(function(data) {
	// 	var customers = $('#customers_id').val();
	// 	$.each(data, function(index) {
	// 		var html_select = '';
	// 		if(parseInt(customers) == data[index].id){
	// 			html_select = 'selected';
	// 		}else{
	// 			html_select = '';
	// 		}
	// 		console.log(customers+' '+data[index].id);
	// 		// html_select = 'selected';
	// 		$('#customers').append('<option value="'+data[index].id+'" '+html_select+'>'+data[index].name+'</option>');
	// 	})
	// 	console.log("success");
	// })
	// .fail(function() {
	// 	console.log("error");
	// })
	// .always(function() {
	// 	console.log("complete");
	// });
	//af1express.com
	 // $.getJSON("http://localhost/af1_web/public_html/api/ajax/report.php?type=report_connote&book_date_start="+$('#book_date_start').val()+"&book_date_end="+$('#book_date_end').val()+"&job_date_start="+$('#job_date_start').val()+"&job_date_end="+$('#job_date_end').val()+"&msg_date_start="+$('#msg_date_start').val()+"&msg_date_end="+$('#msg_date_end').val()+"&status_chosen="+$('#status_chosen').val()+"&customers="+$('#customers').val(), function(returndata) {
	 // 		// console.log(returndata);

           
  //     }); 

	 

	// $.ajax({
	// 	url: 'http://af1express.com/api/ajax/report.php',
	// 	type: 'GET',
	// 	dataType: 'json',
	// 	data: {
	// 		type: 'report_connote',
	// 		book_date_start: $('#book_date_start').val(),
	// 		book_date_end:$('#book_date_end').val(),

	// 		job_date_end:$('#job_date_start').val(),
	// 		job_date_end:$('#job_date_end').val(),
	// 		msg_date_end:$('#msg_date_start').val(),
	// 		msg_date_end:$('#msg_date_end').val(),
	// 		status_chosen:$('#status_chosen').val(),
	// 		customers:$('#customers').val()
	// 	},
	// })
	// .done(function(data) {
	// 	console.log(data);
	// 	count = 1;
	// 	// $.each(data, function(index) {
	// 	// 	html = '<tr>';
	// 	// 	html += '<td>'+count+'</td>';
	// 	// 	html += '<td>'+data[index].booking_no+'</td>';
	// 	// 	html += '<td>'+data[index].date_start+'</td>';
	// 	// 	html += '<td>'+data[index].company_key+'</td>';
	// 	// 	html += '<td>'+data[index].company_sender+'</td>';
	// 	// 	html += '<td>'+data[index].company_district+'</td>';

	// 	// 	html += '<td>'+data[index].company_province+'</td>';
	// 	// 	html += '<td>'+data[index].company_postcode+'</td>';
	// 	// 	html += '<td>'+data[index].company_person+'</td>';
	// 	// 	html += '<td>'+data[index].company_address+'</td>';
	// 	// 	html += '<td>'+data[index].company_phone+'</td>';

	// 	// 	html += '<td>'+data[index].service_level+'</td>';
	// 	// 	html += '<td>'+data[index].service_type+'</td>';
	// 	// 	html += '<td>'+data[index].size_box+'</td>';
	// 	// 	html += '<td>'+data[index].time_pickup+'</td>';
	// 	// 	html += '<td>'+data[index].service_comment+'</td>';

	// 	// 	html += '<td>'+data[index].customer_ref+'</td>';
	// 	// 	html += '<td>'+data[index].time_send+'</td>';
	// 	// 	html += '<td>'+data[index].consignee_name+'</td>';
	// 	// 	html += '<td>'+data[index].consignee_phone+'</td>';
	// 	// 	html += '<td>'+data[index].consignee_company+'</td>';

	// 	// 	html += '<td>'+data[index].consignee_address+'</td>';
	// 	// 	html += '<td>'+data[index].consignee_districe+'</td>';
	// 	// 	html += '<td>'+data[index].consignee_province+'</td>';
	// 	// 	html += '<td>'+data[index].consignee_postcode+'</td>';
	// 	// 	html += '<td>'+data[index].connote_key+'</td>';

	// 	// 	html += '<td>'+data[index].connotes_status+'</td>';
	// 	// 	html += '<td>'+data[index].sign_name+'</td>';
	// 	// 	html += '<td>'+data[index].time_send+'</td>';
	// 	// 	html += '<td>'+data[index].time_return+'</td>';
	// 	// 	html += '<td>'+data[index].return_comment+'</td>';
	// 	// 	html += '<td></td>';
	// 	// 	html += '</tr>';
	// 	// 	count += 1;
	// 	// 	$('#mw-table-order tbody').append(html);
	// 	// })
		
	//     $('#mw-table-order').DataTable( {
	//         dom: 'Bfrtip',
	//         buttons: [
	//             'copy', 'csv', 'excel'
	//         ]
	//     } );

	// 	console.log("success");
	// })
	// .fail(function() {
	// 	console.log("error");
	// })
	// .always(function() {
	// 	console.log("complete");
	// });
	
});
$(document).on('click','#btn-filter',function(e){
	console.log('Click filter');
			// job_date_end:$('#job_date_start').val(),
			// job_date_end:$('#job_date_end').val(),
			// msg_date_end:$('#msg_date_start').val(),
			// msg_date_end:$('#msg_date_end').val(),
	window.location=$('#url').val()+'/admin/reportnew?book_date_start='+$('#book_date_start').val()+'&book_date_end='+$('#book_date_end').val()+'&job_date_start='+$('#job_date_start').val()+'&job_date_end='+$('#job_date_end').val()+'&msg_date_start='+$('#msg_date_start').val()+'&msg_date_end='+$('#msg_date_end').val()+'&status_chosen='+$('#status_chosen').val()+'&customers='+$('#customers').val();
	
	// $('#mw-table-order').Rows.Clear();
	// $.ajax({
	// 	url: 'http://af1express.com/api/ajax/report.php',
	// 	type: 'GET',
	// 	dataType: 'json',
	// 	data: {
	// 		type: 'report_connote',
	// 		book_date_start: $('#book_date_start').val(),
	// 		book_date_end:$('#book_date_end').val(),
	// 		job_date_start:$('#job_date_start').val(),
	// 		job_date_end:$('#job_date_end').val(),
	// 		msg_date_start:$('#msg_date_start').val(),
	// 		msg_date_end:$('#msg_date_end').val(),
	// 		status_chosen:$('#status_chosen').val(),
	// 		customers:$('#customers').val()
	// 	},
	// })
	// .done(function(data) {
	// 	console.log('Ajax filter success');
	// 	console.log(data);
	// 	//$('#mw-table-order tbody').html('');
	// 	count = 1;
	// 	$.each(data, function(index) {
	// 		html = '<tr>';
	// 		html += '<td>'+count+'</td>';
	// 		html += '<td>'+data[index].booking_no+'</td>';
	// 		html += '<td>'+data[index].date_start+'</td>';
	// 		html += '<td>'+data[index].company_key+'</td>';
	// 		html += '<td>'+data[index].company_sender+'</td>';
	// 		html += '<td>'+data[index].company_district+'</td>';

	// 		html += '<td>'+data[index].company_province+'</td>';
	// 		html += '<td>'+data[index].company_postcode+'</td>';
	// 		html += '<td>'+data[index].company_person+'</td>';
	// 		html += '<td>'+data[index].company_address+'</td>';
	// 		html += '<td>'+data[index].company_phone+'</td>';

	// 		html += '<td>'+data[index].service_level+'</td>';
	// 		html += '<td>'+data[index].service_type+'</td>';
	// 		html += '<td>'+data[index].size_box+'</td>';
	// 		html += '<td>'+data[index].time_pickup+'</td>';
	// 		html += '<td>'+data[index].service_comment+'</td>';

	// 		html += '<td>'+data[index].customer_ref+'</td>';
	// 		html += '<td>'+data[index].time_send+'</td>';
	// 		html += '<td>'+data[index].consignee_name+'</td>';
	// 		html += '<td>'+data[index].consignee_phone+'</td>';
	// 		html += '<td>'+data[index].consignee_company+'</td>';

	// 		html += '<td>'+data[index].consignee_address+'</td>';
	// 		html += '<td>'+data[index].consignee_districe+'</td>';
	// 		html += '<td>'+data[index].consignee_province+'</td>';
	// 		html += '<td>'+data[index].consignee_postcode+'</td>';
	// 		html += '<td>'+data[index].connote_key+'</td>';

	// 		html += '<td>'+data[index].connotes_status+'</td>';
	// 		html += '<td>'+data[index].sign_name+'</td>';
	// 		html += '<td>'+data[index].time_send+'</td>';
	// 		html += '<td>'+data[index].time_return+'</td>';
	// 		html += '<td>'+data[index].return_comment+'</td>';
	// 		html += '<td></td>';
	// 		html += '</tr>';
	// 		count += 1;
	// 		$('#mw-table-order tbody').append(html);
	// 	})
	// 	$('#mw-table-order').DataTable( {
	//         dom: 'Bfrtip',
	//         buttons: [
	//             'copy', 'csv', 'excel'
	//         ]
	//     } );
	// 	console.log("success");
	// })
	// .fail(function() {
	// 	console.log("ajax filter error");
	// })
	// .always(function() {
	// 	console.log("complete");
	// });
	
});
