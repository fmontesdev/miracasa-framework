<?php
    class login_dao {
        static $_instance;

        private function __construct() {
        }

        public static function getInstance() {
            if(!(self::$_instance instanceof self)){
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function select_userReg($db, $username){
            // $data = array($_POST['username_reg'], $_POST['email_reg'], $_POST['passwd1_reg']);
            // return $data;

			$sql = "SELECT u.username
                    FROM `user` u
                    WHERE u.username = '$username'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }

        public function select_email($db, $email){

			$sql = "SELECT u.email
                    FROM `user` u
                    WHERE u.email = '$email'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        } 

        public function insert_user($db, $username, $password, $email, $avatar, $token) {

            $sql = "INSERT INTO `user`(`username`, `password`, `email`, `type_user`, `avatar`, `token_email`, `isActive`) 
                    VALUES ('$username','$password','$email','client','$avatar','$token', 'false')";

            return $stmt = $db->ejecutar($sql);
        }

        public function select_verify_email($db, $token_email){

			$sql = "SELECT u.token_email
                        FROM `user` u
                        WHERE u.token_email = '$token_email'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }

        public function update_verify_email($db, $token_email){

            $sql = "UPDATE `user` u
                        SET u.isActive = 'true'
                        WHERE u.token_email = '$token_email'";

            return $stmt = $db->ejecutar($sql);
        }

        public function select_userLogin($db, $username){

			$sql = "SELECT u.id_user, u.username, u.password, u.isActive
                        FROM `user` u
                        WHERE u.username = '$username'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }

        // SIMPLIFICAR LOS DOS ÚLTIMOS DAOS EN UNO ???

        public function select_data_user($db, $username){

            $sql = "SELECT u.username, u.password, u.email, u.type_user, u.avatar
                    FROM `user` u
                    WHERE u.username = '$username'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }
    }

?>