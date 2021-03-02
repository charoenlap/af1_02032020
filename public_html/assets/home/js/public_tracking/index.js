var mwVuePublicTracking;

$(document).ready(function(){

    const id = '#mw-vue-tracking';

    mwVuePublicTracking = new Vue({
        el: id,
        data: {
            connoteModel: $(id).data('connote-model'),
            tracking_key: $(id).data('tracking-key'),
        },
        mounted: function(){
            $('#mw-input-code').focus();
        },
        methods: {
            checkOrderTracking: function() {
                swalWaiting();
                postPublicTracking();
            },
            clearOrderCode: function() {
                this.tracking_key = '';
            },
        }
    });

    $(document).keypress(function(e) {

        if(e.which == 13) {
            swalWaiting();
            postPublicTracking();
        }
    });
});

function postPublicTracking()
{
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').prop('content')},
        type: 'POST',
        url: $('#mw-url-public-tracking').data('url'),
        data: {
            tracking_key: mwVuePublicTracking.tracking_key
        },
        success: function(result) {

            swalStop();

            if (result.status == 'success') {
                mwVuePublicTracking.tracking_key = result.tracking_key;
                mwVuePublicTracking.connoteModel = result.connoteModel;
            } else {
                mwVuePublicTracking.tracking_key = '';
                mwVuePublicTracking.connoteModel = null;
            }
        },
        error: function(result) {
            console.log(result);
        }
    });
}