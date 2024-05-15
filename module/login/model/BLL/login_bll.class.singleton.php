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

	}