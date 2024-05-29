function insert_cart(id_realestate) {
    var accessToken = localStorage.getItem('access_token') || null;
    if (accessToken != null) {
        ajaxPromise(friendlyURL('?module=cart'), 'POST', 'JSON', { 'op': 'insert_cart', 'id_re': id_realestate, 'token': accessToken })
            .then(function(data) {
                console.log(data);
                // return;

                if (data.msg == 'insert done' || data.msg == 'update done') {
                    //SweetAlert2
                    Swal.fire({
                        // position: "top-end",
                        // icon: "success",
                        title: "Añadido al carrito",
                        text: `${data.re.name_type} en ${data.re.name_city}`,
                        imageUrl: `${IMG_REALESTATES_PATH + data.re.img_realestate}`,
                        imageHeight: 250,
                        imageAlt: `${data.re.name_type} en ${data.re.name_city}`,
                        showConfirmButton: false,
                        timer: 2500
                    });
                } else if (data == 'insuficient stock') {
                    //SweetAlert2
                    Swal.fire({
                        // position: "top-end",
                        icon: "warning",
                        title: "Stock insuficiente",
                        text: "Has añadido la máxima cantidad disponible",
                        showConfirmButton: false,
                        timer: 2500
                    });
                }
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