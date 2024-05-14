<?php
	class search_bll {
		private $dao;
		private $db;
		static $_instance;

		function __construct() {
			$this -> dao = search_dao::getInstance();
			$this->db = db::getInstance();
		}

		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function get_search_operation_BLL() {
			return $this -> dao -> search_operation($this->db);
		}

		public function get_search_touristCategory_BLL($operation) {
			return $this -> dao -> search_touristCategory($this->db, $operation);
		}

		public function get_search_autocomplete_BLL($args) {
			return $this -> dao -> search_autocomplete($this->db, $args[0], $args[1], $args[2]);
		}
	}
?>