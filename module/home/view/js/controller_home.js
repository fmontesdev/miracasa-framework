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
//   carouselLastsearch();
//   carouselRecomendations();
//   carouselMostvisited();
//   clicks();
});