function carouselTouristcat() {
    //die("<script>console.log('Hola loadCategories');</script>");
    ajaxPromise(friendlyURL('?module=home'), 'POST', 'JSON', { 'op': 'carouselTouristcat' })
    .then(function(data) {
        // console.log(data);
        // return;
        for (row in data) {
            $('<div></div>').attr('class', 'touristcatSlide carousel-item-c swiper-slide').attr('id', data[row].name_touristcat).appendTo('#touristcat-carousel .containerTouristcat')
                .html(`
                    <div class='card-box-b card-shadow news-box'>
                        <div class='img-box-b'>
                            <img src='${data[row].img_touristcat}' alt='' class='img-b img-fluid'>
                        </div>
                        <div class='card-overlay'>
                            <div class='card-header-e touristcat_map_overlay_cont'>
                                <div class='full_width'>
                                    <img src='${data[row].map_touristcat}' alt='' class='touristcat_map'>
                                </div>
                                <div class='full_width'>
                                    <span class='title-1'>
                                    ${data[row].name_touristcat}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>`
                )     
        }
  
        new Swiper('#touristcat-carousel', {
            speed: 600,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false
            },
            slidesPerView: 'auto',
            //slidesPerGroup: 2,
            //initialSlide: 2,
            pagination: {
                el: '.touristcat-carousel-pagination',
                type: 'bullets',
                clickable: true
            },
            breakpoints: {
                320: {slidesPerView: 1,},
                500: {slidesPerView: 2,},
                1000: {slidesPerView: 3,},
                1500: {slidesPerView: 4,},
                2000: {slidesPerView: 5,},
            }
        });
    }).catch(function() {
        // window.location.href='index.php?page=503';S
        console.log('Error: CarouselTouristcat error');
    });
}

function carouselTypes() {
    ajaxPromise(friendlyURL('?module=home'), 'POST', 'JSON', { 'op': 'carouselType' })
    .then(function(data) {
        for (row in data) {
            $('<div></div>').attr('class', 'typeSlide carousel-item-c swiper-slide').attr('id', data[row].name_type).appendTo('#types-carousel .containerTypes')
                .html(`
                    <div class='card-box-b card-shadow news-box border_radius'>
                        <div class='img-box-b'>
                            <img src='${data[row].img_type}' alt='' class='img-b img-fluid'>
                        </div>
                        <div class='card-overlay'>
                            <div class='card-header-e'>
                                <span class='title-1'>
                                    ${data[row].name_type}
                                </span>
                            </div>
                        </div>
                    </div>`
                )     
        }
  
        new Swiper('#types-carousel', {
            speed: 600,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false
            },
            slidesPerView: 'auto',
            //slidesPerGroup: 2,
            //initialSlide: 2,
            pagination: {
                el: '.types-carousel-pagination',
                type: 'bullets',
                clickable: true
            },
            breakpoints: {
                320: {
                    slidesPerView: 2,
                    spaceBetween: 18
                },
            
                1200: {
                    slidesPerView: 4,
                    spaceBetween: 18
                }
            }
        });
    }).catch(function() {
        // window.location.href='index.php?page=503';
        console.log('Error: CarouselTypes error');
    });
}

function carouselCategories() { 
    ajaxPromise(friendlyURL('?module=home'), 'POST', 'JSON', { 'op': 'carouselCategory' })
    .then(function(data) {
        for (row in data) {
            $('<div></div>').attr('class', 'categorySlide carousel-item-c swiper-slide').attr('id', data[row].name_cat).appendTo('#categories-carousel .containerCategories')
                .html(`
                    <div class='card-box-b card-shadow news-box border_radius'>
                        <div class='img-box-b'>
                            <img src='${data[row].img_cat}' alt='' class='img-b img-fluid'>
                        </div>
                        <div class='card-overlay'>
                            <div class='card-header-e'>
                                <span class='title-1'>
                                    ${data[row].name_cat}
                                </span>
                            </div>
                        </div>
                    </div>`
                )     
        }
  
        new Swiper('#categories-carousel', {
            speed: 600,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false
            },
            slidesPerView: 'auto',
            pagination: {
                el: '.categories-carousel-pagination',
                type: 'bullets',
                clickable: true
            },
            breakpoints: {
                320: {
                    slidesPerView: 2,
                    spaceBetween: 18
                },
            
                1200: {
                    slidesPerView: 4,
                    spaceBetween: 18
                }
            }
        });
    }).catch(function() {
        // window.location.href='index.php?page=503';
        console.log('Error: CarouselCategories error');
    });
}

