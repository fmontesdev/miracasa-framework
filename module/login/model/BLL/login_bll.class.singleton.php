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

	}