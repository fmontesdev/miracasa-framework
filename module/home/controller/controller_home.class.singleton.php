<?php
    class controller_home {

        static $_instance;
        
        public static function getInstance() {
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        function view() {
            common::load_view('top_page_home.html', VIEW_PATH_HOME . 'home.html');
        }

        function carouselTouristcat() {
            echo json_encode(common::load_model('home_model', 'get_carouselTouristcat'));
        }

        function carouselType() {
            echo json_encode(common::load_model('home_model', 'get_carouselType'));
        }
        
        function carouselCategory() {
            // echo json_encode('Hola');
            echo json_encode(common::load_model('home_model', 'get_carouselCategory'));
        }

        function carouselOperation() {
            echo json_encode(common::load_model('home_model', 'get_carouselOperation'));
        }

        function carouselCity() {
            echo json_encode(common::load_model('home_model', 'get_carouselCity'));
        }

        // function carouselRecomendations() {
        //     echo json_encode(common::load_model('home_model', 'get_carouselRecomendations'));
        // }

        // function carouselMostvisited() {
        //     echo json_encode(common::load_model('home_model', 'get_carouselMostvisited'));
        // }

        // function carouselLastsearch() {
        //     echo json_encode(common::load_model('home_model', 'get_carouselLastsearch', $_POST['filters']));
        // }
    }
?>