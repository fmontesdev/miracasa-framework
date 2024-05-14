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

        function details_realestate() {
            echo json_encode(common::load_model('shop_model', 'get_details_realestate', [$_POST['id_re'], $_POST['token']]));
        }

    }
?>
