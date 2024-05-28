function insert_cart(id_realestate) {
    var accessToken = localStorage.getItem('access_token') || null;
    if (accessToken != null) {
        ajaxPromise(friendlyURL('?module=cart'), 'POST', 'JSON', { 'op': 'insert_cart', 'id_re': id_realestate, 'token': accessToken })
            .then(function(data) {
                console.log(data);
                return;

            }).catch(function(textStatus) {
                if (console && console.log) {
                    console.log("La solicitud ha fallado: " + textStatus);
                }
            });
    } else {
        window.location.href=friendlyURL('?module=login');
    }
}

function click_cart() {
    $(document).on("click", ".listCart_container", function() {
        var id_realestate = this.getAttribute('id');
        insert_cart(id_realestate);
    });

    $(document).on("click", ".detailsCart_container", function() {
        var id_realestate = this.getAttribute('id');
        insert_cart(id_realestate);
    });
}

$(document).ready(function() {
    click_cart();
});