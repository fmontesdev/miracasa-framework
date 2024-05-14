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

		public function select_type($db) {

            $sql= "SELECT * FROM `type` WHERE subtype = 'Inmueble' ORDER BY id_type";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar_array($stmt);
        }

		public function select_category($db) {

            $sql= "SELECT * FROM `category` ORDER BY id_cat";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar_array($stmt);
        }

		public function select_operation($db) {

            $sql= "SELECT * FROM `operation` ORDER BY id_op";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar_array($stmt);
        }

		public function select_city($db) {

            $sql= "SELECT * FROM `city` ORDER BY id_city LIMIT 25";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar_array($stmt);
        }

    }
?>