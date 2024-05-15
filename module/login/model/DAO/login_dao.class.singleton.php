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

        public function insert_user($db, $username, $password, $email) {

            $hashed_pass = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]); // password_hash() función para encriptar password muy segura
            // $hashavatar = md5(strtolower(trim($email))); // md5() función para encriptar con menos seguridad
            // $avatar = "https://i.pravatar.cc/500?u=$hashavatar";
            $avatar = "https://api.dicebear.com/8.x/initials/svg?backgroundColor=2eca6a&size=40&scale=110&radius=50&seed=$username";
            $sql = "INSERT INTO `user`(`username`, `password`, `email`, `type_user`, `avatar`) 
                    VALUES ('$username','$hashed_pass','$email','client','$avatar')";

            return $stmt = $db->ejecutar($sql);
        }

        public function select_userLogin($db, $username){

			$sql = "SELECT u.id_user, u.username, u.password
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