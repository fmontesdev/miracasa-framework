function load_bills() {
    localStorage.setItem("location", "profile");
    var accessToken = localStorage.getItem('access_token')
    ajaxPromise(friendlyURL('?module=profile'), 'POST', 'JSON', { 'op': 'load_bills', 'token': accessToken })
        .then(function(data) {
            console.log(data);
            // return;

            $('<div></div>').attr('class', 'containerProfile_info').appendTo('#profile')
                .html(`
                    <h3 class='text-brand'>Tu cuenta</h3>
                    <p>
                        <span class="info_header">Nombre:</span>
                        <span class="info">${data.user.username}</span>
                    </p>
                    <p>
                        <span class="info_header">Email:</span>
                        <span class="info">${data.user.email}</span>
                    </p>`
                );

            $('<div></div>').attr('class', 'containerProfile_billing').appendTo('#profile')
                .html(`
                    <h3 class='text-brand'>Tus pedidos</h3>`
                );

            if (data.bills == "no_bills") {
                
                $('<p></p>').attr('class', 'unbilled').appendTo('.containerProfile_billing').html('Sin pedidos');

            } else {
                
                for (row in data.bills) {
                    $('<div></div>').attr('id', data.bills[row].id_bill).attr('class', 'bills').appendTo('.containerProfile_billing')
                        .html(`
                            <a class='item' href='javascript:load_qr(${data.bills[row].id_bill})'>
                                <img class='icon' src='${IMG_ICONS_PATH + 'qrcode.png'}' alt='Código QR ${data.bills[row].id_bill}'/>
                                <span class='txt'>Código QR</span>
                            </a>
                            <a class='item' href='${BILL_PDF_PATH + data.bills[row].id_bill + '.pdf'}'>
                                <img class='icon' src='${IMG_ICONS_PATH + 'pdf.png'}' alt='Factura ${data.bills[row].id_bill}'/>
                                <span class='txt'>Factura ${data.bills[row].id_bill} / ${data.bills[row].date}</span>
                            </a>`
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
        confirmButtonColor: "#2eca6a",
        width: '350px',
    });
}

$(document).ready(function() {
    load_bills();
});