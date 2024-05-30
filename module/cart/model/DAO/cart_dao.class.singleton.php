<?php
    class cart_dao {
        static $_instance;

        private function __construct() {
        }

        public static function getInstance() {
            if(!(self::$_instance instanceof self)){
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function select_lineCart($db, $id_re, $uid){

			$sql = "SELECT c.id_realestate, c.uid, c.quantity, c.state
                    FROM `cart` c
                    WHERE c.id_realestate = '$id_re' AND c.uid = '$uid'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }

        public function select_realestate($db, $id_re){

            $sql = "SELECT r.id_realestate, t.name_type, o.name_op, s.price, c.name_city, c.province,
                    r.area, r.rooms, r.bathrooms, r.floor, r.description, i.img_realestate, r.stock
                        FROM `real_estate` r
                        INNER JOIN `belong_to_type` bt ON  r.id_realestate = bt.id_realestate 
                        INNER JOIN `type` t ON t.id_type = bt.id_type
                        INNER JOIN `is_traded` s ON r.id_realestate = s.id_realestate 
                        INNER JOIN `operation` o ON o.id_op = s.id_op
                        INNER JOIN `img_realestate` i ON r.id_realestate = i.id_realestate
                        INNER JOIN `city` c ON r.id_city = c.id_city
                        WHERE r.id_realestate = '$id_re' AND t.name_type != 'Vivienda'
                        GROUP BY r.id_realestate";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }

        public function select_totalQty($db, $uid){

			$sql = "SELECT SUM(c.quantity) AS quantity
                    FROM `cart` c INNER JOIN `real_estate` r ON c.id_realestate = r.id_realestate
                    WHERE c.uid = '$uid' AND r.stock != 0";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }

        public function insert_cart($db, $id_re, $uid){

			$sql = "INSERT INTO `cart` (`id_realestate`, `uid`, `quantity`) 
                    VALUES ('$id_re','$uid',1)";

            return $stmt = $db->ejecutar($sql);
        }

        public function update_cart($db, $id_re, $uid, $op){

            $sql = "UPDATE `cart` c
                        SET c.quantity = c.quantity + '$op'
                        WHERE c.id_realestate = '$id_re' AND c.uid = '$uid'";

            return $stmt = $db->ejecutar($sql);
        }

        public function select_cart($db, $uid){

			$sql = "SELECT ca.id_realestate, ca.uid, t.name_type, o.name_op, c.name_city, c.province, r.area, r.rooms, r.bathrooms, r.floor, r.description, i.img_realestate, ca.quantity, s.price, r.stock, ca.state
                    FROM `real_estate` r
                    INNER JOIN `cart` ca ON r.id_realestate = ca.id_realestate
                    INNER JOIN `belong_to_type` bt ON  r.id_realestate = bt.id_realestate 
                    INNER JOIN `type` t ON t.id_type = bt.id_type
                    INNER JOIN `is_traded` s ON r.id_realestate = s.id_realestate 
                    INNER JOIN `operation` o ON o.id_op = s.id_op
                    INNER JOIN `img_realestate` i ON r.id_realestate = i.id_realestate
                    INNER JOIN `city` c ON r.id_city = c.id_city
                    WHERE ca.uid = '$uid' AND t.name_type != 'Vivienda'
                    GROUP BY ca.id_realestate";

            $stmt = $db->ejecutar($sql);
            return $db->listar_indexed_array($stmt);
        }

        public function delete_lineCart($db, $id_re, $uid){

            $sql = "DELETE
                        FROM `cart` c
                        WHERE c.id_realestate = '$id_re' AND c.uid = '$uid'";

            return $stmt = $db->ejecutar($sql);
        }

    }

?>