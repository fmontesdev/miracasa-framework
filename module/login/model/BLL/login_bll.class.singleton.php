<?php
	class login_bll {
		private $dao;
		private $db;
		static $_instance;

		function __construct() {
			$this -> dao = login_dao::getInstance();
			$this -> db = db::getInstance();
		}

		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function get_register_BLL($args) {
			$checkUsername = $this -> dao -> select_userReg($this->db, $args[0]);

			// Comprobar que el usuario no existe
			if ($checkUsername) {
				$check = false;
				return "error_user";
			} else {
				// Comprobar que el email no existe
				$checkEmail = $this -> dao -> select_email($this->db, $args[2]);
	
				if ($checkEmail) {
					$check = false;
					return "error_email";
				} else {
					$check = true;
				}
			}

			// Si no existe ni el username ni el email creará el usuario
			if ($check) {
				$hashed_pass = password_hash($args[1], PASSWORD_DEFAULT, ['cost' => 12]); // password_hash() función para encriptar password muy segura
				// $hashavatar = md5(strtolower(trim($email))); // md5() función para encriptar con menos seguridad
				// $avatar = "https://i.pravatar.cc/500?u=$hashavatar";
				$avatar = "https://api.dicebear.com/8.x/initials/svg?backgroundColor=2eca6a&size=40&scale=110&radius=50&seed=$args[0]";
				$uid = common::generate_Token_secure(12);
				$token_email = middleware_auth::create_token("verify", $uid, $args[0]); // creamos token JWT con tiempo de expiración
				$rdo = $this -> dao -> insert_user($this->db, $uid, $args[0], $hashed_pass, $args[2], $avatar);

				if (!$rdo) {
					return "error";
				} else {
					$message = [ 'type' => 'validate',
								'token' => $token_email, 
								'toEmail' =>  $args[2]];
					$email = mail::send_email($message);
					return $email;
					if (!empty($email)) {
						return "done";  
					} 
				}
			}
		}

		public function get_login_BLL($args) {
			$rdo = $this -> dao -> select_userLogin($this->db, $args[0]);

			if ($rdo) {
				$value = get_object_vars($rdo); //serializa objeto
			}else {
				$rdo = "error_user";
			}

            if ($rdo == "error_user") {
                return "error_user";
            } else if (password_verify($args[1], $value['password']) && $value['isActive'] == 'true') { // password correcto y usuario activo
                $accessToken = middleware_auth::create_token("access", $value['uid'], $value['username']);
                $refreshToken = middleware_auth::create_token("refresh", $value['uid'], $value['username']);
                $token = array("access" => $accessToken, "refresh" => $refreshToken); // array asociativo
                $_SESSION['uid'] = $value['uid']; //guardamos usuario en cookie (servidor)
                $_SESSION['tiempo'] = time(); //guardamos momento exacto del login en cookie (servidor)
                return $token;
            } else if (password_verify($args[1], $value['password']) && $value['isActive'] == 'false') {  // password correcto y usuario inactivo
                return "user_inactive";
			} else { // password incorrecto
				if (($value['login_attempts'] == 0) || ($value['login_attempts'] < 2 && (time() - $value['login_time']) < 600)) { // login_attempts entre 0 y 2, y con menos de 10 minutos entre errores de password
					$login_time = time();
					$login_attempts = $value['login_attempts'] + 1;
					$this -> dao -> update_login_attempts($this->db, $value['uid'], $login_time, $login_attempts);  // actualizamos los intentos fallidos de login junto la hora
					return "error_passwd";
				} else if ($value['login_attempts'] >= 2) { // login_attempts igual a 2 (al tercer fallo de password)
					$otp = common::generate_Token_secure(4);
					$expiredToken = middleware_auth::create_token("verify", $value['uid'], $value['username']);
					$this -> dao -> update_otp($this->db, $value['uid'], $otp, $expiredToken); // guardamos OTP y token
					$result_otp = otp::send_msg($otp, $value['phone']);
					if (!empty($result_otp)) {
						$data = array("msg" => "otp", "uid" => $value['uid']);
						return $data;  
					}
				}
            }
		}

		public function get_otp_login_BLL($args) {
			$rdo = $this -> dao -> select_otp($this->db, $args[0]); // seleccionamos OTP del usuario

			if ($rdo) {
				$value = get_object_vars($rdo); //serializa objeto
			}else {
				$rdo = "error_user";
			}

			if ($rdo == "error_user") {
				return "error_user";
			} else if ($value['otp'] === $args[1]) { // codigo OTP válido
				$expiredToken_dec = middleware_auth::decode_token('verify', $value['expired_token']);
				
				if ($expiredToken_dec['exp'] > time()) { // tiempo de expiración del token otp válido
					$otp_empty = 'NULL';
					$expiredToken_empty = 'NULL';
					$this -> dao -> update_otp($this->db, $value['uid'], $otp_empty, $expiredToken_empty); // borramos OTP

					$accessToken = middleware_auth::create_token("access", $value['uid'], $value['username']);
					$refreshToken = middleware_auth::create_token("refresh", $value['uid'], $value['username']);
					$token = array("access" => $accessToken, "refresh" => $refreshToken); // array asociativo
					$_SESSION['uid'] = $value['uid']; //guardamos usuario en cookie (servidor)
					$_SESSION['tiempo'] = time(); //guardamos momento exacto del login en cookie (servidor)
					return $token;
				} else { // tiempo de expiración del token otp caducado
					$otp = common::generate_Token_secure(4);
					$expiredToken = middleware_auth::create_token("verify", $value['uid'], $value['username']);
					$this -> dao -> update_otp($this->db, $value['uid'], $otp, $expiredToken); // guardamos OTP y token
					$result_otp = otp::send_msg($otp, $value['phone']);
					if (!empty($result_otp)) {
						$data = array("msg" => "expired_token", "uid" => $value['uid']);
						return $data;  
					}
				}
			} else { // código OTP incorrecto
				$otp_attempts = $value['otp_attempts'] + 1;
				$this -> dao -> update_otp_attempts($this->db, $value['uid'], $otp_attempts); // desactivamos usuario
				if ($value['otp_attempts'] >= 2) {
					$this -> dao -> update_otp_isActive($this->db, $value['uid']); // desactivamos usuario
					return "unauthenticated";
				} else {
					$data = array("msg" => "otp_attempts", "otp_attempts" => $value['otp_attempts']);
					return $data;
				}
				// desactivar usuario??
				// proximo login también otp??
			}
		}

		public function get_social_login_BLL($args) {
			$user = $this -> dao -> select_social_login($this->db, $args[0]);

			if (empty($user)) {
				$this -> dao -> insert_social_login($this->db, $args[0], $args[1], $args[2], $args[3], $args[4]);
            }

			$accessToken = middleware_auth::create_token("access", $args[0], $args[1]);
			$refreshToken = middleware_auth::create_token("refresh", $args[0], $args[1]);
			$token = array("access" => $accessToken, "refresh" => $refreshToken);
			$_SESSION['uid'] = $args[0];
            $_SESSION['tiempo'] = time();
			return $token;
		}

		public function get_verify_email_BLL($token_email) {
			$tokenEmail_dec = middleware_auth::decode_token('verify', $token_email);

			if($tokenEmail_dec['exp'] > time()){
				$this -> dao -> update_verify_email($this->db, $tokenEmail_dec['uid']);
				return "verify";
			} else {
				$this -> dao -> delete_verify_email($this->db, $tokenEmail_dec['uid']);
				return "fail";
			}
		}

		public function get_send_recover_email_BBL($email_recover) {
			$emailRecover = $this -> dao -> select_recover_email($this->db, $email_recover);
			$email_userLocal = get_object_vars($emailRecover); //serializa objeto
			$value = explode('/', $email_userLocal['email']);
			$token_email = common::generate_Token_secure(20);

			if (!empty($emailRecover) && $value[1] == 'local') {
				$this -> dao -> update_recover_email($this->db, $email_recover, $token_email);
                $message = ['type' => 'recover', 
                            'token' => $token_email, 
                            'toEmail' => $email_recover];
                $email = mail::send_email($message);
				return $email;
					if (!empty($email)) {
						return "done";  
					}   
            }else {
                return "error";
            }
		}

		public function get_verify_token_BLL($token_email) {
			$rdo = $this -> dao -> select_verify_email($this->db, $token_email);

			if(!$rdo){
				return "fail";
			} else {
				return "verify";
			}
		}

		public function get_new_password_BLL($args) {
			$hashed_pass = password_hash($args[1], PASSWORD_DEFAULT, ['cost' => 12]);
			$rdo = $this -> dao -> update_new_passwoord($this->db, $args[0], $hashed_pass);
			
			if (!$rdo) {
				return "fail";
			} else {
				return "done";
			}
		}

		public function get_data_user_BLL($token) {
			$accessToken_dec = middleware_auth::decode_token('access', $token);
			$rdo = $this -> dao -> select_data_user($this->db, $accessToken_dec['uid']);
			return $rdo;
		}

		public function get_logout_BLL() {
			unset($_SESSION['uid']); //elimina valor de $_SESSION
			unset($_SESSION['tiempo']); //elimina valor de $_SESSION
			session_destroy();
			return "logout done";
		}

		public function get_control_user_BBL($args) {
			$accessToken_dec = middleware_auth::decode_token('access', $args[0]);
			$refreshToken_dec = middleware_auth::decode_token('refresh', $args[1]);

			if ($accessToken_dec['exp'] < time() && $refreshToken_dec['exp'] > time()) { // accessToken expirado y refreshToken activo -> actualizamos accessToken
				$new_accessToken = middleware_auth::create_token('access', $accessToken_dec['uid'], $accessToken_dec['username']);
				$new_accessToken_dec = middleware_auth::decode_token('access', $new_accessToken);
				if (isset($_SESSION['uid']) && ($_SESSION['uid'] == $new_accessToken_dec['uid']) && ($_SESSION['uid'] == $refreshToken_dec['uid'])) {
					return $new_accessToken;
				} else {
					return "Wrong_User";
				}
			} else if ( ($accessToken_dec['exp'] > time() && $refreshToken_dec['exp'] < time())) { // accessToken activo y refreshToken expirado -> logout
				return "ExpirationTime_Token";
			} else if (($accessToken_dec['exp'] < time()) && ($refreshToken_dec['exp'] < time())) { // accessToken expirado y refreshToken expirado -> logout
				return "ExpirationTime_Token";
			} else if (isset($_SESSION['uid']) && ($_SESSION['uid'] == $accessToken_dec['uid']) && ($_SESSION['uid'] == $refreshToken_dec['uid'])) {
				// accessToken activo - refreshToken activo, y username de cookie y tokens valido
				return "Correct_User";
			} else { // username de cookie y tokens inválido
				return "Wrong_User";
			}
		}

		public function get_control_activity_BLL() {
			if (!isset($_SESSION['tiempo'])) {
				return "inactivo";
			} else {
				if ((time() - $_SESSION['tiempo']) >= 30*60) { //1800s=30min
					return "inactivo";
				} else {
					return "activo";
				}
			}
		}

		public function get_refresh_cookie_BLL() {
			session_regenerate_id();
        	return "Refresh cookie correctly";
		}

	}