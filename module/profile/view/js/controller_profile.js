function loadProfile_user() {
    localStorage.setItem("location", "profile");
    var accessToken = localStorage.getItem('access_token')

    $('.profile_updUser').hide();

    $('.icon_profile_nav-item.account')
        .css({
            "filter": "none",
        });

    ajaxPromise(friendlyURL('?module=profile'), 'POST', 'JSON', { 'op': 'loadProfile_user', 'token': accessToken })
        .then(function(data) {
            console.log(data);
            // return;

            // botón de actualizar profile solo disponible para usuarios locales
            if (data.provider != "local") {
                $('#toUpdate_user').remove();
            }

            $('<div></div>').appendTo('.profileAccount_info')
                .html(`
                    <p>
                        <span class="info_header">Nombre:</span>
                        <span class="info">${data.username}</span>
                    </p>
                    <p>
                        <span class="info_header">Email:</span>
                        <span class="info">${data.email}</span>
                    </p>
                    <p>
                        <span class="info_header">Teléfono:</span>
                        <span class="info">${data.phone}</span>
                    </p>`
                );
            
            var splitAvatar = data.avatar.split("://");
            if (splitAvatar[0] == 'https' || splitAvatar[0] == 'http') {
                $('<img>').attr('class', 'avatar').attr('src', data.avatar).appendTo('.profile_avatar');
            } else {
                $('<img>').attr('class', 'avatar').attr('src', IMG_AVATAR_PATH + data.avatar).appendTo('.profile_avatar');
            }

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
            $('.profile_updUser').hide();
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

function load_likes() {
    var accessToken = localStorage.getItem('access_token')
    ajaxPromise(friendlyURL('?module=profile'), 'POST', 'JSON', { 'op': 'load_likes', 'token': accessToken })
        .then(function(data) {
            console.log(data);   
            // return;

            $('.profile_account').hide();
            $('.profile_updUser').hide();
            $('.profile_purchases').hide();
            $('.profile_likes').hide();

            $('.icon_profile_nav-item.account')
                .css({
                    "filter": "hue-rotate(0deg) saturate(0%) brightness(0%)",
                });
            $('.icon_profile_nav-item.purchases')
                .css({
                    "filter": "hue-rotate(0deg) saturate(0%) brightness(0%)",
                });
            $('.icon_profile_nav-item.likes')
                .css({
                    "filter": "none",
                });

            $('<div></div>').attr('class', 'profile_likes').appendTo('#profile')
                .html(`
                    <h3 class='text-brand'>Mis likes</h3>`
                );

            if (data == "no_likes") {

                $('<p></p>').attr('class', 'without_likes').appendTo('.profile_likes').html('Sin likes');

            } else {
                // Bucle para cada una de las viviendas    
                for (row in data) {
                    $('<div></div>').attr('class', 'profile_realestates').attr('id', data[row].id_realestate).appendTo('.profile_likes')
                        .html(`
                            <div class='containerList_img'>
                                <div id='list-carousel-${data[row].id_realestate}' class='swiper'>
                                    <div class='container_listCarousel swiper-wrapper'></div>
                                    <div class='swiper-button-prev'></div>
                                    <div class='swiper-button-next'></div>
                                </div>
                            </div>
                            <div id='${data[row].id_realestate}' class='containerList_info more_info'>
                                <div class='listInfo_header'>
                                    <span id='${data[row].id_realestate}' class='listInfo_title toDetails'>${data[row].name_type} en ${data[row].name_city}</span>
                                    <div class='listHeader_icons_container'>
                                        <div id='${data[row].id_realestate}' class='listCart_container'>
                                            <img src='${IMG_ICONS_PATH}cart1.png' id='${data[row].id_realestate}' class='listCart_icon'>
                                        </div>
                                        <div id='${data[row].id_realestate}' class='profileLike_container' like='${data[row].like}'>
                                            <img src='${IMG_ICONS_PATH}eliminar.png' id='${data[row].id_realestate}' class='delLike_icon'>
                                        </div>
                                    </div>
                                </div>
                                <div id='${data[row].id_realestate}' class='toDetails'>
                                    <div class='listInfo_trading'>
                                        <span class='listInfo_price'>${new Intl.NumberFormat("es-ES").format(data[row].price)} €&nbsp;&nbsp;|&nbsp;&nbsp;${data[row].name_op}</span>
                                    </div>
                                    <div class='listInfo_specs'>
                                        <div class="listInfoSpecs_contents">
                                            <img src='${IMG_SPECS_PATH}area.png' class='listInfoSpecs-img'>
                                            <span class='listInfoSpecs-txt'>
                                                ${data[row].area} m<sup>2</sup>
                                            </span>
                                        </div>
                                        ${(data[row].rooms != 0 ? (`
                                            <div class="listInfoSpecs_contents">
                                                <img src='${IMG_SPECS_PATH}rooms.png' class='listInfoSpecs-img'>
                                                <span class='listInfoSpecs-txt'>
                                                ${data[row].rooms} habs.
                                                </span>
                                            </div>
                                        `) : "")}
                                        ${(data[row].bathrooms != 0 ? (`
                                            <div class="listInfoSpecs_contents">
                                                <img src='${IMG_SPECS_PATH}bathrooms.png' class='listInfoSpecs-img'>
                                                <span class='listInfoSpecs-txt'>
                                                    ${data[row].bathrooms} baños
                                                </span>
                                            </div>
                                        `) : "")}
                                        ${(data[row].floor != 0 ? (`
                                            <div class="listInfoSpecs_contents">
                                                <img src='${IMG_SPECS_PATH}floor.png' class='listInfoSpecs-img'>
                                                <span class='listInfoSpecs-txt'>
                                                    ${data[row].floor}
                                                </span>
                                            </div>
                                        `) : "")}
                                    </div>
                                    <p class='listInfo_desc'>${data[row].description}</p>
                                </div>
                            </div>`
                        );

                    // Recorremos las imágenes de cada vivienda y las agregamos al carrusel
                    // Para apuntar al div donde creamos los slides, indicamos div del carrousel + div slide
                    for (img in data[row].img_realestate) {
                        $('<div></div>').attr('class', 'carousel-item-c swiper-slide').attr('id', data[row].img_realestate[img]).appendTo(`#list-carousel-${data[row].id_realestate} .container_listCarousel`)
                            .html(`
                                <div class='card-box-b card-shadow news-box'>
                                    <div class='img-box-b'>
                                        <img src='${IMG_REALESTATES_PATH + data[row].img_realestate[img]}' alt='' class='img-b img-fluid'>
                                    </div>
                                </div>`
                            );
                    }

                    // Inicializar el carrusel después de agregar todas las imágenes
                    // El div del carrousel necesita un id único
                    new Swiper(`#list-carousel-${data[row].id_realestate}`, {
                        speed: 600,
                        loop: true,
                        slidesPerView: 1,
                        navigation: {
                            prevEl: '.swiper-button-prev',
                            nextEl: '.swiper-button-next',
                        },
                    });
                }
            }
            
        }).catch(function() {
            localStorage.setItem('count', 0);
            localStorage.removeItem('filters_home'); // eliminamos de localStorage filters_home para no interferir en próximas busquedas
            $('<div></div>').attr('class', 'intro-single3').appendTo('.list_realestates')
                .html(`
                    <div class='container'>
                        <div class='row'>
                            <div class='col-md-12 col-lg-8'>
                                <div class='title-single-box'>
                                    <h1 class='title-single'>Sin resultados</h1>
                                    <span class='color-text-a'>¡No se encontaron resultados con los filtros aplicados!</span>
                                </div>
                            </div>
                        </div>
                    </div>`
                )
        });
}

function delete_like(id_realestate) {
    var accessToken = localStorage.getItem('access_token')

    ajaxPromise(friendlyURL('?module=profile'), 'POST', 'JSON', { 'op': 'delete_like', 'token': accessToken, 'id_realestate': id_realestate })
        .then(function(data) {
            console.log(data);
            // return;

            $(`#profile #${data}.profile_realestates`).remove();

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
    // muestra MI CUENTA
    $('.btn_profile_nav-item.account').on('click', function() {
        $('.profile_account').show();
        $('.profile_updUser').hide();
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

    $('.btn_profile_nav-item.likes').on('click', function() {
        load_likes()
    });

    // muestra ACTUALIZA TUS DATOS
    $('#to_updUser').on('click', function() {
        $('.profile_account').hide();
        $('.profile_updUser').show();
    });

    // clic para dirigir a eliminar like
    $(document).on("click", ".profileLike_container", function() {
        var id_realestate = this.getAttribute('id');
        delete_like(id_realestate);
    });

    // redirecciona a details
    $(document).on("click", ".toDetails", function() {
        localStorage.removeItem('redirect_details');
        localStorage.setItem('redirect_details', this.getAttribute('id'));
        setTimeout(function(){ 
            window.location.href = friendlyURL('?module=shop');
        }, 500);
    });
}

function update_user() {
    $("#uploadForm").on('submit', function (e) {
        e.preventDefault(); // salta la acción predefinida en este caso del submit para realizar la acción definida

        var fileInput = $('#file')[0];
        var file = fileInput.files[0];
        
        if (validate_form(file) != 0) {
            var formData = new FormData(this);
    
            var accessToken = localStorage.getItem('access_token')
            var whitespace_phoneForm = $('#phone_prf').val()
            var phoneForm = whitespace_phoneForm.replace(/ /g, ""); // quita los espacios en blanco
    
            // Agregar otros datos al FormData
            formData.append('token', accessToken);
            formData.append('username', $('#username_prf').val());
            formData.append('email', $('#email_prf').val());
            formData.append('phone', phoneForm);
    
            $.ajax({
                type: 'POST',
                url: friendlyURL('?module=profile&op=update_user'),
                data: formData,
                contentType: false,
                processData: false,
                success: function(response){
                    console.log(response);

                    if (response == "done") {
                        //SweetAlert2
                        Swal.fire({
                            // position: "top-end",
                            icon: "success",
                            title: "Tu información ha sido actualizada",
                            // text: "Your work has been saved",
                            showConfirmButton: false,
                            // timer: 1500
                        });

                        setTimeout(function(){window.location.href = friendlyURL('?module=profile');}, 1500);
                    } else if (response == "error_update") {
                        //SweetAlert2
                        Swal.fire({
                            // position: "top-end",
                            icon: "error",
                            title: "Error en la actualización",
                            text: "Inténtelo de nuevo",
                            showConfirmButton: false,
                            // timer: 1500
                        });
                    } else if (response == "error_upload") {
                        //SweetAlert2
                        Swal.fire({
                            // position: "top-end",
                            icon: "error",
                            title: "Error al subir la imagen",
                            text: "Inténtelo de nuevo",
                            showConfirmButton: false,
                            // timer: 1500
                        });
                    } else if (response == "error_ext") {
                        $('#error_file').html('<p>Tipo de archivo no permitido. Solo se permiten .jpg, .jpeg, .png, .gif</p>');
                    } else if (response == "error_size") {
                        $('#error_file').html('<p>El archivo es demasiado grande. El tamaño máximo es de 1MB</p>');
                    } else if (response == "error_noFile") {
                        $('#error_file').html('<p>Por favor, seleccione un archivo para subir</p>');
                    } else if (response == "error_user") {
                        $('#error_username_prf').html('<p>El nombre de usuario ya existe. Introduce otro</p>');
                    } else if (response == "error_email") {
                        $('#error_email_prf').html('<p>El email ya existe. Introduce otro.</p>');
                    }
                },
                error: function(){
                    // console.log(response);
                    $('#error_file').html('<p>Error al subir el archivo</p>');
                }
            });
        }
    });
}

function validate_form(file) {
    // expresión ?=. buscará en la cadena almenos una condición de la expresión regular sin avanzar en la cadena de entrada
    // expresión regular * cero o mas carácteres 
    // expresión regular + uno o mas carácteres
    // expresión regular . cualquier carácter excepto salto de linea
    // expresión regualar \W carácter no alfanumérico
    var username_exp = /^(?=.{5,}$)(?=.*[a-zA-Z0-9]).*$/;
    var mail_exp = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
    var phone_exp = /^\+[0-9]{2} [0-9]{3} [0-9]{2} [0-9]{2} [0-9]{2}$/;

    // Restricciones de extensión y tamaño de archivo
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
    var maxSize = 1 * 1024 * 1024; // 1MB

    var error = false;

    if ($('#username_prf').val().length != 0) {
        if ($('#username_prf').val().length < 5) {
            $('#error_username_prf').html('<p>El usuario tiene que tener 5 caracteres como mínimo</p>');
            error = true;
        } else {
            if (!username_exp.test($('#username_prf').val())) { // test() prueba coincidencia en la cadena. Devuelve true o false.
                $('#error_username_prf').html('<p>El usuario no puede contener caracteres especiales</p>');
                error = true;
            } else {
                $('#error_username_prf').html('<p></p>');
            }
        }
    }

    if ($('#email_prf').val().length === 0) {
        $('#error_email_prf').html('<p>Tienes que introducir el email</p>');
        error = true;
    } else {
        if (!mail_exp.test($('#email_prf').val())) {
            $('#error_email_prf').html('<p>El formato del email es inválido</p>');
            error = true;
        } else {
            $('#error_email_prf').html('<p></p>');
        }
    }

    if ($('#phone_prf').val().length === 0) {
        $('#error_phone_prf').html('<p>Tienes que introducir el teléfono</p>');
        error = true;
    } else {
        if (!phone_exp.test($('#phone_prf').val())) {
            $('#error_phone_prf').html('<p>El formato del teléfono es inválido. Ejemplo +12 123 45 67 89</p>');
            error = true;
        } else {
            $('#error_phone_prf').html('<p></p>');
        }
    }

    if (!file) {
        $('#error_file').html('<p>Por favor, seleccione un archivo para subir</p>');
        error = true;
    } else {
        if(!allowedExtensions.exec(file.name)){
            $('#error_file').html('<p>Tipo de archivo no permitido. Solo se permiten .jpg, .jpeg, .png, .gif</p>');
            error = true;
        } else if(file.size > maxSize){
            $('#error_file').html('<p>El archivo es demasiado grande. El tamaño máximo es de 1MB</p>');
            error = true;
        } else {
            $('#error_file').html('<p></p>');
        }
        
    }

    if (error == true) {
        return 0;
    }
}

$(document).ready(function() {
    loadProfile_user();
    clicks();
    update_user();
});