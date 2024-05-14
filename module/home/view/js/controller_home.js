function carouselTouristcat() {
    //die("<script>console.log('Hola loadCategories');</script>");
    ajaxPromise(friendlyURL('?module=home'), 'POST', 'JSON', { 'op': 'carouselTouristcat' })
    .then(function(data) {
        console.log(data);
        return;
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

$(document).ready(function() {
  //$data = 'hola JS function';
  //die("<script>console.log('.json_encode( $data ).');</script>");
//   remove_filtersShop();
//   location_home();
  carouselTouristcat();
//   carouselTypes();
//   carouselCategories();
//   carouselOperations();
//   carouselCities();
//   carouselLastsearch();
//   carouselRecomendations();
//   carouselMostvisited();
//   clicks();
});