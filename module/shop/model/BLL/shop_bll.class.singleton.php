<?php
	class shop_bll {
		private $dao;
		private $db;
		static $_instance;

		function __construct() {
			$this -> dao = shop_dao::getInstance();
			$this -> db = db::getInstance();
		}

		public static function getInstance() {
			if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function get_all_realestates_BLL($args) {
			if ($args[2] != null) {
				$accessToken_dec = middleware_auth::decode_token('access', $args[2]);
				$id_user = $accessToken_dec['id_user'];
			} else {
				$id_user = 'null';
			}

			return $this -> dao -> select_all_realEstates($this->db, $args[0], $args[1], $id_user);
		}

		public function get_details_realestate_BLL($args) {
			if ($args[1] != null) {
				$accessToken_dec = middleware_auth::decode_token('access', $args[1]);
				$id_user = $accessToken_dec['id_user'];
			} else {
				$id_user = 'null';
			}

			$this -> dao -> insert_visited($this->db, $args[0]);
			$dates_realEstate = $this -> dao -> select_one_realEstate($this->db, $args[0]);
			$dates_images = $this -> dao -> select_imgs_realEstate($this->db, $args[0]);
			$dates_extras = $this -> dao -> select_extras_realEstate($this->db, $args[0]);
			$dates_likes = $this -> dao -> select_likes_realEstate($this->db, $args[0], $id_user);

			if (!empty($dates_realEstate || $dates_images || $dates_extras || $dates_likes)) {
				$rdo = array();
				$rdo[0] = $dates_realEstate; //introduce objeto con datos de la vivienda en posición 0
				$rdo[1] = $dates_images; //introduce array de imagenes en posición 1
				$rdo[2] = $dates_extras; //introduce array de extras en posición 2
				$rdo[3] = $dates_likes; //introduce objeto con likes de vivienda en posición 3
			}

			return $rdo;
		}

		public function get_load_filters_BLL() {
			$dates_city = $this -> dao -> select_filter_city($this->db);
			$dates_cat = $this -> dao -> select_filter_cat($this->db);
			$dates_type = $this -> dao -> select_filter_type($this->db);
			$dates_type_flat = $this -> dao -> select_filter_flat($this->db);
			$dates_type_house = $this -> dao -> select_filter_house($this->db);
			$dates_op = $this -> dao -> select_filter_op($this->db);
			$dates_extras = $this -> dao -> select_filter_extras($this->db);
			$dates_touristcat = $this -> dao -> select_filter_touristcat($this->db);

			if (!empty($dates_city || $dates_cat || $dates_type || $dates_type_flat || $dates_type_house || $dates_op || $dates_extras || $dates_touristcat)) {
				$rdo = array();
				$rdo[0] = $dates_city;
				$rdo[1] = $dates_cat;
				$rdo[2][0] = $dates_type;
				$rdo[2][1] = $dates_type_flat;
				$rdo[2][2] = $dates_type_house;
				$rdo[3] = $dates_op;
				$rdo[4] = $dates_extras;
				$rdo[5] = $dates_touristcat;
			}

			return $rdo;
		}

		public function get_filters_shop_BLL($args) {
			if ($args[3] != null) {
				$accessToken_dec = middleware_auth::decode_token('access', $args[3]);
				$id_user = $accessToken_dec['id_user'];
			} else {
				$id_user = 'null';
			}
			
			return $this -> dao -> filters_shop($this->db, $args[0], $args[1], $args[2], $id_user);
		}

		public function get_realestates_related_BLL($args) {
			return $this -> dao -> select_realestates_related($this->db, $args[0], $args[1], $args[2], $args[3]);
		}

		public function get_count_all_BLL() {
			return $this -> dao -> select_count_all($this->db);
		}

		public function get_count_filtersShop_BLL($filters) {
			return $this -> dao -> select_count_filtersShop($this->db, $filters);
		}

		public function get_count_realestates_related_BLL($args) {
			return $this -> dao -> select_count_related($this->db, $args[0], $args[1]);
		}

	}
?>