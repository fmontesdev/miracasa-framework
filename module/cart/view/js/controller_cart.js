function load_cart() {
    localStorage.setItem("location", "cart");
    var accessToken = localStorage.getItem('access_token')
    ajaxPromise(friendlyURL('?module=cart'), 'POST', 'JSON', { 'op': 'select_cart', 'token': accessToken })
        .then(function(data) {
            console.log(data);
            // return;

            var total = 0;
            for (row in data) {
                var subtotal = data[row].quantity * data[row].price;
                total = total + subtotal;
                $('<div></div>').attr('class', 'containerCart').attr('id', data[row].uid).appendTo('#cart')
                    .html(`
                        <div id='${data[row].id_realestate}' class='containerCart_img toDetails'>
                            <img src='${IMG_REALESTATES_PATH + data[row].img_realestate}' id='${data[row].id_realestate}' class='img-fluid'>
                        </div>
                        <div id='${data[row].id_realestate}' class='containerCart_info'>
                            <div class='cartInfo_title'>
                                <p class='cartInfoTitle_property'>${data[row].name_op} de ${data[row].name_type} en ${data[row].name_city}</p>
                                <p class='cartInfoTitle_province'>${data[row].province}</p>
                            </div>
                            <div class='cartInfo_specs'>
                                <div class='cartInfoSpecs_contents'>
                                    <img src='${IMG_SPECS_PATH}area.png' class='cartInfoSpecs-img'>
                                    <span class='cartInfoSpecs-txt'>${data[0].area} m<sup>2</sup></span>
                                </div>
                                ${(data[row].rooms != 0 ? (`
                                    <div class='cartInfoSpecs_contents'>
                                        <img src='${IMG_SPECS_PATH}rooms.png' class='cartInfoSpecs-img'>
                                        <span class='cartInfoSpecs-txt'>${data[row].rooms} habs.</span>
                                    </div>
                                `) : "")}
                                ${(data[row].bathrooms != 0 ? (`
                                    <div class='cartInfoSpecs_contents'>
                                        <img src='${IMG_SPECS_PATH}bathrooms.png' class='cartInfoSpecs-img'>
                                        <span class='cartInfoSpecs-txt'>${data[row].bathrooms} baños</span>
                                    </div>
                                `) : "")}
                                ${(data[row].floor != 0 ? (`
                                    <div class='cartInfoSpecs_contents'>
                                        <img src='${IMG_SPECS_PATH}floor.png' class='cartInfoSpecs-img'>
                                        <span class='cartInfoSpecs-txt'>${data[row].floor}</span>
                                    </div>
                                `) : "")}
                            </div>
                            <div class='cartInfo_price'>
                                <span>Precio por unidad: </span>
                                <span class='unit_price'>${new Intl.NumberFormat("es-ES").format(data[row].price)} €</span>
                            </div>
                            <div class='containerCart_purchase'>
                                <span class='cartPurchase_txt'>Cantidad:</span>
                                <div class='cartPurchase_qty'>
                                    <button class='btn' onclick='update_cart("minus")'>-</button>
                                    <input class='quantity' value='${data[row].quantity}'>
                                    <button class='btn' onclick='update_cart("plus")'>+</button>
                                </div>
                                <div class='cartPurchase_delete'>
                                    <img src='${IMG_ICONS_PATH}eliminar.png' alt='Eliminar producto' onclick='update_cart("delete")'>
                                </div>
                                <div class='cartPurchase_stock'>
                                    <span class='cartPurchase_txt'>En stock</span>
                                </div>
                                <div class='cartPurchase_price'>
                                    <span>${new Intl.NumberFormat("es-ES").format(subtotal)} €</span>
                                </div>
                            </div>
                        </div>`
                    );
            }

            $('<div></div>').attr('class', 'containerCart_total').attr('id', data[row].id_realestate).appendTo('#cart')
                .html(`
                    <span class='cartTotal_desc'>Total:</span>
                    <span class='cartTotal_imp'>${new Intl.NumberFormat("es-ES").format(total)} €</span>`
                );
                
        }).catch(function(textStatus) {
            if (console && console.log) {
                console.log("La solicitud ha fallado: " + textStatus);
            }
        });

}

function update_cart(qty) {
    console.log(qty);
    return;
    ajaxPromise(friendlyURL('?module=cart'), 'POST', 'JSON', { 'op': 'select_cart', 'token': accessToken })
        .then(function(data) {
            console.log(data);
            // return;


        }).catch(function(textStatus) {
            if (console && console.log) {
                console.log("La solicitud ha fallado: " + textStatus);
            }
        });

}
// function clicks() {
//     $(document).on("click", ".listCart_container", function() {
//         var id_realestate = this.getAttribute('id');
//         insert_cart(id_realestate);
//     });

//     $(document).on("click", ".detailsCart_container", function() {
//         var id_realestate = this.getAttribute('id');
//         insert_cart(id_realestate);
//     });
// }

$(document).ready(function() {
    load_cart();
});