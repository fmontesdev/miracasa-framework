function load_cart() {
    localStorage.setItem("location", "cart");
    var accessToken = localStorage.getItem('access_token')
    ajaxPromise(friendlyURL('?module=cart'), 'POST', 'JSON', { 'op': 'select_cart', 'token': accessToken })
        .then(function(data) {
            console.log(data);
            // return;

            if (data == "no_cart") {
                $('<div></div>').attr('class', 'intro-single2').appendTo('#cart')
                .html(`
                    <div class='container'>
                        <div class='row'>
                            <div class='col-md-12 col-lg-8'>
                                <div class='title-single-box'>
                                    <h1 class='title-single'>Tu cesta está vacia</h1>
                                    <span class='color-text-a'>Sigue explorando los inmuebles que hemos preparado para ti</span>
                                </div>
                            </div>
                        </div>
                    </div>`
                );

                // actualiza contador carrito en menu
                $('.navbarCart_container .qty_cart').remove();
                localStorage.removeItem("cart_qty");
            } else {
                $('<div></div>').attr('class', 'containerCart_title').appendTo('#cart')
                    .html(`
                        <h3 class="text-brand">Cesta de la compra</h3>`
                    );
                $('<div></div>').attr('class', 'containerCart').appendTo('#cart');

                var products = 0;
                var total = 0;
                for (row in data) {
                    if (data[row].quantity > data[row].stock) {
                        data[row].quantity = 0;
                    }
                    products = products + parseInt(data[row].quantity, 10);
                    var subtotal = data[row].quantity * data[row].price;
                    total = total + subtotal;

                    $('<div></div>').attr('id', data[row].id_realestate).attr('class', 'containerCart_item').appendTo('.containerCart')
                        .html(`
                            <div id='${data[row].id_realestate}' class='containerCart_img toDetails'>
                                <img src='${IMG_REALESTATES_PATH + data[row].img_realestate}' id='${data[row].id_realestate}' class='img-fluid'>
                            </div>
                            <div id='${data[row].id_realestate}' class='containerCart_info'>
                                <div class='cartInfo_title'>
                                    <span id='${data[row].id_realestate}' class='cartInfoTitle_property toDetails'>${data[row].name_op} de ${data[row].name_type} en ${data[row].name_city}</span>
                                    <br>
                                    <span class='cartInfoTitle_province'>${data[row].province}</span>
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
                                <div id='${data[row].id_realestate}' class='containerCart_purchase'>
                                    <span class='cartPurchase_txt'>Cantidad:</span>
                                    <div class='cartPurchase_qty'>
                                        <button class='btn' onclick='update_cart(${data[row].id_realestate}, ${data[row].quantity}, -1)'>-</button>
                                        ${(data[row].stock > 0 ? (`
                                            <input class='quantity' value='${data[row].quantity}'>
                                        `) : (`
                                            <input class='quantity' value='0'>
                                        `) )}
                                        <button class='btn' onclick='update_cart(${data[row].id_realestate}, ${data[row].quantity}, +1)'>+</button>
                                    </div>
                                    <div class='cartPurchase_delete'>
                                        <img src='${IMG_ICONS_PATH}eliminar.png' alt='Eliminar producto' onclick='update_cart(${data[row].id_realestate}, ${data[row].quantity}, "delete")'>
                                    </div>
                                    <div class='cartPurchase_stock'>
                                        ${(data[row].stock > 0 ? (`
                                            <span class='stock'>En stock</span>
                                        `) : (`
                                            <span class='outStock'>No disponible</span>
                                        `) )}
                                    </div>
                                    <div class='cartPurchase_price'>
                                        <span id='${data[row].id_realestate}' class='subtotal'>${new Intl.NumberFormat("es-ES").format(subtotal)} €</span>
                                    </div>
                                </div>
                            </div>`
                        );
                }

                $('<div></div>').attr('class', 'containerCart_total').attr('id', data[row].id_realestate).appendTo('#cart')
                    .html(`
                        <div class='cartProducts'>
                            <span class='products_desc'>Productos:</span>
                            <span class='products_qty'>${products}</span>
                        </div>
                        <div class='cartTotal'>
                            <span class='cartTotal_desc'>Total importe:</span>
                            <span id='${data[row].uid}' class='cartTotal_imp'>${new Intl.NumberFormat("es-ES").format(total)} €</span>
                        </div>
                        <div class='purchase_btn'>
                            <button class="btn btn-c" onclick='billing()'>Realizar pedido</button>
                        </div>`
                    );
                
                // actualiza contador carrito en menu
                localStorage.setItem("cart_qty", products);
            } 
        }).catch(function(textStatus) {
            if (console && console.log) {
                console.log("La solicitud ha fallado: " + textStatus);
            }
        });
}

