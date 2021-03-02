function onLoad() {
    gapi.load('auth2', function() {
        gapi.auth2.init();
    });
}

function mwGoogleSignIn(googleUser)
{
	// swalWaiting();

	var profile = googleUser.getBasicProfile();
	mwPostGoogleLogin(profile.getEmail());

    //var id_token = googleUser.getAuthResponse().id_token;// this token we will send to server to verify
    // console.log('ID: ' + profile.getId());
    // console.log('Name: ' + profile.getName());
    // console.log('Image URL: ' + profile.getImageUrl());
    // console.log('Email: ' + profile.getEmail());
    // console.log('Token: ' + id_token);
}

function mwPostGoogleLogin(email)
{
	const url = $('#mw-url-google-authen').data('url');

	$.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').prop('content')},
        type: 'post',
        url: url,
        data: {
        	'email': email
        },
        success: function(result) {
            window.location.href = result.url;
        },
        error: function(result) {
            swalStop();
            mwGoogleLogOut();
            mwShowErrorMessage(result);
        }
    });
}

function mwGoogleLogOut() {

    // gapi.load('auth2', function() {
    //     gapi.auth2.init();
    // });

    var auth2 = gapi.auth2.getAuthInstance();
console.log(auth2);
    if (auth2 !== null) {
console.log(auth2);
        auth2.signOut().then(function () {
            console.log('User signed out.');
        });
    }
}
