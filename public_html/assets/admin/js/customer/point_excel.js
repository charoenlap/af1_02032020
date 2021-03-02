var mwVue

$(document).ready(function(){

	const id = '#mw-vue-contain';

	mwVue = new Vue({
		el: id,
		data: {
			pointData: null,
		},
		methods: {
			readData: function(){
				swalWaiting();
				mwPostReadFile();
			},
			removeData: function(i) {
				this.pointData.splice(i, 1);
			},
			clearData: function(){
				this.pointData = null;
			},
			updateData: function(){
				mwUpdateEmployee();
			},
		}
	});
});

function mwPostReadFile()
{
	const data = new FormData();

	// EXCEL.
    if (typeof($('.mw-input-import-excel')[0].files) !== 'undefined') {

        jQuery.each($('.mw-input-import-excel')[0].files, function(i, file) {
            data.append('excel', file);
        });
    }

	$.ajax({
		headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
		type: 'POST',
		url: $('#mw-url-read-file').data('url'),
		contentType: false,
        processData: false,
        cache: false,
		data: data,
		success: function(result){

			swalStop();
			if (result.status == 'success') {
				mwVue.pointData = result.data;
			}
		},
		error: function(result){
			swalStop();
			console.log(result)
			mwShowErrorMessage(result);
		}
	});
}

function mwUpdateEmployee()
{
	swalWaiting();

	$.ajax({
		headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
		type: 'POST',
		url: $('#mw-url-update-data').data('url'),
		data: {
			pointData: mwVue.pointData
		},
		success: function(result){

			swalStop();
			if (result.status == 'success') {
				window.location.href = result.url;
			}
		},
		error: function(result){
			swalStop();
			console.log(result)
			mwShowErrorMessage(result);
		}
	});
}
