var mwVueProfile

$(document).ready(function(){

	const id = '#mw-vue-profile';

	mwVueProfile = new Vue({
		el: id,
		data: {
			ctmModel: $(id).data('model'),
		},
		methods: {
			changeInputToText: function(){
				$('.mw-row-value').find('.mw-input-value').addClass('hidden');
				$('.mw-btn-value').removeClass('hidden');
			},
			saveData: function() {
				postValue();
			}
		}
	});

	$('.mw-btn-value').on('click', function(){

		$('.mw-input-value').addClass('hidden');
		$('.mw-btn-value').removeClass('hidden');
		$(this).parents('.mw-row-value').find('.mw-input-value').removeClass('hidden');
		$(this).parents('.mw-row-value').find('.mw-input-value').find('.form-control').focus();

		$(this).addClass('hidden');
	});

	$('#mw-table-profile').on('click', '.mw-btn-cancel', function(){

	});
});

function postValue()
{
	 $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').prop('content')},
        type: 'POST',
        url: $('#mw-url-profile-post').data('url'),
        data: {
            ctmModel: mwVueProfile.ctmModel
        },
        success: function(result) {

            swalStop();

            mwVueProfile.ctmModel = result.model
            mwVueProfile.changeInputToText();
        },
        error: function(result) {
            console.log(result);
        }
    });
}