<?php
    class home_dao {
        static $_instance;

        private function __construct() {
        }

        public static function getInstance() {
            if(!(self::$_instance instanceof self)){
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function select_touristcat($db) {

            $sql= "SELECT * FROM `tourist_cat` ORDER BY id_touristcat";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar_array($stmt);
        }

		public function select_type($db) {

            $sql= "SELECT * FROM `type` WHERE subtype = 'Inmueble' ORDER BY id_type";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar_array($stmt);
        }

		public function select_category($db) {

            $sql= "SELECT * FROM `category` ORDER BY id_cat";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar_array($stmt);
        }

		public function select_operation($db) {

            $sql= "SELECT * FROM `operation` ORDER BY id_op";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar_array($stmt);
        }

		public function select_city($db) {

            $sql= "SELECT * FROM `city` ORDER BY id_city LIMIT 25";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar_array($stmt);
        }

		public function select_lastsearch($db, $filters) {

            $sql_selectFilter = "";
			$sql_innerFilter = "";
			$sql_whereFilter = "";
			$sql_orderFilter = "";
	
			for ($i=0; $i < count($filters); $i++) {
				if ($filters[$i][0] == 'name_cat') {
					$sql_selectFilter .= ", cat." . $filters[$i][0];
					$sql_innerFilter .= " INNER JOIN `belong_to_cat` bcat ON r.id_realestate = bcat.id_realestate
										INNER JOIN `category` cat ON cat.id_cat = bcat.id_cat";
				} else if ($filters[$i][0] == 'name_extras') {
					$sql_innerFilter .= " INNER JOIN `has_extras` hex ON r.id_realestate = hex.id_realestate
										INNER JOIN `extras` ex ON ex.id_extras = hex.id_extras";
				} else if ($filters[$i][0] == 'name_touristcat') {
					$sql_innerFilter .= " INNER JOIN `tourist_cat` toc ON toc.id_touristcat = c.id_touristcat";
				} else if ($filters[$i][1] == 'Más baratos') {
					$sql_orderFilter .= " ORDER BY price ASC";
				} else if ($filters[$i][1] == 'Más caros') {
					$sql_orderFilter .= " ORDER BY price DESC";
				} else if ($filters[$i][1] == 'Más grandes') {
					$sql_orderFilter .= " ORDER BY area DESC";
				} else if ($filters[$i][1] == 'Más pequeños') {
					$sql_orderFilter .= " ORDER BY area ASC";
				}
				if ($i==0){
					if ($filters[$i][0] == 'name_extras') { // checkbox
						for ($j=0; $j < count($filters[$i][1]); $j++) {
							if ($j==0){
								$sql_whereFilter .= " WHERE (" . $filters[$i][0] . " = '" . $filters[$i][1][$j] . "'";
							} else {
								$sql_whereFilter .= " OR " . $filters[$i][0] . " = '" . $filters[$i][1][$j] . "'";
							}
						}
						$sql_whereFilter .= ")";
					} else if ($filters[$i][0] == 'price') { // rango
						$sql_whereFilter .= " WHERE " . $filters[$i][0] . " BETWEEN " . $filters[$i][1][1] . " AND " . $filters[$i][2][1];
					} else if ($filters[$i][1] == '+5') { // rooms - bathrooms
						$sql_whereFilter .= " WHERE " . $filters[$i][0] . " >= '" . $filters[$i][1] . "'";
					} else if ($filters[$i][0] != 'order'){
						$sql_whereFilter .= " WHERE " . $filters[$i][0] . " = '" . $filters[$i][1] . "'";
					}
				}else {
					if ($filters[$i][0] == 'name_extras') { // checkbox
						for ($j=0; $j < count($filters[$i][1]); $j++) {
							if ($j==0){
								$sql_whereFilter .= " AND (" . $filters[$i][0] . " = '" . $filters[$i][1][$j] . "'";
							} else {
								$sql_whereFilter .= " OR " . $filters[$i][0] . " = '" . $filters[$i][1][$j] . "'";
							}
						}
						$sql_whereFilter .= ")";
					} else if ($filters[$i][0] == 'price') { // rango
						$sql_whereFilter .= " AND " . $filters[$i][0] . " BETWEEN " . $filters[$i][1][1] . " AND " . $filters[$i][2][1];
					} else if ($filters[$i][1] == '+5') { // rooms - bathrooms
						$sql_whereFilter .= " AND " . $filters[$i][0] . " >= '" . $filters[$i][1] . "'";
					} else if ($filters[$i][0] != 'order'){
						$sql_whereFilter .= " AND " . $filters[$i][0] . " = '" . $filters[$i][1] . "'";
					}
				}        
			}

            $sql = "SELECT r.id_realestate, t.name_type, t.subtype, o.name_op, s.price, c.name_city, c.province, r.area, r.rooms,
					r.bathrooms, r.floor, r.description, GROUP_CONCAT(i.img_realestate SEPARATOR ':') AS img_realestate". $sql_selectFilter ."
						FROM `real_estate` r
						INNER JOIN `belong_to_type` bt ON  r.id_realestate = bt.id_realestate
						INNER JOIN `type` t ON t.id_type = bt.id_type
						INNER JOIN `is_traded` s ON r.id_realestate = s.id_realestate
						INNER JOIN `operation` o ON o.id_op = s.id_op
						INNER JOIN `img_realestate` i ON r.id_realestate = i.id_realestate
						INNER JOIN `city` c ON r.id_city = c.id_city "
						. $sql_innerFilter
						. $sql_whereFilter .
						" GROUP BY r.id_realestate"
						. $sql_orderFilter;

            $stmt = $db -> ejecutar($sql);
            return $db -> listar_array_img($stmt);
        }

    }
?>