// Controla el tiempo de expiración de los tokens, y que el username de los tokens y la cookie coincida, cada vez que accedemos a un controlador
function control_user() {
    var accessToken = localStorage.getItem('access_token');
    var refreshToken = localStorage.getItem('refresh_token');

    if (accessToken && refreshToken) {
        ajaxPromise(friendlyURL('?module=login'), 'POST', 'JSON', { 'op': 'control_user', 'accessToken': accessToken, 'refreshToken': refreshToken })
            .then(function(data) {
                // console.log(data);
                // return;
                
                if (data == "Correct_User") {
                    console.log("CORRECTO -> El usuario coincide con la session");
                } else if (data == "Wrong_User") {
                    console.log("INCORRECTO -> Estan intentando acceder a la cuenta");
                    logout_auto();
                } else if (data == "ExpirationTime_Token") {
                    console.log("INCORRECTO -> El token ha expirado");
                    logout_auto();
                } else {
                    console.log("Update accessToken correctly");
                    localStorage.setItem("access_token", data);
                    load_menu();
                }
            }).catch(function() {
                console.log("ANONYMOUS user");
            });
    } else {
        console.log("No hay usuario logeado");
    }
}

// Controla la inactividad del usuario. Al cabo de un tiempo especificado de inactividad, deslogea al usuario a través de la información de sesión (cookie)
function control_activity() {
    var token = localStorage.getItem('refresh_token');
    if (token) {
        ajaxPromise(friendlyURL('?module=login'), 'POST', 'JSON', { 'op': 'control_activity' })
            .then(function(response) {
                if (response == "inactivo") {
                    console.log("usuario INACTIVO");
                    logout_auto();
                } else {
                    console.log("usuario ACTIVO")
                }
            });
    } else {
        console.log("No hay usuario logeado");
    }
}

// Limita el tiempo de uso de la cookie en caso de robo
function refresh_cookie() {
    ajaxPromise(friendlyURL('?module=login'), 'POST', 'JSON', { 'op': 'refresh_cookie' })
        .then(function(response) {
            console.log(response);
        });
}                                                                     

function logout_auto() {
    ajaxPromise(friendlyURL('?module=login'), 'POST', 'JSON', { 'op': 'logout' })
        .then(function(data) {
            console.log(data);
            // return;

            localStorage.removeItem('access_token');
            localStorage.removeItem('refresh_token');
            //SweetAlert2
            Swal.fire({
                // position: "top-end",
                icon: "error",
                // title: "Your work has been saved",
                text: "Sesión finalizada por seguridad",
                showConfirmButton: false,
                timer: 1250
                });
            setTimeout(function(){window.location.href = friendlyURL('?module=home');}, 1500); // redirigimos al home
        }).catch(function() {
            console.log('Something has occured');
        }); 
}

$(document).ready(function() {
    control_user();
    setInterval(function() { control_activity() }, 10*60*1000); //10min= 600000, una tercera parte del tiempo màximo de inactividad
    setInterval(function() { refresh_cookie() }, 10*60*1000); //10min= 600000
});