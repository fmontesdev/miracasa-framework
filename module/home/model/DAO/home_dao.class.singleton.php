<?php
    class home_dao {
        static $_instance;

        private function __construct() {
        }

        public static function getInstance() {
            if(!(self::$_instance instanceof self)){
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function select_touristcat($db) {

            $sql= "SELECT * FROM `tourist_cat` ORDER BY id_touristcat";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar_array($stmt);
        }

    }
?>