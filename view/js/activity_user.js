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

$(document).ready(function() {
    control_user();
});