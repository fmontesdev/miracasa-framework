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
				$rdo = $this -> dao -> insert_user($this->db, $args[0], $args[1], $args[2]);

				if (!$rdo) {
					return "error";
				} else {
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
            } else if (password_verify($args[1], $value['password'])) { //compara el password introducido con el password de base de datos
				// return $data = [$value['id_user'], $value['username']];
                $accessToken = middleware_auth::create_token("access", $value['id_user'], $value['username']);
				// return $accessToken;
                $refreshToken = middleware_auth::create_token("refresh", $value['id_user'], $value['username']);
                $token = array("access" => $accessToken, "refresh" => $refreshToken); // array asociativo
                $_SESSION['username'] = $value['username']; //guardamos usuario en cookie (servidor)
                $_SESSION['tiempo'] = time(); //guardamos momento exacto del login en cookie (servidor)
                return $token;
            } else {
                return "error_passwd";
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

	}