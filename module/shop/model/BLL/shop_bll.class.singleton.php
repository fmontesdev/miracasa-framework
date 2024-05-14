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

	}
?>