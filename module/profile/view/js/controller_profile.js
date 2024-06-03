function load_bills() {
    localStorage.setItem("location", "profile");
    var accessToken = localStorage.getItem('access_token')
    ajaxPromise(friendlyURL('?module=profile'), 'POST', 'JSON', { 'op': 'load_bills', 'token': accessToken })
        .then(function(data) {
            console.log(data);
            return;


        }).catch(function(textStatus) {
            if (console && console.log) {
                console.log("La solicitud ha fallado: " + textStatus);
            }
        });
}

$(document).ready(function() {
    load_bills();
});