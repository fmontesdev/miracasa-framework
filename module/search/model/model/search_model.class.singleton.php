<?php
    class search_model {
        private $bll;
        static $_instance;
        
        function __construct() {
            $this -> bll = search_bll::getInstance();
        }

        public static function getInstance() {
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function get_search_operation() {
            return $this -> bll -> get_search_operation_BLL();
        }

        public function get_search_touristCategory($operation) {
            return $this -> bll -> get_search_touristCategory_BLL($operation);
        }

    }