function update_cart(id_re, qty, operation) {
    // console.log(id_re, operation);
    // return;
    var accessToken = localStorage.getItem('access_token')
    ajaxPromise(friendlyURL('?module=cart'), 'POST', 'JSON', { 'op': 'update_cart', 'id_re': id_re, 'token': accessToken, 'qty': qty, 'operation': operation })
        .then(function(data) {
            console.log(data);
            // return;

            if (data[1] == "update") {

                $('.containerCart_purchase').remove();
                $('.containerCart_total').remove();

                var products = 0;
                var total = 0;
                for (row in data[0]) {
                    if (data[0][row].quantity > data[0][row].stock) {
                        data[0][row].quantity = 0;
                    }
                    products = products + parseInt(data[0][row].quantity, 10);
                    var subtotal = data[0][row].quantity * data[0][row].price;
                    total = total + subtotal;

                    $('<div></div>').attr('class', 'containerCart_purchase').attr('id', data[0][row].id_realestate).appendTo(`#${data[0][row].id_realestate}.containerCart_info`)
                        .html(`
                            <span class='cartPurchase_txt'>Cantidad:</span>
                            <div class='cartPurchase_qty'>
                                <button class='btn' onclick='update_cart(${data[0][row].id_realestate}, ${data[0][row].quantity}, -1)'>-</button>
                                ${(data[0][row].stock > 0 ? (`
                                    <input class='quantity' value='${data[0][row].quantity}'>
                                `) : (`
                                    <input class='quantity' value='0'>
                                `) )}
                                <button class='btn' onclick='update_cart(${data[0][row].id_realestate}, ${data[0][row].quantity}, +1)'>+</button>
                            </div>
                            <div class='cartPurchase_delete'>
                                <img src='${IMG_ICONS_PATH}eliminar.png' alt='Eliminar producto' onclick='update_cart(${data[0][row].id_realestate}, ${data[0][row].quantity}, "delete")'>
                            </div>
                            <div class='cartPurchase_stock'>
                                ${(data[0][row].stock > 0 ? (`
                                    <span class='stock'>En stock</span>
                                `) : (`
                                    <span class='outStock'>No disponible</span>
                                `) )}
                            </div>
                            <div class='cartPurchase_price'>
                                <span id='${data[0][row].id_realestate}' class='subtotal'>${new Intl.NumberFormat("es-ES").format(subtotal)} €</span>
                            </div>`
                        );
                }
                
                $('<div></div>').attr('class', 'containerCart_total').attr('id', data[0][row].id_realestate).appendTo('#cart')
                    .html(`
                        <div class='cartProducts'>
                            <span class='products_desc'>Productos:</span>
                            <span class='products_qty'>${products}</span>
                        </div>
                        <div class='cartTotal'>
                            <span class='cartTotal_desc'>Total importe:</span>
                            <span id='${data[0][row].uid}' class='cartTotal_imp'>${new Intl.NumberFormat("es-ES").format(total)} €</span>
                        </div>
                        <div class='purchase_btn'>
                            <button class="btn btn-c" onclick='billing()'>Realizar pedido</button>
                        </div>`
                    );
                
                // actualiza contador carrito en menu
                $('.navbarCart_container .qty_cart').remove();
                $('<span></span>').attr('class', 'qty_cart').appendTo('.navbarCart_container').html(products);  
                localStorage.setItem("cart_qty", products);

            } else if (data[1] == "delete") {

                $(`#${data[2]}.containerCart_item`).remove();
                $('.containerCart_total').remove();

                var products = 0;
                var total = 0;
                for (row in data[0]) {
                    if (data[0][row].quantity > data[0][row].stock) {
                        data[0][row].quantity = 0;
                    }
                    products = products + parseInt(data[0][row].quantity, 10);
                    var subtotal = data[0][row].quantity * data[0][row].price;
                    total = total + subtotal;
                }
                
                $('<div></div>').attr('class', 'containerCart_total').attr('id', data[0][row].id_realestate).appendTo('#cart')
                    .html(`
                        <div class='cartProducts'>
                            <span class='products_desc'>Productos:</span>
                            <span class='products_qty'>${products}</span>
                        </div>
                        <div class='cartTotal'>
                            <span class='cartTotal_desc'>Total importe:</span>
                            <span id='${data[0][row].uid}' class='cartTotal_imp'>${new Intl.NumberFormat("es-ES").format(total)} €</span>
                        </div>
                        <div class='purchase_btn'>
                            <button class="btn btn-c" onclick='billing()'>Realizar pedido</button>
                        </div>`
                    );
                
                // actualiza contador carrito en menu
                $('.navbarCart_container .qty_cart').remove();
                $('<span></span>').attr('class', 'qty_cart').appendTo('.navbarCart_container').html(products); 
                localStorage.setItem("cart_qty", products);

            } else if (data == "no_cart") {

                $('.containerCart_title').remove();
                $('.containerCart').remove();
                $('.containerCart_total').remove();

                $('<div></div>').attr('class', 'intro-single2').appendTo('#cart')
                .html(`
                    <div class='container'>
                        <div class='row'>
                            <div class='col-md-12 col-lg-8'>
                                <div class='title-single-box'>
                                    <h1 class='title-single'>Tu cesta está vacia</h1>
                                    <span class='color-text-a'>Sigue explorando los inmuebles que hemos preparado para ti</span>
                                </div>
                            </div>
                        </div>
                    </div>`
                );
                
                // actualiza contador carrito en menu
                $('.navbarCart_container .qty_cart').remove();
                localStorage.removeItem("cart_qty");
            }
        }).catch(function(textStatus) {
            if (console && console.log) {
                console.log("La solicitud ha fallado: " + textStatus);
            }
        });
}

