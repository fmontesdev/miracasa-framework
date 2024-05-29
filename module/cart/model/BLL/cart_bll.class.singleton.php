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
			$checkCart = $this -> dao -> select_one_lineCart($this->db, $args[0], $token_dec['uid']);
			// return $checkCart;

			// Comprobar que la linea del carrito no existe, entonces inserta
			if (!$checkCart) {
				$rdo = $this -> dao -> insert_cart($this->db, $args[0], $token_dec['uid']);
				$data_re = $this -> dao -> select_realestate($this->db, $args[0]);
				$data_qty = $this -> dao -> select_totalQty($this->db, $token_dec['uid']);

				if ($data_qty) {
					$qty_value = get_object_vars($data_qty); //serializa objeto
				}else {
					return "error_cart";
				}

				$data = array("msg" => "insert done", "re" => $data_re, "qty" => $qty_value['quantity']);
				return $data;
			} else { // Comprobar que la linea del carrito existe, entonces actualiza
				$cart_value = get_object_vars($checkCart); //serializa objeto
				
				$data_re = $this -> dao -> select_realestate($this->db, $args[0]);
				
				if ($data_re) {
					$re_value = get_object_vars($data_re); //serializa objeto
				}else {
					return "error_realestate";
				}

				// return [$cart_value['quantity'], $re_value['stock']];
				if ($cart_value['quantity'] < $re_value['stock']) { // Comprobar que hay suficiente stock
					$rdo = $this -> dao -> update_cart($this->db, $args[0], $token_dec['uid']);
					$data_qty = $this -> dao -> select_totalQty($this->db, $token_dec['uid']);

					if ($data_qty) {
						$qty_value = get_object_vars($data_qty); //serializa objeto
					}else {
						return "error_cart";
					}

					$data = array("msg" => "update done", "re" => $data_re, "qty" => $qty_value['quantity']);
					return $data;
				} else {
					return "insuficient stock";
				}
			}
		}

		public function get_select_cart_BLL($token) {
			// return $token;

			$token_dec = middleware_auth::decode_token('access', $token);
			$cart = $this -> dao -> select_cart($this->db, $token_dec['uid']);

			if ($cart) {
				return $cart;
			} else {
				return "error_cart";
			}
		}
	}