$(document).ready(function(){

    Site.run();
    // CUSTOM THEME.
    $('.site-menubar').css('height', '100%');

    // mwInitMenubarFold();
    mwAlertify();

    // Change Lang.
    $('.mw-lang-btn').on('click', function() {
        swalWaiting();
        mwChangeLang($(this));
    });

    // GUIDE POPOVER.
    $('.mw-btn-guide').webuiPopover({trigger: 'hover'});
    // DATA TABLE.
    $('.dataTable').dataTable();
    // DATE PICKER.
    $('.mw-input-datepicker').datepicker({
        format: 'dd/mm/yyyy',
    });

    // DROPIFY INPUT.
    $('.mw-input-dropify').dropify();
});

/*FLASH ALERTIFY*/
function mwAlertify()
{
    if ($('.mw-flash-alert').length) {

        const status  = $('.mw-flash-alert').data('status');
        const message = $('.mw-flash-alert').data('message');

        if (status === 'success') alertify.success(message, 'top-right');
        if (status === 'fail') alertify.error(message);
    }

}

/*CHANGE LANG*/
function mwChangeLang(obj){

    const method    = 'ChangeLang';
    const lang      = $(obj).data('lang');
    const ajax_url  = $('#mw-url-ajax').data('value');

    $.ajax({
        headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type    : 'post',
        url     : ajax_url,
        data: {
            'method' : method,
            'lang'   : lang
        },
        success: function(result) {
            window.location.reload();
        },
    });
}

/* SET Menubar Fold */
function mwInitMenubarFold() {

    $('.dashboard').removeClass('site-menubar-fold');
    $('.dashboard').addClass('site-menubar-unfold');
}

/* ERROR MESSAGE */
function mwShowErrorMessage(result) {

    if (result.status === 422) {

        $('.mw-text-error').html('');
        const errors = $.parseJSON(result.responseText);

        const keys = [];

        $.each(errors.errors, function(key, value) {
            $('#form-error-'+key).html('* '+value[0]);

            keys.push(key);
        });

        // AUTO SCROLL.
        $('html,body').animate({
           scrollTop: ($('#form-error-'+keys[0]).offset().top) - 150,
        });
    }

    if (result.status === 500) {
        $('.mw-text-error').html('* Error 500');
    }
}

/*SWEET ALERT*/
function swalWaiting() {
    swal({title: '', html: true, imageUrl: '/assets/admin/images/mw_loading.gif', type:'', showConfirmButton: false});
}
function swalProcess() {
    swal({title:'', text: 'Processing', imageUrl: '/assets/themes/remark/images/loading.gif', type:'info', showConfirmButton: false});
}
function swalSuccess() {
    swal({title:'', text: 'Submit Successful', type:'success', confirmButtonText:  'OK', timer: 10000});
}
function swalSuccessCustom(title, msg) {
    swal({title:title, text: msg, type:'success', timer: 5000});
}
function swalStop() {
    swal.close();
}

/*CKEDITOR*/
function mwCustomCKEditor(ck_id)
{
    CKEDITOR.config.extraPlugins = 'font,colorbutton,sourcedialog,justify,youtube';
    //CKEDITOR.config.skin = 'office2013';

    CKEDITOR.replace(ck_id, {
        toolbar: [
            { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
            '/',
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
            { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList'] },
            { name: 'alignment', items : [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
            '/',
            { name: 'styles', items: [ 'Styles', 'Format', 'FontSize' ] },
            { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
            { name: 'document', items: [ 'Source'] },
            { name: 'links', items: [ 'Link' ]},
            { name: 'contents', items: [ 'Image' ]},
            { name: 'youtube', items: [ 'Youtube'] },
        ]
    });
}
