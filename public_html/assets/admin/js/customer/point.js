var mwVue

$(document).ready(function(){

    const id = '#mw-vue-contain';

    mwVue = new Vue({
        el: id,
        data: {
            pointModels: $(id).data('point'),
        },
        methods: {
            viewDetail: function(model) {
                popupPointDetail(model);
            },
            removeData: function(model) {
                popupRemoveWarning(model);
            }
        }
    });

    // DETAIL.
    $('#mw-table-point').on('click', '.mw-btn-detail', function(){
    });

    // REMOVE.
    $('#mw-table-point').on('click', '.mw-btn-remove', function(){
	});
});

function popupRemoveWarning(model)
{
	swal({
        title: 'คุณต้องการลบ <br/><small class="mw-black">'+model.person+' '+model.name+'</small>',
        text: 'แน่ใจหรือไม่ ?',
        html: true,
        customClass: 'mw-bg-white',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'ลบ',
        cancelButtonText: 'ไม่ลบ',
        closeOnConfirm: false,
        closeOnCancel: true,
    }, function(click_confirm) {

    	swalWaiting();

    	if (click_confirm) {

			$.ajax({
				headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
				type: 'POST',
				url: model.remove_url,
				success: function(result) {
					// console.log(result);
					window.location.reload();
				},
				error: function(result){
					swalStop();
					mwShowErrorMessage(result);
				}
			});
    	}
    });
}

function popupPointDetail(model)
{
    const html = genPointDetail(model);

    myBootBox = bootbox.dialog({
        className: 'mw-bootbox-employee',
        title : model.person,
        message : html,
        size: 'large',
        onEscape: true,
        backdrop: true,
    });
}

function genPointDetail(model)
{
    var html = '';
    html += '<div>';
    html += '<h5 class="mw-left">';
    html += 'ชื่อผู้รับ : '+model.person;
    html += '</h5>';
    html += '<h5 class="mw-left">';
    html += 'ชื่อบริษัท : '+model.name;
    html += '</h5>';
    html += '<h5 class="mw-left">';
    html += 'ที่อยู่ : '+model.address+' '+model.district+' '+model.province+' '+model.postcode;
    html += '</h5>';
    html += '<h5 class="mw-left">';
    html += 'เบอร์ติดต่อ : '+model.mobile;
    html += '</h5>';
    html += '</div>';
    return html;
}
