function fbPlugin(d, s, id, fb_app_id) {

    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}


function fbCheckLogin(callback)
{
    FB.getLoginStatus(function(response) {
        callback(response);
    });
}

function fbLogin(callback, scope)
{
    if (scope == '') scope = 'public_profile,email,publish_actions,user_birthday';

    FB.login(function(response){
        callback(response);
    }, {scope: scope});
}

function fbLogout(callback)
{
    FB.logout(function(response){
        callback(response);
    });
}

function fbGetProfile(callback, fields)
{
    if (fields == '') fields = 'name,email,picture,birthday,gender';

    FB.api('/me?fields='+fields, function(response) {
        // console.log(response);
        callback(response);
    });
}

function fbFeedDialog(data, callback)
{
    FB.ui({
        method: 'feed',
        link: data.link,
        caption: data.message,
    }, function(response){
        callback(response);
    });
}

function fbPostPublish(data, callback)
{
    FB.api(
        '/me/feed',
        'POST',
        {
            message: data.message,
            object_attachment: data.photo_id,
            privacy: {value: 'SELF'},
        },
        function(response) {
            callback(response);
        }
    );
}

function fbCreatePhoto(data, callback)
{
    FB.api(
        '/me/photos',
        'POST',
        {
            published: false,
            url: data.photo_url,
        },
        function(response) {
            callback(response);
        }
    );
}

function fbGenChatBootbox(fb_app_id, fb_name)
{
    const form = fbGenFormFbChatBox(fb_name);

    var myBootBox = bootbox.dialog({
        className: 'mw-bootbox-fb-send-msg',
        title : '',
        message : form,
        backdrop: true,
        onEscape: function(){
            // document.getElementById('facebook-jssdk').remove();
            window.location.reload();
        },
    });
}

function fbGenFormFbChatBox(fb_name)
{
    const screen_w = $(window).width();
    var form = '';

    form += '<div class="fb-page"';
    form += 'data-href="https://www.facebook.com/'+fb_name+'/"';
    form += 'data-width="'+parseInt(screen_w*27/32)+'"';
    form += 'data-tabs="timeline,messages"';
    form += 'data-small-header="true"';
    form += 'data-adapt-container-width="true"';
    form += 'data-hide-cover="false"';
    form += 'data-show-facepile="false">';
    form += '<blockquote cite="https://www.facebook.com/'+fb_name+'/" class="fb-xfbml-parse-ignore">';
        form += '<a href="https://www.facebook.com/'+fb_name+'/" target="_blank">Visit to Our Page</a>';
    form += '</blockquote>';
    form += '</div>';

    return form;
}

function fbShareDialog(link, callback)
{
    FB.ui({
        method: 'share',
        href: link,
    }, function(response) {
        callback(response);
    });
}