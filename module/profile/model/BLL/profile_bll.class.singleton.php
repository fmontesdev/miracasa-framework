<?php
	class profile_bll {
		private $dao;
		private $db;
		static $_instance;

		function __construct() {
			$this -> dao = profile_dao::getInstance();
			$this -> db = db::getInstance();
		}

		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function get_load_bills_BLL($token) {
			$token_dec = middleware_auth::decode_token('access', $token);
			$user = $this -> dao -> select_user($this->db, $token_dec['uid'], $token_dec['provider']);
			
			if ($user) {
				$bills = $this -> dao -> select_bill($this->db, $token_dec['uid']);
				if ($bills) {
					$data = array("user" => $user, "bills" => $bills);
					return $data;
				} else {
					$data = array("user" => $user, "bills" => "no_bills");
					return $data;
				}
			} else {
				return "error_user";
			}
		}
	}