function billing() {
    // console.log(id_re, operation);
    // return;
    var accessToken = localStorage.getItem('access_token')
    ajaxPromise(friendlyURL('?module=cart'), 'POST', 'JSON', { 'op': 'billing', 'token': accessToken })
        .then(function(data) {
            console.log(data);
            // return;

            $('.containerCart_title').remove();
                $('.containerCart').remove();
                $('.containerCart_total').remove();

                $('<div></div>').attr('class', 'intro-single2').appendTo('#cart')
                .html(`
                    <div class='container'>
                        <div class='row'>
                            <div class='col-md-12 col-lg-8'>
                                <div class='title-single-box'>
                                    <h1 class='title-single'>Explora nuestra selección</h1>
                                    <span class='color-text-a'>Sigue explorando los inmuebles que hemos preparado para ti</span>
                                </div>
                            </div>
                        </div>
                    </div>`
                );

            // actualiza contador carrito en menu
            $('.navbarCart_container .qty_cart').remove();
            localStorage.removeItem("cart_qty");

            //SweetAlert2
            Swal.fire({
                // position: "top-end",
                icon: "success",
                title: "Compra realizada",
                // text: "Has añadido la cantidad máxima disponible",
                showConfirmButton: false,
                timer: 1500
            });
            
            setTimeout(function(){ 
                window.location.href = friendlyURL('?module=shop');
            }, 4500);

        }).catch(function(textStatus) {
            if (console && console.log) {
                console.log("La solicitud ha fallado: " + textStatus);
            }
        });
}

function clicks() {
    $(document).on("click", ".toDetails", function() {
        localStorage.removeItem('redirect_details');
        localStorage.setItem('redirect_details', this.getAttribute('id'));
        setTimeout(function(){ 
            window.location.href = friendlyURL('?module=shop');
        }, 500);
    });
}

$(document).ready(function() {
    load_cart();
    clicks();
});