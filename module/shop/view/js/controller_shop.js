function loadAllRealestates(limit=4, offset=0) {
    // conseguimos accessToken desde localStorage para comprobar que hay un usuario logeado
    var accessToken = localStorage.getItem('access_token') || null;
    
    // con la página activa de la paginación definimos el offset
    var validate_page = localStorage.getItem('page') || undefined;
    if (validate_page != undefined) {
        offset = 4 * (validate_page - 1);
    }

    // transformamos los valores del localStorage del filters_search en valores individuales para pasarselos al filters_shop
    var validate_filtersSearch = localStorage.getItem('filters_search') || undefined; // conseguimos de localStorage filters_search, sinó existe undefined
    if (validate_filtersSearch != undefined) {
        localStorage.removeItem('filters_search');
        var filtersSearch = JSON.parse(validate_filtersSearch); // deserializamos para convertir el string otra vez en un array
        console.log(filtersSearch);

        for (row in filtersSearch) {
            if (filtersSearch[row][0] == 'name_op') {
                localStorage.setItem('filter_op', filtersSearch[row][1]);
            } 
            if (filtersSearch[row][0] == 'name_touristcat') {
                localStorage.setItem('filter_touristcat', filtersSearch[row][1]);
            }
            if (filtersSearch[row][0] == 'name_city') {
                localStorage.setItem('filter_city', filtersSearch[row][1]);
            }
        }

        applyFilters('search');
    }

    // transformamos los valores del localStorage del filter_home en valores individuales para pasarselos al filters_shop
    var validate_filtersHome = localStorage.getItem('filters_home') || undefined; // conseguimos de localStorage filters_home, sinó existe undefined
    if (validate_filtersHome != undefined) {
        localStorage.removeItem('filters_home');
        var filtersHome = JSON.parse(validate_filtersHome); // deserializamos para convertir el string otra vez en un array
        console.log(filtersHome);

        if (filtersHome[0][0] == 'name_touristcat') {
            localStorage.setItem('filter_touristcat', filtersHome[0][1]);
        } else if (filtersHome[0][0] == 'name_type') {
            localStorage.setItem('filter_type', filtersHome[0][1]);
        } else if (filtersHome[0][0] == 'name_cat') {
            localStorage.setItem('filter_cat', filtersHome[0][1]);
        } else if (filtersHome[0][0] == 'name_op') {
            localStorage.setItem('filter_op', filtersHome[0][1]);
        } else if (filtersHome[0][0] == 'name_city') {
            localStorage.setItem('filter_city', filtersHome[0][1]);
        }

        localStorage.setItem('filters_shop', JSON.stringify(filtersHome));
    }

    var validate_filtersHome_details = localStorage.getItem('filtersHome_details') || undefined; // conseguimos de localStorage filtersHome_details, sinó existe undefined
    // var validate_locationDetails = localStorage.getItem('location') || undefined; // conseguimos de localStorage location, sinó existe undefined
    var validate_filtersShop = localStorage.getItem('filters_shop') || undefined; // conseguimos de localStorage filters_shop, sinó existe undefined
    
    if (validate_filtersHome_details != undefined) {
        localStorage.removeItem('filtersHome_details'); // eliminamos de localStorage id_recomendation para no interferir en próximas busquedas
        console.log(validate_filtersHome_details);
        loadDetails(validate_filtersHome_details);
    // } else if (validate_locationDetails != undefined && validate_locationDetails != 'home' && validate_locationDetails != 'shop') {
    //     loadDetails(validate_locationDetails);
    } else if (validate_filtersShop != undefined) {
        var filtersShop = JSON.parse(validate_filtersShop); // deserializamos para convertir el string otra vez en un array
        console.log(filtersShop);
        ajaxForSearch(friendlyURL('?module=shop'), 'POST', 'JSON', { 'op': 'filters_shop', 'filters': filtersShop, 'limit': limit, 'offset': offset, 'token': accessToken });
    } else {
        ajaxForSearch(friendlyURL('?module=shop'), 'POST', 'JSON', { 'op': 'all_realestates', 'limit': limit, 'offset': offset, 'token': accessToken });
    }
}

