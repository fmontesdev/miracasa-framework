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

        public function get_filters_shop($args) {
            return $this -> bll -> get_filters_shop_BLL($args);
        }

        public function get_realestates_related($args) {
            return $this -> bll -> get_realestates_related_BLL($args);
        }

        public function get_count_all() {
            return $this -> bll -> get_count_all_BLL();
        }

        public function get_count_filtersShop($args) {
            return $this -> bll -> get_count_filtersShop_BLL($args);
        }

        public function get_count_realestates_related($args) {
            return $this -> bll -> get_count_realestates_related_BLL($args);
        }

        public function get_likes($args) {
            return $this -> bll -> get_likes_BLL($args);
        }

    }
?>
