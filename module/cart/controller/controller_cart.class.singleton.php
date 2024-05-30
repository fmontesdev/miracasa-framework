<?php
    class controller_cart {

        static $_instance;
        
        public static function getInstance() {
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        function view() {
            common::load_view('top_page_cart.html', VIEW_PATH_CART . 'cart.html');
            }
 
        function insert_cart() {
            echo json_encode(common::load_model('cart_model', 'get_insert_cart', [$_POST['id_re'], $_POST['token']]));
        }

        function select_cart() {
            echo json_encode(common::load_model('cart_model', 'get_select_cart', $_POST['token']));
        }

        function update_cart() {
            echo json_encode(common::load_model('cart_model', 'get_update_cart', [$_POST['id_re'], $_POST['token'], $_POST['qty'], $_POST['operation']]));
        }

    }

?>