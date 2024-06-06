function loadProfile_user() {
    localStorage.setItem("location", "profile");
    var accessToken = localStorage.getItem('access_token')

    // Menú Profile
    $('<div></div>').attr('class', 'profile_navigation').appendTo('#profile')
        .html(`
            <nav class="profile_navbar">
                <ul class="profile_nav-list">
                    <li class="profile_nav-item">
                        <button class='btn_profile_nav-item account'>
                            <img class='icon_profile_nav-item account' src='${IMG_ICONS_PATH + 'user1.png'}' alt='Mi cuenta'>
                            Mi cuenta
                        </button>
                    </li>
                    <li class="profile_nav-item">
                        <button id='XXXPPP'class='btn_profile_nav-item purchases'>
                            <img class='icon_profile_nav-item purchases' src='${IMG_ICONS_PATH + 'purchase_profile1.png'}' alt='Mis compras'>
                            Mis compras
                        </button>
                    </li>
                    <li class="profile_nav-item">
                        <button class='btn_profile_nav-item likes'>
                            <img class='icon_profile_nav-item likes' src='${IMG_ICONS_PATH + 'like_profile1.png'}' alt='Mis likes'>
                            Mis likes
                        </button>
                    </li>
                </ul>
            </nav>`
        );

    $('.icon_profile_nav-item.account')
        .css({
            "filter": "none",
        });

    ajaxPromise(friendlyURL('?module=profile'), 'POST', 'JSON', { 'op': 'loadProfile_user', 'token': accessToken })
        .then(function(data) {
            console.log(data);
            // return;

            $('<div></div>').attr('class', 'profile_account').appendTo('#profile')
                .html(`
                    <h3 class='text-brand'>Mi cuenta</h3>
                    <p>
                        <span class="info_header">Nombre:</span>
                        <span class="info">${data.username}</span>
                    </p>
                    <p>
                        <span class="info_header">Email:</span>
                        <span class="info">${data.email}</span>
                    </p>`
                ); 
        }).catch(function(textStatus) {
            if (console && console.log) {
                console.log("La solicitud ha fallado: " + textStatus);
            }
        });
}

function load_bills() {
    var accessToken = localStorage.getItem('access_token')
    ajaxPromise(friendlyURL('?module=profile'), 'POST', 'JSON', { 'op': 'load_bills', 'token': accessToken })
        .then(function(data) {
            console.log(data);
            // return;

            $('.profile_account').hide();
            $('.profile_purchases').hide();
            $('.profile_likes').hide();

            $('.icon_profile_nav-item.account')
                .css({
                    "filter": "hue-rotate(0deg) saturate(0%) brightness(0%)",
                });
            $('.icon_profile_nav-item.purchases')
                .css({
                    "filter": "none",
                });
            $('.icon_profile_nav-item.likes')
                .css({
                    "filter": "hue-rotate(0deg) saturate(0%) brightness(0%)",
                });

            $('<div></div>').attr('class', 'profile_purchases').appendTo('#profile')
                .html(`
                    <h3 class='text-brand'>Mis compras</h3>`
                );

            if (data == "no_bills") {
                
                $('<p></p>').attr('class', 'unbilled').appendTo('.profile_purchases').html('Sin pedidos');

            } else {
                
                for (row in data) {
                    $('<div></div>').attr('id', data[row].id_bill).attr('class', 'bills').appendTo('.profile_purchases')
                        .html(`
                            <a class='item' href='javascript:load_qr(${data[row].id_bill})'>
                                <img class='icon' src='${IMG_ICONS_PATH + 'qrcode.png'}' alt='Código QR ${data[row].id_bill}'/>
                            </a>
                            <a class='item' href='${BILL_PDF_PATH + data[row].id_bill + '.pdf'}'>
                                <img class='icon' src='${IMG_ICONS_PATH + 'pdf.png'}' alt='Factura ${data[row].id_bill}'/>
                            </a>
                            <span class='txt'>Factura ${data[row].id_bill} / ${data[row].date}</span>`
                        );
                }
            } 
        }).catch(function(textStatus) {
            if (console && console.log) {
                console.log("La solicitud ha fallado: " + textStatus);
            }
        });
}

function load_qr(id_bill) {
    // console.log (BILL_PDF_PATH + id_bill + '.png')

    //SweetAlert2
    Swal.fire({
        imageUrl: `${BILL_QR_PATH + id_bill + '.png'}`,
        imageHeight: 250,
        imageAlt: `Código QR ${id_bill}`,
        // showConfirmButton: false,
        confirmButtonColor: "#2eca6a",
        width: '350px',
    });
}

function clicks() {
    $('.btn_profile_nav-item.account').on('click', function() {
        $('.profile_account').show();
        $('.profile_purchases').hide();
        $('.profile_likes').hide();

        $('.icon_profile_nav-item.account')
            .css({
                "filter": "none",
            });
        $('.icon_profile_nav-item.purchases')
            .css({
                "filter": "hue-rotate(0deg) saturate(0%) brightness(0%)",
            });
        $('.icon_profile_nav-item.likes')
            .css({
                "filter": "hue-rotate(0deg) saturate(0%) brightness(0%)",
            });
    });

    $('.btn_profile_nav-item.purchases').on('click', function() {
        load_bills()
    });
}

$(document).ready(function() {
    loadProfile_user();
    clicks();
});