function carouselOperations() {
    ajaxPromise(friendlyURL('?module=home'), 'POST', 'JSON', { 'op': 'carouselOperation' })
    .then(function(data) {
        for (row in data) {
            $('<div></div>').attr('class', 'operationSlide carousel-item-c swiper-slide').attr('id', data[row].name_op).appendTo('#operations-carousel .containerOperations')
                .html(`
                    <div class='card-box-b card-shadow news-box border_radius'>
                        <div class='img-box-b'>
                            <img src='${data[row].img_op}' alt='' class='img-b img-fluid'>
                        </div>
                        <div class='card-overlay'>
                            <div class='card-header-e'>
                                <span class='title-1'>
                                    ${data[row].name_op}
                                </span>
                            </div>
                        </div>
                    </div>`
                )     
        }
  
        new Swiper('#operations-carousel', {
            speed: 600,
            loop: false,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false
            },
            slidesPerView: 'auto',
            pagination: {
                el: '.operations-carousel-pagination',
                type: 'bullets',
                clickable: true
            },
            breakpoints: {
                320: {
                    slidesPerView: 2,
                    spaceBetween: 18
                },
        
                1200: {
                    slidesPerView: 4,
                    spaceBetween: 18
                }
            }
        });
    }).catch(function() {
        // window.location.href='index.php?page=503';
        console.log('Error: CarouselOperations error');
    });
}

function carouselCities() {
    ajaxPromise(friendlyURL('?module=home'), 'POST', 'JSON', { 'op': 'carouselCity' })
    .then(function(data) {
        for (row in data) {
            $('<div></div>').attr('class', 'citySlide carousel-item-c swiper-slide').attr('id', data[row].name_city).appendTo('#cities-carousel .containerCities')
                .html(`
                    <div class='card-box-b card-shadow news-box border_radius'>
                        <div class='img-box-b'>
                            <img src='${data[row].img_city}' alt='' class='img-b img-fluid'>
                        </div>
                        <div class='card-overlay'>
                            <div class='card-header-e'>
                                <span class='title-3'>
                                    ${data[row].name_city}
                                </span>
                            </div>
                        </div>
                    </div>`
                )     
        }
  
        new Swiper('#cities-carousel', {
            speed: 500,
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false
            },
            slidesPerView: 'auto',
            pagination: {
                el: '.cities-carousel-pagination',
                type: 'bullets',
                clickable: true
            },
            breakpoints: {
                320: {
                    slidesPerView: 3,
                    spaceBetween: 14
                },
        
                1200: {
                    slidesPerView: 6,
                    spaceBetween: 14
                }
            }
        });
    }).catch(function() {
        // window.location.href='index.php?page=503';
        console.log('Error: CarouselTouristcat error');
    });
}

