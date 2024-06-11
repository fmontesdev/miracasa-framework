<?php
    class profile_dao {
        static $_instance;

        private function __construct() {
        }

        public static function getInstance() {
            if(!(self::$_instance instanceof self)){
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function select_user($db, $uid, $provider){

            $sql = "SELECT up.uid, up.username, up.email, up.phone, up.avatar, u.provider
                        FROM `user` u INNER JOIN `user_$provider` up ON u.uid = up.uid
                        WHERE u.uid = '$uid'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }

        public function select_bill($db, $uid){

			$sql = "SELECT b.id_bill, b.date, b.uid
                        FROM `bill` b
                        WHERE b.uid = '$uid'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_indexed_array($stmt);
        }

        public function select_likes($db, $uid) {

            $sql = "SELECT r.id_realestate, r.lat, r.lng, t.name_type, o.name_op, s.price, c.name_city, c.province, r.area, r.rooms, r.bathrooms, r.floor, r.description,
                    GROUP_CONCAT(DISTINCT i.img_realestate SEPARATOR ':') AS img_realestate, GROUP_CONCAT(DISTINCT l.uid SEPARATOR ':') AS 'like'
                        FROM `real_estate` r
                        INNER JOIN `belong_to_type` bt ON  r.id_realestate = bt.id_realestate 
                        INNER JOIN `type` t ON t.id_type = bt.id_type
                        INNER JOIN `is_traded` s ON r.id_realestate = s.id_realestate 
                        INNER JOIN `operation` o ON o.id_op = s.id_op
                        INNER JOIN `img_realestate` i ON r.id_realestate = i.id_realestate
                        INNER JOIN `city` c ON r.id_city = c.id_city
                        LEFT JOIN `like` l ON r.id_realestate = l.id_realestate
                        WHERE t.name_type != 'Vivienda' AND l.uid = '$uid'
                        GROUP BY r.id_realestate";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar_array_img_like($stmt, $uid);
        }

        public function delete_like($db, $uid, $id_realestate){

			$sql = "DELETE
                        FROM `like` l
                        WHERE l.uid = '$uid' AND l.id_realestate = '$id_realestate'";

            return $stmt = $db->ejecutar($sql);
        }

        public function select_user_toUpdate($db, $username){

			$sql = "SELECT u.username
                    FROM `user_local` u
                    WHERE u.username = '$username'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }

        public function select_email_toUpdate($db, $email){

			$sql = "SELECT u.email
                    FROM `user_local` u
                    WHERE u.email = '$email'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }

        public function update_user($db, $uid, $username, $email, $phone, $avatar){
			$sql = "CALL update_user('$uid', '$username', '$email', '$phone', '$avatar')";

            return $stmt = $db->ejecutar($sql);
        }
    }

?>