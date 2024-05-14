<?php
	class home_bll {
		private $dao;
		private $db;
		static $_instance;

		function __construct() {
			$this -> dao = home_dao::getInstance();
			$this -> db = db::getInstance();
		}

		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function get_carouselTouristcat_BLL() {
			return $this -> dao -> select_touristcat($this -> db);
		}

		public function get_carouselType_BLL() {
			return $this -> dao -> select_type($this -> db);
		}

		public function get_carouselCategory_BLL() {
			return $this -> dao -> select_category($this -> db);
		}

		public function get_carouselOperation_BLL() {
			return $this -> dao -> select_operation($this -> db);
		}

	}
?>