function carouselLastsearch() {
    var validate_lastSearch = localStorage.getItem('last_search') || undefined;
  
    if (validate_lastSearch != undefined) {
        var lastSearch = JSON.parse(validate_lastSearch);
        // console.log(lastSearch);
  
        ajaxPromise(friendlyURL('?module=home'), 'POST', 'JSON', { 'op': 'carouselLastsearch', 'filters': lastSearch } )
        .then(function(data) {
            // console.log(data);
            // return;
  
            $('<div></div>').attr('class', 'col-md-12').appendTo('.section-lastsearch .container .row')
                .html(`
                    <div class="title-wrap-2 d-flex justify-content-between">
                        <div class="title-box">
                            <h2 class="title-a">Sigue con tu última búsqueda</h2>
                        </div>
                    </div>`
                );
  
            for (row in data) {
                $('<div></div>').attr('class', 'lastsearchSlide carousel-item-b swiper-slide').attr('id', data[row].id_realestate).appendTo('#lastsearch-carousel .containerLastsearch')
                    .html(`
                        <div class='card-box-a card-shadow border_radius'>
                            <div class='img-box-a'>
                                <img src='${data[row].img_realestate[0]}' alt='' class='img-a img-fluid'>
                            </div>
                            <div class='card-overlay'>
                                <div class='card-overlay-a-content'>
                                    <div class='card-header-a'>
                                        <h2 class='card-title-a'>
                                            <span class='color-b'>${data[row].name_type}</span> en ${data[row].name_city}
                                        </h2>
                                    </div>
                                    <div class='card-body-a'>
                                        <div class='price-box d-flex'>
                                            <span class='price-a'>
                                                ${data[row].name_op} | ${new Intl.NumberFormat("es-ES").format(data[row].price)} €
                                            </span>
                                        </div>
                                    </div>
                                    <div class='card-footer-a'>
                                        <ul class='card-info d-flex justify-content-around'>
                                            <li>
                                                <span class='card-info-title'>Area: </span>
                                                <span>${data[row].area} m<sup>2</sup></span>
                                            </li>
                                            <li>
                                                <span class='card-info-title'>Habitaciones: </span>
                                                <span>${data[row].rooms}</span>
                                            </li>
                                            <li>
                                                <span class='card-info-title'>Baños: </span>
                                                <span>${data[row].bathrooms}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>`
                    )     
            }
  
            new Swiper('#lastsearch-carousel', {
                speed: 500,
                loop: false,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false
                },
                slidesPerView: 'auto',
                pagination: {
                    el: '.lastsearch-carousel-pagination',
                    type: 'bullets',
                    clickable: true
                },
                breakpoints: {
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 18
                    },
                
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 18
                    },
                
                    1200: {
                        slidesPerView: 3,
                        spaceBetween: 18
                    }
                }
            });
        }).catch(function() {
            // window.location.href='index.php?page=503';
            console.log('Error: CarouselLastsearch error');
        });
    } else {
        $('.section-lastsearch').hide();
    }
}

function carouselMostvisited() {
    ajaxPromise(friendlyURL('?module=home'), 'POST', 'JSON', { 'op': 'carouselMostvisited' })
    .then(function(data) {
        for (row in data) {
            $('<div></div>').attr('class', 'mostvisitedSlide carousel-item-b swiper-slide').attr('id', data[row].id_realestate).appendTo('#mostvisited-carousel .containerMostvisited')
                .html(`
                    <div class='card-box-a card-shadow border_radius'>
                        <div class='img-box-a'>
                            <img src='${data[row].img_realestate}' alt='' class='img-a img-fluid'>
                        </div>
                        <div class='card-overlay'>
                            <div class='card-overlay-a-content'>
                                <div class='card-header-a'>
                                    <h2 class='card-title-a'>
                                        <span class='color-b'>${data[row].name_type}</span> en ${data[row].name_city}
                                    </h2>
                                </div>
                                <div class='card-body-a'>
                                    <div class='price-box d-flex'>
                                        <span class='price-a'>
                                            ${data[row].name_op} | ${new Intl.NumberFormat("es-ES").format(data[row].price)} €
                                        </span>
                                    </div>
                                </div>
                                <div class='card-footer-a'>
                                    <ul class='card-info d-flex justify-content-around'>
                                        <li>
                                            <span class='card-info-title'>Area: </span>
                                            <span>${data[row].area} m<sup>2</sup></span>
                                        </li>
                                        <li>
                                            <span class='card-info-title'>Habitaciones: </span>
                                            <span>${data[row].rooms}</span>
                                        </li>
                                        <li>
                                            <span class='card-info-title'>Baños: </span>
                                            <span>${data[row].bathrooms}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>`
                )     
        }
  
        new Swiper('#mostvisited-carousel', {
            speed: 500,
            loop: false,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false
            },
            slidesPerView: 'auto',
            pagination: {
                el: '.mostvisited-carousel-pagination',
                type: 'bullets',
                clickable: true
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 18
                },
            
                768: {
                    slidesPerView: 2,
                    spaceBetween: 18
                },
            
                1200: {
                    slidesPerView: 3,
                    spaceBetween: 18
                }
            }
        });
    }).catch(function() {
        // window.location.href='index.php?page=503';
        console.log('Error: CarouselMostvisited error');
    });
}

$(document).ready(function() {
  //$data = 'hola JS function';
  //die("<script>console.log('.json_encode( $data ).');</script>");
//   remove_filtersShop();
//   location_home();
  carouselTouristcat();
  carouselTypes();
  carouselCategories();
  carouselOperations();
  carouselCities();
  carouselLastsearch();
  carouselMostvisited();
//   carouselRecomendations();
//   clicks();
});