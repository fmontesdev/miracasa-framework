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
            echo 'Hola Shop';
            // common::load_view('top_page_shop.html', VIEW_PATH_SHOP . 'shop.html');
        }

    }
?>
