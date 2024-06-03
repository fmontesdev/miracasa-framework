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

        public function select_bill($db, $uid, $provider){

			$sql = "SELECT b.id_bill, b.date, b.uid, up.username, up.email
                        FROM `bill` b
                        INNER JOIN `user` u ON u.uid = b.uid
                        INNER JOIN `user_$provider` up ON u.uid = up.uid
                        WHERE b.uid = '$uid'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_indexed_array($stmt);
        }
    }

?>