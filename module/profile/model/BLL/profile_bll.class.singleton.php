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

		public function get_loadProfile_user_BLL($token) {
			$token_dec = middleware_auth::decode_token('access', $token);
			$user = $this -> dao -> select_user($this->db, $token_dec['uid'], $token_dec['provider']);
			
			if ($user) {
				return $user;
			} else {
				return "error_user";
			}
		}

		public function get_load_bills_BLL($token) {
			$token_dec = middleware_auth::decode_token('access', $token);
			
			$bills = $this -> dao -> select_bill($this->db, $token_dec['uid']);
			if ($bills) {
				return $bills;
			} else {
				return "no_bills";
			}
		}
	}