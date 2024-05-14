<?php
    class shop_model {
        private $bll;
        static $_instance;

        function __construct() {
            $this -> bll = shop_bll::getInstance();
        }

        public static function getInstance() {
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function get_all_realestates($args) {
            return $this -> bll -> get_all_realestates_BLL($args);
        }

        public function get_details_realestate($args) {
            return $this -> bll -> get_details_realestate_BLL($args);
        }

        public function get_load_filters() {
            return $this -> bll -> get_load_filters_BLL();
        }

    }
?>
