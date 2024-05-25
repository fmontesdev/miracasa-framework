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

			$sql = "SELECT u.username
                    FROM `user_local` u
                    WHERE u.username = '$username'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }

        public function select_email($db, $email){

			$sql = "SELECT u.email
                    FROM `user_local` u
                    WHERE u.email = '$email'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        } 

        public function insert_user($db, $uid) {

            $sql = "INSERT INTO `user`(`uid`, `type_user`, `provider`, `isActive`) 
                    VALUES ('$uid','client','local','false')";

            return $stmt = $db->ejecutar($sql);
        }

        public function insert_user_local($db, $uid, $username, $password, $email, $phone, $avatar) {

            $sql = "INSERT INTO `user_local`(`uid`, `username`, `password`, `email`, `phone`, `avatar`) 
                    VALUES ('$uid','$username','$password','$email','$phone','$avatar')";

            return $stmt = $db->ejecutar($sql);
        }

        public function insert_user_log($db, $uid) {

            $sql = "INSERT INTO `user_log`(`uid`, `login_time`, `login_attempts`, `otp`, `otp_attempts`, `expired_token`) 
                    VALUES ('$uid',0,0,null,0,null)";

            return $stmt = $db->ejecutar($sql);
        }

        public function select_social_login($db, $uid) {

			$sql = "SELECT u.uid
                        FROM `user` u
                        WHERE u.uid = '$uid'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }

        public function insert_socialLogin_user($db, $uid, $provider) {

            $sql = "INSERT INTO `user`(`uid`, `type_user`, `provider`, `isActive`) 
                    VALUES ('$uid','client','$provider','true')";

            return $stmt = $db->ejecutar($sql);
        }

        public function insert_socialLogin_userProvider($db, $uid, $username, $email, $phone, $avatar, $provider) {

            $sql = "INSERT INTO `user_$provider`(`uid`, `username`, `email`, `phone`, `avatar`) 
                    VALUES ('$uid','$username','$email','$phone','$avatar')";

            return $stmt = $db->ejecutar($sql);
        }

        public function update_isActive($db, $uid, $boolean){

            $sql = "UPDATE `user` u
                        SET u.isActive = '$boolean'
                        WHERE u.uid = '$uid'";

            return $stmt = $db->ejecutar($sql);
        }

        public function delete_user($db, $uid){

            $sql = "DELETE
                        FROM `user` u
                        WHERE u.uid = '$uid'";

            return $stmt = $db->ejecutar($sql);
        }

        public function delete_userLocal($db, $uid){

            $sql = "DELETE
                        FROM `user_local` u
                        WHERE u.uid = '$uid'";

            return $stmt = $db->ejecutar($sql);
        }

        public function delete_userLog($db, $uid){

            $sql = "DELETE
                        FROM `user_log` u
                        WHERE u.uid = '$uid'";

            return $stmt = $db->ejecutar($sql);
        }

        public function select_recover_email($db, $email){

			$sql = "SELECT u.uid, u.username, u.email, u.phone
                        FROM `user_local` u
                        WHERE u.email = '$email' AND u.password NOT LIKE ('')";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }

        public function update_new_passwoord($db, $uid, $password){

            $sql = "UPDATE `user_local` u
                        SET u.password = '$password'
                        WHERE u.uid = '$uid'";

            return $stmt = $db->ejecutar($sql);
        }

        public function select_userLogin($db, $username){

			$sql = "SELECT u.uid, ul.username, ul.password, ul.phone, u.isActive, lg.login_time, lg.login_attempts
                        FROM `user` u
                        INNER JOIN `user_local` ul ON u.uid = ul.uid
                        INNER JOIN `user_log` lg ON u.uid = lg.uid
                        WHERE ul.username = '$username'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }

        public function update_login_attempts($db, $uid, $login_time, $login_attempts){

            $sql = "UPDATE `user_log` u
                        SET u.login_time = '$login_time', u.login_attempts = '$login_attempts'
                        WHERE u.uid = '$uid'";

            return $stmt = $db->ejecutar($sql);
        }

        public function update_otp($db, $uid, $otp, $expired_token){

            $sql = "UPDATE `user_log` u
                        SET u.otp = '$otp', u.expired_token = '$expired_token', u.login_attempts = 0, u.otp_attempts = 0
                        WHERE u.uid = '$uid'";

            return $stmt = $db->ejecutar($sql);
        }

        public function select_otp($db, $uid){

			$sql = "SELECT u.uid, ul.username, ul.phone, lg.otp, lg.otp_attempts, lg.expired_token
                        FROM `user` u
                        INNER JOIN `user_local` ul ON u.uid = ul.uid
                        INNER JOIN `user_log` lg ON u.uid = lg.uid
                        WHERE u.uid = '$uid'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }

        public function update_otp_attempts($db, $uid, $otp_attempts){

            $sql = "UPDATE `user_log` u
                        SET u.otp_attempts = '$otp_attempts'
                        WHERE u.uid = '$uid'";

            return $stmt = $db->ejecutar($sql);
        }

        public function select_data_user($db, $uid, $provider){
            if ($provider == 'local') {
                $sql = "SELECT u.uid, ul.username, ul.password, ul.email, ul.phone, u.type_user, ul.avatar
                            FROM `user` u
                            INNER JOIN `user_local` ul ON u.uid = ul.uid
                            WHERE u.uid = '$uid'";
            } else {
                $sql = "SELECT u.uid, up.username, up.email, up.phone, u.type_user, up.avatar
                            FROM `user` u
                            INNER JOIN `user_$provider` up ON u.uid = up.uid
                            WHERE u.uid = '$uid'";
            }

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }
    }

?>