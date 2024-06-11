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
			$token_dec = middleware_auth::decode_token('access', $args[1]);
			$checkCart = $this -> dao -> select_lineCart($this->db, $args[0], $token_dec['uid']);
			
			// Comprueba que la linea del carrito no existe, entonces inserta
			if (!$checkCart) {
				$data_re = $this -> dao -> select_realestate($this->db, $args[0]);
				if (!$data_re) {
					return "error_realestate";
				}

				if ($data_re->stock >= 1) {
					$insert = $this -> dao -> insert_cart($this->db, $args[0], $token_dec['uid']);
					$data_qty = $this -> dao -> select_totalQty($this->db, $token_dec['uid']);
					if (!$insert || !$data_qty) {
						return "error_cart";
					}

					$data = array("msg" => "insert done", "re" => $data_re, "qty" => $data_qty->quantity);
					return $data;
				} else {
					return "insuficient stock";
				}
			} else { // Comprueba que la linea del carrito existe, entonces actualiza
				$data_re = $this -> dao -> select_realestate($this->db, $args[0]);
				if (!$data_re) {
					return "error_realestate";
				}

				if ($checkCart->quantity < $data_re->stock) { // Comprueba que hay suficiente stock
					$update = $this -> dao -> update_cart($this->db, $args[0], $token_dec['uid'], 1);
					$data_qty = $this -> dao -> select_totalQty($this->db, $token_dec['uid']);
					if (!$update || !$data_qty) {
						return "error_cart";
					}

					$data = array("msg" => "update done", "re" => $data_re, "qty" => $data_qty->quantity);
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
				return "no_cart";
			}
		}

		public function get_update_cart_BLL($args) {
			$token_dec = middleware_auth::decode_token('access', $args[1]);

			// elimina linea del carrito
			if ($args[3] == "delete") {
				$del = $this -> dao -> delete_lineCart($this->db, $args[0], $token_dec['uid']);
				if (!$del) {
					return "error_cart";
				}

				$cart = $this -> dao -> select_cart($this->db, $token_dec['uid']);
				if ($cart) {
					return [$cart, "delete", $args[0]];
				} else {
					return "no_cart";
				}
			} else {
				// recupera stock del producto
				$data_re = $this -> dao -> select_realestate($this->db, $args[0]);
				if (!$data_re) {
					return "error_realestate";
				}

				// actualiza linea del carrito
				if ((($args[2] + $args[3]) <= ($data_re->stock)) && (($args[2] + $args[3]) >= 1)) {  // Comprobar que hay suficiente stock, y la cantidad final no sea inferior a 1
					$update = $this -> dao -> update_cart($this->db, $args[0], $token_dec['uid'], $args[3]);
					if (!$update) {
						return "error_cart";
					}

					$cart = $this -> dao -> select_cart($this->db, $token_dec['uid']);
					if ($cart) {
						return [$cart, "update"];
					} else {
						return "error_cart";
					}
				}
			}
		}

		public function get_quantity_cart_BLL($token) {
			$token_dec = middleware_auth::decode_token('access', $token);
			$qty = $this -> dao -> select_totalQty($this->db, $token_dec['uid']);
			
			if ($qty->quantity != null) {
				return $qty;
			} else {
				return "no_cart";
			}
		}

		public function get_insert_bill_BLL($token) {
			$token_dec = middleware_auth::decode_token('access', $token);
			$bill = $this -> dao -> insert_bill($this->db, $token_dec['uid']);

			if ($bill) {
				$data = array("uid" => $token_dec['uid'], "provider" => $token_dec['provider'], "id_bill" => $bill->id_bill);
				return $data;
			} else {
				return "error_bill";
			}
		}

		public function get_insert_bill_detail_BLL($data) {
			$bill_detail = $this -> dao -> insert_bill_detail($this->db, $data['uid'], $data['id_bill']);
			
			if ($bill_detail) {
				return $bill_detail;
			} else {
				return "error_bill_detail";
			}
		}

		public function get_update_stock_BLL($uid) {
			$stock = $this -> dao -> update_stock($this->db, $uid);
			
			if ($stock) {
				return $stock;
			} else {
				return "error_stock";
			}
		}

		public function get_insert_purchase_log_BLL($uid) {
			$purchase_log = $this -> dao -> insert_purchase_log($this->db, $uid);
			
			if ($purchase_log) {
				return $purchase_log;
			} else {
				return "error_purchase_log";
			}
		}

		public function get_delete_cart_BLL($uid) {
			$del_cart = $this -> dao -> delete_cart($this->db, $uid);
			
			if ($del_cart) {
				return $del_cart;
			} else {
				return "error_cart";
			}
		}

		public function get_generate_pdf_BLL($data) {
			$bill_data = $this -> dao -> select_bill($this->db, $data['uid'], $data['provider'], $data['id_bill']);
			$bill_detail = $this -> dao -> select_bill_detail($this->db, $data['id_bill']);
			$bill_total = $this -> dao -> select_bill_total($this->db, $data['uid'], $data['id_bill']);
			
			if ($bill_data && $bill_detail && $bill_total) {
				$pdf = pdf::generate($bill_data, $bill_detail, $bill_total);
				if ($pdf == "done") {
					return "done";
				} else {
					return "error_pdf";
				}
			} else {
				return "error_bill";
			}
		}

		public function get_generate_qr_BLL($id_bill) {
			$url = URL_BILL_QR . $id_bill . '.pdf';			
			$qr = qr::generate($url, $id_bill);
			return $qr;
		}
	}