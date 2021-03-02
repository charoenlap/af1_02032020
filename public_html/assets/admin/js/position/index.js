$(document).ready(function(){

	$('.mw-btn-update').on('click', function(){
        mwPopupEdit($(this).data('model'));
    });

});

function mwPostEdit(position_id, label, except)
{
    swalWaiting();
	$.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'post',
        url: $('#mw-url-post').data('url'),
        data: {
        	position_id: position_id,
        	label: label,
        	except: except,
        },
        success: function(result) {
            window.location.reload();
        },
        error: function(result) {
            mwShowErrorMessage(result);
        }
    });
}

function mwPopupEdit(model)
{
    const html = mwGenFormEdit(model);

    bbox = bootbox.dialog({
        className: 'mw-bootbox-edit',
        title : 'Edit Position',
        message : html,
        size: 'large',
        onEscape: true,
        backdrop: true,
    });

    $(bbox).on('click', '#mw-btn-add', function(){

    	const label = $(bbox).find('input[name=position_label]').val();
    	const except = [];
    	$(bbox).find('input[name=mw_checkbox_role]:not(:checked)').each(function(i, checkbox){
    		except.push($(checkbox).val());
    	});

        mwPostEdit(model.id, label, except);
    });

    $(bbox).on('click', '#mw-btn-cancel', function(){
        bootbox.hideAll();
    });
}


function mwGenFormEdit(model)
{
	const permissions = $('#mw-data-permission').data('model');

    var form = '';
    form += "<div class='row'>";
    form += "<div class='col-md-8 col-md-offset-2'>";

    	form += "<h4>ตำแหน่ง</h4>";
    	form += "<div class='form-group'>";
        form += "<input type='text' name='position_label' class='form-control' value='"+model.label+"' />"
        form += "</div>";

		form += "<h4>สิทธิ์การเข้าถึง</h4>";
	    $.each(permissions, function(module, method_arr) {

		    form += "<div class='row'>";
	    	$.each(method_arr, function(i, method) {
				form += "<div class='col-sm-6'>";
		        form += "<div class='form-group'>";
		        	form += "<div class='checkbox-custom checkbox-warning'>";
		            form += "<input type='checkbox' name='mw_checkbox_role' class='form-control' ";
		            form += "value="+method.value+" id="+method.value
		            form += ($.inArray(method.value, model.except) == -1) ? "	checked" : "";
		            form += ">";
		            form += "<label for="+method.value+">"+module+" ["+method.label+"]"+"</label>";
		            form += "</div>";
				form += "</div>";
		    	form += "</div>";
	    	});
			form += "</div>";
		});

		form += "<hr/>";
		form += "<div class='row'>";
			form += "<div class='col-sm-12'>";
		        form += "<div id='mw-btn-cancel' class='btn btn-default mw-mg-lr-10'>CANCEL</div>";
		        form += "<div id='mw-btn-add' class='btn btn-success'><i class='fa-save'></i> SAVE</div>";
	        form += "</div>";
	    form += "</div>";

    form += "</div>";
    form += "</div>";
    return form;
}