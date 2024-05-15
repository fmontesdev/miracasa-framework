function login() {
    if (validate_login() != 0) {
        var userForm = document.getElementById('username_log').value;
        var pswdForm = document.getElementById('passwd_log').value;

        ajaxPromise(friendlyURL('?module=login'), 'POST', 'JSON', { 'op': 'login', 'username': userForm, 'password': pswdForm })
            .then(function(data) {
                // console.log(data);
                // return;

                if (data == 'error_user') {
                    document.getElementById('error_username_log').innerHTML = "El usuario no existe, asegúrese de haberlo escrito corréctamente"
                } else if (data == 'error_passwd') {
                    document.getElementById('error_passwd_log').innerHTML = "La contraseña es incorrecta"
                } else {
                    localStorage.setItem("access_token", data.access);
                    localStorage.setItem("refresh_token", data.refresh);

                    //SweetAlert2
                    Swal.fire({
                        // position: "top-end",
                        icon: "success",
                        // title: "Your work has been saved",
                        text: "Sesión iniciada",
                        showConfirmButton: false,
                        timer: 1250
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

// pinta el formulario de registro desde el formulario del login
function button_toRegister() {
    $('#to_register').on('click', function() {
        $('#login_container').hide();
        $('#register_container').show();
    });
}

$(document).ready(function() {
    key_login();
    button_login();
    button_toRegister();
});