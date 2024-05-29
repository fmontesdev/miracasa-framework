<?php
	class cart_bll {
		private $dao;
		private $db;
		static $_instance;

		function __construct() {
			$this -> dao = cart_dao::getInstance();
			$this -> db = db::getInstance();
		}

		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function get_insert_cart_BLL($args) {
			// return $args;

			$token_dec = middleware_auth::decode_token('access', $args[1]);
			$checkCart = $this -> dao -> select_cart($this->db, $args[0], $token_dec['uid']);
			// return $checkCart;

			// Comprobar que la linea del carrito no existe
			if (!$checkCart) {
				$rdo = $this -> dao -> insert_cart($this->db, $args[0], $token_dec['uid']);
				$data_re = $this -> dao -> select_realestate($this->db, $args[0]);
				$data = array("msg" => "insert done", "re" => $data_re);
				return $data;
			} else {
				$cart_value = get_object_vars($checkCart); //serializa objeto
				
				$data_re = $this -> dao -> select_realestate($this->db, $args[0]);
				
				if ($data_re) {
					$re_value = get_object_vars($data_re); //serializa objeto
				}else {
					return "error_realestate";
				}

				// return [$cart_value['quantity'], $re_value['stock']];
				if ($cart_value['quantity'] < $re_value['stock']) {
					$rdo = $this -> dao -> update_cart($this->db, $args[0], $token_dec['uid']);
					$data = array("msg" => "update done", "re" => $data_re);
					return $data;
				} else {
					return "insuficient stock";
				}
			}
		}
	}