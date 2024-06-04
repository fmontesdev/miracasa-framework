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

            $sql = "SELECT up.uid, up.username, up.email
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
    }

?>