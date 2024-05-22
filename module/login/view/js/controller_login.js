function login() {
    if (validate_login() != 0) {
        var userForm = document.getElementById('username_log').value;
        var pswdForm = document.getElementById('passwd_log').value;

        ajaxPromise(friendlyURL('?module=login'), 'POST', 'JSON', { 'op': 'login', 'username': userForm, 'password': pswdForm })
            .then(function(data) {
                console.log(data);
                // return;

                if (data == 'error_user') {
                    document.getElementById('error_username_log').innerHTML = "El usuario no existe, asegúrese de haberlo escrito corréctamente"
                } else if (data == 'error_passwd') {
                    document.getElementById('error_passwd_log').innerHTML = "La contraseña es incorrecta"
                } else if (data == 'user_inactive') {
                    document.getElementById('error_passwd_log').innerHTML = "Tu cuenta ha sido deshabilitada"
                } else if (data == 'otp') {
                    login_otp();
                } else {
                    localStorage.setItem("access_token", data.access);
                    localStorage.setItem("refresh_token", data.refresh);

                    //SweetAlert2
                    Swal.fire({
                        // position: "top-end",
                        icon: "success",
                        title: "Sesión iniciada",
                        // text: "Your work has been saved",
                        showConfirmButton: false,
                        // timer: 1500
                    });

                    var location = localStorage.getItem("location");
                    if (location == 'home')  {
                        setTimeout(function(){window.location.href = friendlyURL('?module=home');}, 1500); // redirigimos al home
                    } else if (location == 'shop') {
                        setTimeout(function(){window.location.href = friendlyURL('?module=shop');}, 1500); // redirigimos al shop
                    } else {
                        setTimeout(function(){window.location.href = friendlyURL('?module=shop');}, 1500); // redirigimos al shop
                    }
                }
            }).catch(function(textStatus) {
                if (console && console.log) {
                    console.log("La solicitud ha fallado: " + textStatus);
                }
            });
    }
}

function login_otp() {
    $('#login_container').hide();
    $('#register_container').hide();
    $('#recoverEmail_container').hide();
    $('#otp_container').show();
}

function key_login() {
    $("#login").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            e.preventDefault();
            login();
        }
    });
}

function button_login() {
    $('#login').on('click', function(e) {
        e.preventDefault();
        login();
    });
}

function validate_login() {
    var error = false;

    if (document.getElementById('username_log').value.length === 0) {
        document.getElementById('error_username_log').innerHTML = "Tienes que introducir el usuario";
        error = true;
    } else {
        if (document.getElementById('username_log').value.length < 5) {
            document.getElementById('error_username_log').innerHTML = "El usuario tiene que tener 5 caracteres como mínimo";
            error = true;
        } else {
            document.getElementById('error_username_log').innerHTML = "";
        }
    }

    if (document.getElementById('passwd_log').value.length === 0) {
        document.getElementById('error_passwd_log').innerHTML = "Tienes que introducir la contraseña";
        error = true;
    } else {
        if (document.getElementById('passwd_log').value.length < 8) {
            document.getElementById('error_passwd_log').innerHTML = "La contraseña tiene que tener 8 caracteres como mínimo";
            error = true;
        } else {
            document.getElementById('error_passwd_log').innerHTML = "";
        }
    }

    if (error == true) {
        return 0;
    }
}

function button_socialLogin() {
    $('#google').on('click', function(e) {
        social_login('google');
    });

    $('#github').on('click', function(e) {
        social_login('github');
    });
}

function social_login(param){
    authService = firebase_config();
    authService.signInWithPopup(provider_config(param))
        .then(function(result) {
            email_name = result.user._delegate.email;
            let username = email_name.split('@');
            console.log('Autenticado usuario', username[0], result.credential.providerId);

            if (result) {
                ajaxPromise(friendlyURL("?module=login"), 'POST', 'JSON', { 'op': 'social_login', 'uid': result.user._delegate.uid, 'username': username[0], 'email': result.user._delegate.email, 'avatar': result.user._delegate.photoURL, 'provider': result.credential.providerId })
                    .then(function(data) {
                        console.log(data);
                        // return;

                        localStorage.setItem("access_token", data.access);
                        localStorage.setItem("refresh_token", data.refresh);

                        //SweetAlert2
                        Swal.fire({
                            // position: "top-end",
                            icon: "success",
                            title: "Sesión iniciada",
                            // text: "Your work has been saved",
                            showConfirmButton: false,
                            // timer: 1500
                        });

                        var location = localStorage.getItem("location");
                        if (location == 'home')  {
                            setTimeout(function(){window.location.href = friendlyURL('?module=home');}, 1500); // redirigimos al home
                        } else if (location == 'shop') {
                            setTimeout(function(){window.location.href = friendlyURL('?module=shop');}, 1500); // redirigimos al shop
                        } else {
                            setTimeout(function(){window.location.href = friendlyURL('?module=shop');}, 1500); // redirigimos al shop
                        }
                    })
                    .catch(function() {
                        console.log('Error: Social login error');
                    });
            }
        })
        .catch(function(error) {
            var errorCode = error.code;
            console.log(errorCode);
            var errorMessage = error.message;
            console.log(errorMessage);
            var email = error.email;
            console.log(email);
            var credential = error.credential;
            console.log(credential);
        });
}

function firebase_config(){
    if(!firebase.apps.length){
        firebase.initializeApp(FIREBASE_CONFIG);
    }else{
        firebase.app();
    }
    return authService = firebase.auth();
}

function provider_config(param){
    if(param === 'google'){
        var provider = new firebase.auth.GoogleAuthProvider();
        provider.addScope('email');
        return provider;
    }else if(param === 'github'){
        return provider = new firebase.auth.GithubAuthProvider();
    }
}

// pinta el formulario de registro desde el formulario del login
function button_toRegister() {
    $('#to_register').on('click', function() {
        $('#login_container').hide();
        $('#register_container').show();
        $('#recoverEmail_container').hide();
        $('#otp_container').hide();
    });
}

$(document).ready(function() {
    key_login();
    button_login();
    button_socialLogin();
    button_toRegister();
});