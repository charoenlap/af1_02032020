$(document).ready(function(){

    mwAlertify();

    mwChangeHeaderWhenScrollDown();

    // LOGIN.
    $('#mw-header-home').on('click', '.mw-btn-popup-login', function(){
        popupLogin();
    });

    // TRACKING.
    $('.mw-header-search-promotion').on('click', '#mw-btn-header-track', function(){
        const tracking_key = $('.mw-header-search-promotion').find('input[name=tracking_key]').val();
        const url = $('#mw-url-tracking').data('url')+'?tracking_key='+tracking_key;
        window.location.href = url;
    });

    // DATE PICKER.
    $('.mw-input-datepicker').datepicker({
        format: 'dd/mm/yyyy',
    });

    $('.mw-input-clockpicker').clockpicker({
        autoclose: true,
        donetext: 'Done',
    });

});


function popupLogin()
{
    const html = genFormLogin();

    myBootBox = bootbox.dialog({
        className: 'mw-bootbox-login',
        title : 'เข้าสู่ระบบ',
        message : html,
        size: 'large',
        onEscape: true,
        backdrop: true,
    });

    $('.mw-bootbox-login').on('click', '#mw-btn-login', function(){
        swalWaiting();
        const email = $.trim($('.mw-bootbox-login').find('#mw-input-email').val());
        const pwd = md5($.trim($('.mw-bootbox-login').find('#mw-input-pwd').val()));
        postLogin(email, pwd);
    });

    // Press Enter.
    $('.mw-bootbox-login').keypress(function(e) {
        if(e.which == 13) {
            swalWaiting();
            const email = $.trim($('.mw-bootbox-login').find('#mw-input-email').val());
            const pwd = md5($.trim($('.mw-bootbox-login').find('#mw-input-pwd').val()));
            postLogin(email, pwd);
        }
    });
}

function genFormLogin()
{

    var html = '';
    html += '<div class="row" style="margin-bottom: 8%">';
    html += '<div class="col-md-8 col-md-offset-2">';
        html += '<div class="mw-center mw-mg-tb-10">';
        html += '<h1 style="font-size: 1.3em;">บริษัท แอร์ฟอร์ส วัน เอ็กเพลส <br/> ยินดีต้อนรับลูกค้าทุกท่าน</h1>';

        html += '<div class="form-group">';
        html += '<label class="sr-only" for="inputName">Email</label>';
        html += '<input type="text" style="font-size: 1em" class="mw-font form-control" id="mw-input-email" name="email" placeholder="Email *">';
        html += '</div>';

        html += '<div class="form-group">';
        html += '<label class="sr-only" for="inputPassword">รหัสผ่าน</label>';
        html += '<input type="password" style="font-size: 1em" class="mw-font form-control" id="mw-input-pwd" name="password" placeholder="รหัสผ่าน *">';
        html += '</div>';
        html += '<div class="mw-text-error" id="form-error-invalid"></div>';

        html += '<div id="mw-btn-login" class="btn btn-color btn-block" style="font-size: 1em">เข้าสู่ระบบ</div>';
        html += '</div>';
    html += '</div>';
    html += '</div>';
    return html;
}


function postLogin(email, pwd)
{
    const url_login = $('#mw-url-auth').data('value');

    $.ajax({
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
        type: 'post',
        url: url_login,
        data: {
            'email': email,
            'password': pwd
        },
        success: function(result) {
            window.location.href = result;
        },
        error: function(result) {

            swalStop();
            mwShowErrorMessage(result);
        }
    });
}


function mwChangeHeaderWhenScrollDown()
{
    $(window).on("scroll", function () {

        if ($(window).width() > 760) {

            if ($(this).scrollTop() > 200) {
                $('.mw-header-small').show();
                $('.mw-scroll-top').show();

            } else {
                $('.mw-header-small').hide();
                $('.mw-scroll-top').hide();
            }
        }else{
            if ($(this).scrollTop() > 400) {
                $('.mw-scroll-top').show();

            } else {
                $('.mw-scroll-top').hide();
            }
        }
    });
}

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

/*SWEET ALERT*/
function swalWaiting() {
    swal({title: '', html: true, imageUrl: '/assets/home/images/mw_loading.gif', type:'', showConfirmButton: false});
}
function swalProcess() {
    swal({title:'', text: 'Processing', imageUrl: '/assets/themes/remark/images/loading.gif', type:'info', showConfirmButton: false});
}
function swalSuccess() {
    swal({title:'', text: 'Submit Successful', type:'success', confirmButtonText:  'OK', timer: 10000});
}
function swalSuccessCustom(title, msg) {
    swal({title:title, text: msg, type:'success', confirmButtonText:  'OK', timer: 5000});
}
function swalStop() {
    swal.close();
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
           scrollTop: ($('#form-error-'+keys[0]).offset().top) - 200,
        });
    }

    if (result.status === 500) {
        $('.mw-text-error').html('* Error 500');
    }
}