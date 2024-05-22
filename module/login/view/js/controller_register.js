function register() {
    if (validate_register() != 0) {
        var userForm = document.getElementById('username_reg').value;
        var pswdForm = document.getElementById('passwd1_reg').value;
        var emailForm = document.getElementById('email_reg').value;

        // console.log([userForm, pswdForm, emailForm]);
        // return;
        
        ajaxPromise(friendlyURL('?module=login'), 'POST', 'JSON', { 'op': 'register', 'username': userForm, 'password': pswdForm, 'email': emailForm })
            .then(function(data) {
                // console.log(data);
                // return;

                if (data == 'error_user') {
                    document.getElementById('error_username_reg').innerHTML = "El usuario ya esta en uso, inténtalo con otro"
                } else if (data == 'error_email') {
                    document.getElementById('error_email_reg').innerHTML = "El email ya esta en uso, asegurate de no tener ya una cuenta"
                } else {
                    //SweetAlert2
                    Swal.fire({
                        // position: "top-end",
                        icon: "success",
                        title: "Correo electrónico enviado",
                        text: "Verifica tu email desde el correo electrónico enviado",
                        showConfirmButton: false,
                        // timer: 2000
                      });
                    setTimeout(function(){window.location.href = friendlyURL('?module=home');}, 2500);
                }
            }).catch(function(textStatus) {
                if (console && console.log) {
                    console.log("La solicitud ha fallado: " + textStatus);
                }
            });
    }
}

// registro se activará al presionar tecla enter (code 13)
function key_register() {
    $("#register").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            e.preventDefault(); // detiene el envío normal del formulario para que javascript maneje los datos mediante ajaxPromise
            register();
        }
    });
}

// registro se activará al hacer clic sobre el botón de registro
function button_register() {
    $('#register').on('click', function(e) {
        e.preventDefault();  // detiene el envío normal del formulario para que javascript maneje los datos mediante ajaxPromise
        register();
    });
}

function validate_register() {
    // expresión ?=. buscará en la cadena almenos una condición de la expresión regular sin avanzar en la cadena de entrada
    // expresión regular * cero o mas carácteres 
    // expresión regular + uno o mas carácteres
    // expresión regular . cualquier carácter excepto salto de linea
    // expresión regualar \W carácter no alfanumérico

    var username_exp = /^(?=.{5,}$)(?=.*[a-zA-Z0-9]).*$/;
    var mail_exp = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
    var passwd_exp = /^(?=.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/;
    var error = false;

    if (document.getElementById('username_reg').value.length === 0) {
        document.getElementById('error_username_reg').innerHTML = "Tienes que introducir el usuario";
        error = true;
    } else {
        if (document.getElementById('username_reg').value.length < 5) {
            document.getElementById('error_username_reg').innerHTML = "El usuario tiene que tener 5 caracteres como mínimo";
            error = true;
        } else {
            if (!username_exp.test(document.getElementById('username_reg').value)) { // test() prueba coincidencia en la cadena. Devuelve true o false.
                document.getElementById('error_username_reg').innerHTML = "El usuario no puede contener caracteres especiales";
                error = true;
            } else {
                document.getElementById('error_username_reg').innerHTML = "";
            }
        }
    }

    if (document.getElementById('email_reg').value.length === 0) {
        document.getElementById('error_email_reg').innerHTML = "Tienes que introducir el email";
        error = true;
    } else {
        if (!mail_exp.test(document.getElementById('email_reg').value)) {
            document.getElementById('error_email_reg').innerHTML = "El formato del email es inválido";
            error = true;
        } else {
            document.getElementById('error_email_reg').innerHTML = "";
        }
    }

    if (document.getElementById('passwd1_reg').value.length === 0) {
        document.getElementById('error_passwd1_reg').innerHTML = "Tienes que introducir la contraseña";
        error = true;
    } else {
        if (document.getElementById('passwd1_reg').value.length < 8) {
            document.getElementById('error_passwd1_reg').innerHTML = "La contraseña tiene que tener 8 caracteres como mínimo";
            error = true;
        } else {
            if (!passwd_exp.test(document.getElementById('passwd1_reg').value)) {
                document.getElementById('error_passwd1_reg').innerHTML = "La contraseña debe contener mínimo 8 caracteres, mayusculas, minusculas y simbolos especiales";
                error = true;
            } else {
                document.getElementById('error_passwd1_reg').innerHTML = "";
            }
        }
    }

    if (document.getElementById('passwd2_reg').value.length === 0) {
        document.getElementById('error_passwd2_reg').innerHTML = "Tienes que repetir la contraseña";
        error = true;
    } else {
        if (document.getElementById('passwd2_reg').value.length < 8) {
            document.getElementById('error_passwd2_reg').innerHTML = "La contraseña tiene que tener 8 caracteres como mínimo";
            error = true;
        } else {
            if (document.getElementById('passwd2_reg').value === document.getElementById('passwd1_reg').value) {
                document.getElementById('error_passwd2_reg').innerHTML = "";
            } else {
                document.getElementById('error_passwd2_reg').innerHTML = "Las contraseñas no coinciden";
                error = true;
            }
        }
    }

    if (error == true) {
        return 0;
    }
}

// pinta el formulario de login desde el formulario de registro
function button_toLogin() {
    $('#to_login').on('click', function() {
        $('#login_container').show();
        $('#register_container').hide();
        $('#recoverEmail_container').hide();
        $('#otp_container').hide();
    });
}

$(document).ready(function() {
    key_register();
    button_register();
    button_toLogin();
});