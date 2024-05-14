<?php
    class controller_search {

        static $_instance;
        
        public static function getInstance() {
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        
        function search_operation() {
            echo json_encode(common::load_model('search_model', 'get_search_operation'));
        }

        function search_touristCategory() {
            echo json_encode(common::load_model('search_model', 'get_search_touristCategory', $_POST['operation']));
        }

        function search_autocomplete() {
            echo json_encode(common::load_model('search_model', 'get_search_autocomplete', [$_POST['complete'], $_POST['operation'], $_POST['touristcat']]));
        }
    }
?>