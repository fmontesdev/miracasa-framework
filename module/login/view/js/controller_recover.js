//============== ENTER RECOVER EMAIL ==============//
function key_recover_email() {
    $("#recover_email").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            e.preventDefault();
            send_recover_email();
        }
    });
}

//============== CLICK RECOVER EMAIL ==============//
function button_recover_email() {
    $('#recover_email').on('click', function(e) {
        e.preventDefault();
        send_recover_email();
    });
}

//============== SEND RECOVER EMAIL ==============//
function send_recover_email(){
    if(validate_recover_email() != 0){
        var emailForm = document.getElementById('email_recover').value;
        // console.log(emailForm);
        // return;

        ajaxPromise(friendlyURL('?module=login'), 'POST', 'JSON', { 'op': 'send_recover_email', 'email_recover': emailForm })
            .then(function(data) {
                // console.log(data);
                // return;

                if (data == "error") {		
                    document.getElementById('error_email_recover').innerHTML = "Este email no ha sido dado de alta";
                } else {
                    //SweetAlert2
                    Swal.fire({
                        // position: "top-end",
                        icon: "success",
                        title: "Email enviado",
                        text: "Haz clic en el enlace del email enviado para restablecer tu contraseña",
                        showConfirmButton: false,
                        // confirmButtonColor: "#2eca6a",
                        // timer: 3000
                    });

                    setTimeout(function(){window.location.href = friendlyURL('?module=login');}, 2500);
                }

            }).catch(function(textStatus) {
                if (console && console.log) {
                    console.log("La solicitud ha fallado: " + textStatus);
                }
            });    
    }
}

//============== VALIDATE RECOVER EMAIL ==============//
function validate_recover_email() {
    var mail_exp = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
    var error = false;

    if (document.getElementById('email_recover').value.length === 0) {
        document.getElementById('error_email_recover').innerHTML = "Tienes que introducir el email";
        error = true;
    } else {
        if (!mail_exp.test(document.getElementById('email_recover').value)) {
            document.getElementById('error_email_recover').innerHTML = "El formato del email es inválido";
            error = true;
        } else {
            document.getElementById('error_email_recover').innerHTML = "";
        }
    }

    if (error == true) {
        return 0;
    }
}

//============== LOAD RECOVER OTP MENU ==============//
function load_recover_otp(){
    var token_email = localStorage.getItem('token_email');
    localStorage.removeItem('token_email');

    ajaxPromise(friendlyURL('?module=login'), 'POST', 'JSON', { 'op': 'verify_token', 'token_email': token_email })
    .then(function(data) {

        if (data.msg == "verify") {
            // console.log(data);
            key_recover_otp(data.uid);
            button_recover_otp(data.uid);
        } else if (data == "fail") {
            //SweetAlert2
            Swal.fire({
                // position: "top-end",
                icon: "error",
                title: "Verificación fallida",
                text: "El email de verificación ha caducado. Vuelva ha intentarlo",
                showConfirmButton: false,
                // confirmButtonColor: "#2eca6a",
                // timer: 3000
            });

            setTimeout(function(){window.location.href = friendlyURL('?module=login');}, 2500);
        }

    }).catch(function(textStatus) {
        if (console && console.log) {
            console.log("La solicitud ha fallado: " + textStatus);
        }
    });      
}

//============== ENTER RECOVER OTP ==============//
function key_recover_otp(uid) {
    $("#recover_otp").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            e.preventDefault();
            load_recover_password(uid);
        }
    });
}

//============== CLICK RECOVER OTP ==============//
function button_recover_otp(uid) {
    $('#recover_otp').on('click', function(e) {
        e.preventDefault();
        load_recover_password(uid);
    });
}

//============== LOAD RECOVER PASSWORD MENU ==============//
function load_recover_password(uid){
    var otpForm = document.getElementById('otp_pass').value;
    
    ajaxPromise(friendlyURL('?module=login'), 'POST', 'JSON', { 'op': 'verify_otp', 'uid': uid, 'otp': otpForm })
    .then(function(data) {
        console.log(data);

        if (data.msg == "verify") {
            // console.log(data);
            $('#otpPass_container').hide();
            $('#recoverPass_container').show();

            key_recover_pass(data.uid);
            button_recover_pass(data.uid); 
        } else if (data == "fail_expiredToken") {
            //SweetAlert2
            Swal.fire({
                // position: "top-end",
                icon: "error",
                title: "Verificación fallida",
                text: "El código de autenticación ha caducado. Vuelva ha intentarlo",
                showConfirmButton: false,
                // confirmButtonColor: "#2eca6a",
                // timer: 3000
            });

            setTimeout(function(){window.location.href = friendlyURL('?module=login');}, 2500);
        } else if (data == "fail_OTP") {
            //SweetAlert2
            Swal.fire({
                // position: "top-end",
                icon: "error",
                title: "Verificación fallida",
                text: "Exceso de intentos de autenticación o el tiempo máximo ha expirado. Vuelva ha intentarlo",
                showConfirmButton: false,
                // confirmButtonColor: "#2eca6a",
                // timer: 3000
            });

            setTimeout(function(){window.location.href = friendlyURL('?module=login');}, 2500);
        } else {
            otp_attempts = 2 - data;
            document.getElementById('error_otp_pass').innerHTML = `El código introducido no es válido.<br> Te quedan ${otp_attempts} intentos.`
        }

    }).catch(function(textStatus) {
        if (console && console.log) {
            console.log("La solicitud ha fallado: " + textStatus);
        }
    });      
}

