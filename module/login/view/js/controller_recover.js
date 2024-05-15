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
                        title: "Recuperación de cuenta",
                        text: "Email enviado",
                        showConfirmButton: false,
                        // confirmButtonColor: "#2eca6a",
                        // timer: 3000
                    });

                    setTimeout(function(){window.location.href = friendlyURL('?module=login');}, 3000);
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

//============== TO RECOVER EMAIL ==============//
function button_toRecover() {
    $('#to_recover').on('click', function() {
        $('#login_container').hide();
        $('#register_container').hide();
        $('#recoverEmail_container').show();
    });
}

$(document).ready(function() {
    key_recover_email();
    button_recover_email();
    button_toRecover();
});