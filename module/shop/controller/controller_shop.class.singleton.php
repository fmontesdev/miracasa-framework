<?php
    class controller_shop {

        static $_instance;
        
        public static function getInstance() {
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        function view() {
            common::load_view('top_page_shop.html', VIEW_PATH_SHOP . 'shop.html');
        }

        function all_realestates() {
            echo json_encode(common::load_model('shop_model', 'get_all_realestates', [$_POST['limit'], $_POST['offset'], $_POST['token']]));
        }

        function details_realestate() {
            echo json_encode(common::load_model('shop_model', 'get_details_realestate', [$_POST['id_re'], $_POST['token']]));
        }

        function load_filters() {
            echo json_encode(common::load_model('shop_model', 'get_load_filters'));
        }

        function filters_shop() {
            echo json_encode(common::load_model('shop_model', 'get_filters_shop', [$_POST['filters'], $_POST['limit'],$_POST['offset'], $_POST['token']]));
        }

        function realestates_related() {
            echo json_encode(common::load_model('shop_model', 'get_realestates_related', [$_POST['id'], $_POST['operation'],$_POST['limit'], $_POST['offset']]));
        }

        function count_all() {
            echo json_encode(common::load_model('shop_model', 'get_count_all'));
        }

        function count_filtersShop() {
            echo json_encode(common::load_model('shop_model', 'get_count_filtersShop', $_POST['filters']));
        }

        function count_realestates_related() {
            // echo json_encode('Hola');
            echo json_encode(common::load_model('shop_model', 'get_count_realestates_related', [$_POST['id'], $_POST['operation']]));
        }

        function likes() {
            // echo json_encode($_POST['token']);
            echo json_encode(common::load_model('shop_model', 'get_likes', [$_POST['id_re'], $_POST['token'], $_POST['countLike']]));
        }

    }
?>