//============== ENTER RECOVER PASSWORD ==============//
function key_recover_pass(uid) {
    $("#recover_pass").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            e.preventDefault();
            send_new_password(uid);
        }
    });
}

//============== CLICK RECOVER PASSWORD ==============//
function button_recover_pass(uid) {
    $('#recover_pass').on('click', function(e) {
        e.preventDefault();
        send_new_password(uid);
    });
}

//============== SEND NEW PASSWORD ==============//
function send_new_password(uid){
    if(validate_new_password() != 0){
        var pswdForm = document.getElementById('passwd1_recover').value;
        // console.log([pswdForm, token_email]);
        // return;

        ajaxPromise(friendlyURL('?module=login'), 'POST', 'JSON', { 'op': 'new_password', 'uid': uid, 'new_pass': pswdForm })
            .then(function(data) {
                if(data == "done"){
                    //SweetAlert2
                    Swal.fire({
                        // position: "top-end",
                        icon: "success",
                        title: "Nueva contraseña guardada",
                        text: "Puedes acceder a tu cuenta con tu nueva contraseña",
                        showConfirmButton: false,
                        // confirmButtonColor: "#2eca6a",
                        // timer: 3000
                    });

                    setTimeout(function(){window.location.href = friendlyURL('?module=login');}, 2500);
                } else {
                    //SweetAlert2
                    Swal.fire({
                        // position: "top-end",
                        icon: "error",
                        title: "Error actualizando la contraseña",
                        text: "Se ha producido un error en la actualización. Vuelva a intentarlo",
                        showConfirmButton: false,
                        // confirmButtonColor: "#2eca6a",
                        timer: 2500
                    })
                }
            }).catch(function(textStatus) {
                if (console && console.log) {
                    console.log("La solicitud ha fallado: " + textStatus);
                }
            });    
    }
}

//============== VALIDATE NEW PASSWORD ==============//
function validate_new_password() {
    var passwd_exp = /^(?=.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/;
    var error = false;

    if (document.getElementById('passwd1_recover').value.length === 0) {
        document.getElementById('error_passwd1_recover').innerHTML = "Tienes que introducir la contraseña";
        error = true;
    } else {
        if (document.getElementById('passwd1_recover').value.length < 8) {
            document.getElementById('error_passwd1_recover').innerHTML = "La contraseña tiene que tener 8 caracteres como mínimo";
            error = true;
        } else {
            if (!passwd_exp.test(document.getElementById('passwd1_recover').value)) {
                document.getElementById('error_passwd1_recover').innerHTML = "La contraseña debe contener mínimo 8 caracteres, mayusculas, minusculas y simbolos especiales";
                error = true;
            } else {
                document.getElementById('error_passwd1_recover').innerHTML = "";
            }
        }
    }

    if (document.getElementById('passwd2_recover').value.length === 0) {
        document.getElementById('error_passwd2_recover').innerHTML = "Tienes que repetir la contraseña";
        error = true;
    } else {
        if (document.getElementById('passwd2_recover').value.length < 8) {
            document.getElementById('error_passwd2_recover').innerHTML = "La contraseña tiene que tener 8 caracteres como mínimo";
            error = true;
        } else {
            if (document.getElementById('passwd2_recover').value === document.getElementById('passwd1_recover').value) {
                document.getElementById('error_passwd2_recover').innerHTML = "";
            } else {
                document.getElementById('error_passwd2_recover').innerHTML = "Las contraseñas no coinciden";
                error = true;
            }
        }
    }

    if (error == true) {
        return 0;
    }
}

//============== TO RECOVER EMAIL ==============//
function button_toRecover() {
    $('#to_recover').on('click', function() {
        $('#login_container').hide();
        $('#register_container').hide();
        $('#recoverEmail_container').show();
        $('#otp_container').hide();
    });
}

$(document).ready(function() {
    key_recover_email();
    button_recover_email();
    button_toRecover();
});