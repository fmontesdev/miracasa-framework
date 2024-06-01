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

			$sql = "SELECT c.id_realestate, c.uid, c.quantity
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
            $sql = "CALL insert_cart($id_re, $uid)";

            return $stmt = $db->ejecutar($sql);
        }

        public function update_cart($db, $id_re, $uid, $op){
            $sql = "CALL update_cart($id_re, $uid, $op)";

            return $stmt = $db->ejecutar($sql);
        }

        public function select_cart($db, $uid){

			$sql = "SELECT ca.id_realestate, ca.uid, t.name_type, o.name_op, c.name_city, c.province, r.area, r.rooms,
                        r.bathrooms, r.floor, r.description, i.img_realestate, ca.quantity, s.price, r.stock
                        FROM `real_estate` r
                        INNER JOIN `cart` ca ON r.id_realestate = ca.id_realestate
                        INNER JOIN `belong_to_type` bt ON  r.id_realestate = bt.id_realestate 
                        INNER JOIN `type` t ON t.id_type = bt.id_type
                        INNER JOIN `is_traded` s ON r.id_realestate = s.id_realestate 
                        INNER JOIN `operation` o ON o.id_op = s.id_op
                        INNER JOIN `img_realestate` i ON r.id_realestate = i.id_realestate
                        INNER JOIN `city` c ON r.id_city = c.id_city
                        WHERE ca.uid = '$uid' AND t.name_type != 'Vivienda'
                        GROUP BY ca.id_realestate
                        ORDER BY ca.date_time";

            $stmt = $db->ejecutar($sql);
            return $db->listar_indexed_array($stmt);
        }

        public function delete_lineCart($db, $id_re, $uid){
            $sql = "CALL delete_lineCart($id_re, $uid)";

            return $stmt = $db->ejecutar($sql);
        }

        public function insert_bill($db, $uid){

			$sql = "CALL insert_bill($uid, @id_bill)";
            $stmt = $db->ejecutar($sql);
                       
            $sql = "SELECT @id_bill AS id_bill";
            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }

        public function insert_bill_detail($db, $uid, $id_bill){
			$sql = "CALL insert_bill_detail($uid, $id_bill)";

            return $stmt = $db->ejecutar($sql);
        }

        public function update_stock($db, $uid){
			$sql = "CALL update_stock($uid)";

            return $stmt = $db->ejecutar($sql);
        }

        public function insert_purchase_log($db, $uid){
			$sql = "CALL insert_purchase_log($uid)";

            return $stmt = $db->ejecutar($sql);
        }

        public function delete_cart($db, $uid){
			$sql = "CALL delete_cart($uid)";

            return $stmt = $db->ejecutar($sql);
        }

        public function select_bill($db, $uid, $provider, $id_bill){

			$sql = "SELECT b.id_bill, b.date, up.username, up.email
                        FROM `bill` b
                        INNER JOIN `user` u ON u.uid = b.uid
                        INNER JOIN `user_$provider` up ON u.uid = up.uid
                        WHERE b.uid = '$uid' AND b.id_bill = '$id_bill'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }

        public function select_bill_detail($db, $id_bill){

			$sql = "SELECT b.quantity, CONCAT(o.name_op, ' de ', t.name_type, ' en ', c.name_city) AS description,
                        FORMAT(b.price, 2) AS price, FORMAT(b.quantity * b.price, 2) AS import
                        FROM `real_estate` r
                        INNER JOIN `belong_to_type` bt ON  r.id_realestate = bt.id_realestate 
                        INNER JOIN `type` t ON t.id_type = bt.id_type
                        INNER JOIN `is_traded` s ON r.id_realestate = s.id_realestate 
                        INNER JOIN `operation` o ON o.id_op = s.id_op
                        INNER JOIN `city` c ON r.id_city = c.id_city
                        INNER JOIN `bill_detail` b ON r.id_realestate = b.id_realestate
                        WHERE b.id_bill = '$id_bill' AND t.name_type != 'Vivienda'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_array($stmt);
        }

        public function select_bill_total($db, $uid, $id_bill){

			$sql = "SELECT SUM((bd.quantity * bd.price)) AS total
                        FROM `bill` b
                        INNER JOIN `bill_detail` bd ON b.id_bill = bd.id_bill
                        WHERE b.uid = '$uid' AND b.id_bill = '$id_bill'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }
    }

?>