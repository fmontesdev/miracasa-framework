<?php
	class cart_bll {
		private $dao;
		private $db;
		static $_instance;

		function __construct() {
			// $this -> dao = cart_dao::getInstance();
			$this -> db = db::getInstance();
		}

		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function get_insert_cart_BLL($args) {
			return $args;

			
		}
	}