function ajaxForSearch(url, type, dataType, sData=undefined) {
    // console.log(url, type, dataType, sData);
    // return;
    // die("<script>console.log('Hola ajaxForSearch');</script>");
    localStorage.setItem("location", "shop"); // guarda en localStorage localización
    ajaxPromise(url, type, dataType, sData)
    .then(function(data) {
        console.log(data);   
        // return;

        $('.section-detailsCarousel').empty(); // antes de iniciar borramos el contenedor de details
        $('.container_detailsRealestate').empty();
        $('.list_realestates').empty(); // antes de iniciar borramos el contenedor de list
        $('#list_map').empty();
        $('#pagination_container').empty();

        setTimeout(function(){
            $('html, body').animate({ scrollTop: $(".list_realestates") }); // cuando carga posiciona el list al inicio
        }, 50);

        // Mejora para que cuando no hayan resultados en los filtros aplicados
        if (data == "error") {
            $('<div></div>').attr('class', 'intro-single2').appendTo('.section-catch')
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
                );
        } else {
            // Bucle para cada una de las viviendas    
            for (row in data) {
                $('<div></div>').attr('class', 'containerList').attr('id', data[row].id_realestate).appendTo('.list_realestates')
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
                                <div id='${data[row].id_realestate}' class='listLike_container' like='${data[row].like}'>
                                    ${(data[row].like != 0 ?
                                    (`<img src='view/img/icons/like.png' id='${data[row].id_realestate}' class='listLike_icon'>
                                    <span class='list_countLikes'>${(data[row].like > 1 ? (`${data[row].like}`) : "")}</span>`) :
                                    (`<img src='view/img/icons/dislike.png' id='${data[row].id_realestate}' class='listLike_icon'>`))}
                                </div>
                            </div>
                            <div id='${data[row].id_realestate}' class='toDetails'>
                                <div class='listInfo_trading'>
                                    <span class='listInfo_price'>${new Intl.NumberFormat("es-ES").format(data[row].price)} €&nbsp;&nbsp;|&nbsp;&nbsp;${data[row].name_op}</span>
                                </div>
                                <div class='listInfo_specs'>
                                    <div class="listInfoSpecs_contents">
                                        <img src='view/img/specs/area.png' class='listInfoSpecs-img'>
                                        <span class='listInfoSpecs-txt'>
                                            ${data[row].area} m<sup>2</sup>
                                        </span>
                                    </div>
                                    ${(data[row].rooms != 0 ? (`
                                        <div class="listInfoSpecs_contents">
                                            <img src='view/img/specs/rooms.png' class='listInfoSpecs-img'>
                                            <span class='listInfoSpecs-txt'>
                                            ${data[row].rooms} habs.
                                            </span>
                                        </div>
                                    `) : "")}
                                    ${(data[row].bathrooms != 0 ? (`
                                        <div class="listInfoSpecs_contents">
                                            <img src='view/img/specs/bathrooms.png' class='listInfoSpecs-img'>
                                            <span class='listInfoSpecs-txt'>
                                                ${data[row].bathrooms} baños
                                            </span>
                                        </div>
                                    `) : "")}
                                    ${(data[row].floor != 0 ? (`
                                        <div class="listInfoSpecs_contents">
                                            <img src='view/img/specs/floor.png' class='listInfoSpecs-img'>
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
                                    <img src='${data[row].img_realestate[img]}' alt='' class='img-b img-fluid'>
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

            // load_mapboxList(data);
            // load_pagination();
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

function loadDetails(id_realestate) {
    localStorage.setItem("location", id_realestate); // guarda en localStorage localización
    var accessToken = localStorage.getItem('access_token') || null;
    ajaxPromise(friendlyURL('?module=shop'), 'POST', 'JSON', { 'op': 'details_realestate', 'id_re': id_realestate, 'token': accessToken })
    .then(function(data) {
        console.log(data);
        // return;
        $('.section-detailsCarousel').empty(); // antes de iniciar borramos el contenedor de details
        $('.container_detailsRealestate').empty();
        $('.section-listRealestates').empty();
        $('#pagination_container').empty();
        setTimeout(function(){
            $('.section-filters').empty();
        }, 100);

        $('html, body').animate({ scrollTop: $('#details-carousel') });  // cuando carga posiciona el details al inicio

        // Carousel container
        $('<div></div>').attr('class', 'swiper').attr('id', 'details-carousel').appendTo('.section-detailsCarousel')
            .html(`
                <div class='container_detailsCarousel swiper-wrapper'></div>
                <div class='swiper-button-prev'></div>
                <div class='swiper-button-next'></div>
                <div class='details-carousel-pagination carousel-pagination'></div>`
            )
        for (row in data[1]) {
            $('<div></div>').attr('class', 'carousel-item-c swiper-slide').attr('id', data[1][row].id_img_re).appendTo('#details-carousel .container_detailsCarousel')
                .html(`
                    <div class='card-box-b card-shadow news-box'>
                        <div class='img-box-b'>
                            <img src='${data[1][row].img_realestate}' alt='' class='img-b img-fluid'>
                        </div>
                    </div>`
                )     
        }

        // Inicializar el carrusel después de agregar todas las imágenes
        new Swiper('#details-carousel', {
            speed: 600,
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false
            },
            slidesPerView: 'auto',
            navigation: {
                prevEl: '.swiper-button-prev',
                nextEl: '.swiper-button-next',
            },
            pagination: {
                el: '.details-carousel-pagination',
                type: 'bullets',
                clickable: true
            },
            breakpoints: {
                320: {slidesPerView: 1,},
                576: {slidesPerView: 2,},
                768: {slidesPerView: 2,},
                992: {slidesPerView: 3,},
                1200: {slidesPerView: 3,},
                1400: {slidesPerView: 3,},
                1600: {slidesPerView: 4,}
            }
        });
        
        // Info container
        $('<div></div>').attr('class', 'containerDetails_info').attr('id', data[0].id_realestate).appendTo('.container_detailsRealestate')
            .html(`
                <div class='detailsInfo_firstRow'>
                    <div class='detailsInfo_trading'>
                        <span class='detailsInfo_price'>${new Intl.NumberFormat("es-ES").format(data[0].price)} €&nbsp;&nbsp;|&nbsp;&nbsp;${data[0].name_op}</span>
                    </div>
                    <div id='${data[0].id_realestate}' class='detailsLike_container' like='${data[3].like}'>
                        ${(data[3].like != 0 ?
                        (`<img src='view/img/icons/like.png' class='detailsLike_icon'>
                        <span class='details_countLikes'>${(data[3].like > 1 ? (`${data[3].like}`) : "")}</span>`) :
                        (`<img src='view/img/icons/dislike.png' class='detailsLike_icon'>`))}
                    </div>
                    <div class='detailsInfo_backButton'>
                        <button class="btn btn-c back_btn" role="link" onclick="window.location='${friendlyURL('?module=shop')}'">Volver</button>
                    </div>
                </div>
                <div class='detailsInfo_specs'>
                    <div class='detailsInfoSpecs_contents'>
                        <img src='view/img/specs/area.png' class='detailsInfoSpecs-img'>
                        <span class='detailsInfoSpecs-txt'>${data[0].area} m<sup>2</sup></span>
                    </div>
                </div>
                <div class='detailsInfo_title'>
                    <p class='detailsInfoTitle_property'>${data[0].name_type} en ${data[0].name_city}</p>
                    <p class='detailsInfoTitle_province'>${data[0].province}</p>
                </div>
                <p class='detailsInfo_desc'>${data[0].description}</p>`
            )
        if (data[0].rooms != 0){
            $('<div></div>').attr('class', 'detailsInfoSpecs_contents').appendTo('.detailsInfo_specs')
                .html(`
                    <img src='view/img/specs/rooms.png' class='detailsInfoSpecs-img'>
                    <span class='detailsInfoSpecs-txt'>${data[0].rooms} habitaciones</span>`
                )
        }
        if (data[0].bathrooms != 0){
            $('<div></div>').attr('class', 'detailsInfoSpecs_contents').appendTo('.detailsInfo_specs')
                .html(`
                    <img src='view/img/specs/bathrooms.png' class='detailsInfoSpecs-img'>
                    <span class='detailsInfoSpecs-txt'>${data[0].bathrooms} baños</span>`
                )
        }
        if (data[0].floor != 0){
            $('<div></div>').attr('class', 'detailsInfoSpecs_contents').appendTo('.detailsInfo_specs')
                .html(`
                    <img src='view/img/specs/floor.png' class='detailsInfoSpecs-img'>
                    <span class='detailsInfoSpecs-txt'>${data[0].floor}</span>`
                )
        }
        
        // Extras container
        $('<div></div>').attr('class', 'detailsInfo_extras').appendTo('.container_detailsRealestate')
            .html(`
                <p class='detailsInfoExtras_title'>Características</p>
                <div class='detailsInfoExtras_container'></div>`
            )
        for (row in data[2]) { 
            $('<div></div>').attr('class', 'detailsInfoExtras_contents').attr('id', data[2][row].id_extras).appendTo('.detailsInfoExtras_container')
                .html(`
                    <img src='${data[2][row].img_extras}' class='detailsInfoExtras-img'</img>
                    <span class='detailsInfoExtras-txt'>${data[2][row].name_extras}</span>`
                )
        }

        // Mapbox
        $('<div></div>').attr('class', 'detailsMap_contents').appendTo('.container_detailsRealestate')
            .html(`
                <p class='detailsInfoMap_title'>Localización</p>
                <div id='details_map'></div>`
            )

        load_mapboxDetails(data);
        // realestates_related(data);

    }).catch(function() {
        window.location.href='index.php?page=503';
    });
}

function load_mapboxDetails(data) {
    mapboxgl.accessToken = 'pk.eyJ1IjoiMjBqdWFuMTUiLCJhIjoiY2t6eWhubW90MDBnYTNlbzdhdTRtb3BkbyJ9.uR4BNyaxVosPVFt8ePxW1g';
    const map = new mapboxgl.Map({
        container: 'details_map', // container ID
        style: 'mapbox://styles/mapbox/streets-v12', // style URL
        center: [data[0].lng, data[0].lat], // starting position [lng, lat]
        zoom: 15 // starting zoom
    });
    map.addControl(new mapboxgl.NavigationControl());
    const marker = new mapboxgl.Marker({ color: '#2eca6a' })
        .setLngLat([data[0].lng, data[0].lat])
        .addTo(map);
}

$(document).ready(function() {
    // console.log('Hola JS document ready');
    loadAllRealestates();
    // setTimeout(function(){ 
    //     loadFilters();
    // }, 20);
    // saveFilters();
    // clicks();
});