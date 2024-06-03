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
			$bill = $this -> dao -> select_bill($this->db, $token_dec['uid'], $token_dec['provider']);

			if ($bill) {
				return $bill;
			} else {
				return "no_bills";
			}
		}
	}