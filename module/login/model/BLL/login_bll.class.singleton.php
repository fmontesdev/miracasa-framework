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
			// return $checkUsername;

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
				$token_email = middleware_auth::create_token("verify", null, $args[0]); // creamos token JWT con tiempo de expiración

				$rdo = $this -> dao -> insert_user($this->db, $args[0], $hashed_pass, $args[2], $avatar);

				if (!$rdo) {
					return "error";
				} else {
					$message = [ 'type' => 'validate',
								'token' => $token_email, 
								'toEmail' =>  $args[2]];
					$email = json_decode(mail::send_email($message), true);
					return "done";
				}
			}
		}

		public function get_login_BLL($args) {
			$rdo = $this -> dao -> select_userLogin($this->db, $args[0]);

			$value = "";
			if ($rdo) {
				$value = get_object_vars($rdo); //serializa objeto
			}else {
				return "error_user";
			}

			// return json_encode($value['username']);

            if ($rdo == "error_user") {
                return "error_user";
            } else if (password_verify($args[1], $value['password']) && $value['isActive'] == 'true') { //compara el password introducido con el password de base de datos
				// return $data = [$value['id_user'], $value['username']];
                $accessToken = middleware_auth::create_token("access", $value['id_user'], $value['username']);
				// return $accessToken;
                $refreshToken = middleware_auth::create_token("refresh", $value['id_user'], $value['username']);
                $token = array("access" => $accessToken, "refresh" => $refreshToken); // array asociativo
                $_SESSION['username'] = $value['username']; //guardamos usuario en cookie (servidor)
                $_SESSION['tiempo'] = time(); //guardamos momento exacto del login en cookie (servidor)
                return $token;
            } else if (password_verify($args[1], $value['password']) && $value['isActive'] == 'false') {
                return "user_inactive";
			} else {
                return "error_passwd";
            }
		}

		public function get_verify_email_BLL($token_email) {
			$tokenEmail_dec = middleware_auth::decode_token('verify', $token_email);

			if($tokenEmail_dec['exp'] > time()){
				$this -> dao -> update_verify_email($this->db, $tokenEmail_dec['username']);
				return "verify";
			} else {
				$this -> dao -> delete_verify_email($this->db, $tokenEmail_dec['username']);
				return "fail";
			}
		}

		public function get_send_recover_email_BBL($email_recover) {
			$email_user = $this -> dao -> select_recover_email($this->db, $email_recover);
			$token_email = common::generate_Token_secure(20);

			if (!empty($email_user)) {
				$this -> dao -> update_recover_email($this->db, $email_recover, $token_email);
                $message = ['type' => 'recover', 
                            'token' => $token_email, 
                            'toEmail' => $email_recover];
                $email = json_decode(mail::send_email($message), true);
				return "done";   
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
			$rdo = $this -> dao -> select_data_user($this->db, $accessToken_dec['username']);
			return $rdo;
		}

		public function get_logout_BLL() {
			unset($_SESSION['username']); //elimina valor de $_SESSION
			unset($_SESSION['tiempo']); //elimina valor de $_SESSION
			session_destroy();
			return "logout done";
		}

		public function get_control_user_BBL($args) {
			$accessToken_dec = middleware_auth::decode_token('access', $args[0]);
			$refreshToken_dec = middleware_auth::decode_token('refresh', $args[1]);

			if ($accessToken_dec['exp'] < time() && $refreshToken_dec['exp'] > time()) { // accessToken expirado y refreshToken activo -> actualizamos accessToken
				$new_accessToken = middleware_auth::create_token('access', $accessToken_dec['id_user'], $accessToken_dec['username']);
				$new_accessToken_dec = middleware_auth::decode_token('access', $new_accessToken);
				if (isset($_SESSION['username']) && ($_SESSION['username'] == $new_accessToken_dec['username']) && ($_SESSION['username'] == $refreshToken_dec['username'])) {
					return $new_accessToken;
				} else {
					return "Wrong_User";
				}
			} else if ( ($accessToken_dec['exp'] > time() && $refreshToken_dec['exp'] < time())) { // accessToken activo y refreshToken expirado -> logout
				return "ExpirationTime_Token";
			} else if (($accessToken_dec['exp'] < time()) && ($refreshToken_dec['exp'] < time())) { // accessToken expirado y refreshToken expirado -> logout
				return "ExpirationTime_Token";
			} else if (isset($_SESSION['username']) && ($_SESSION['username'] == $accessToken_dec['username']) && ($_SESSION['username'] == $refreshToken_dec['username'])) {
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