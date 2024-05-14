<?php
    class shop_dao {
        static $_instance;
        
        private function __construct() {
        }
        
        public static function getInstance() {
            if(!(self::$_instance instanceof self)){
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function select_all_realEstates($db, $limit, $offset, $id_user) {

            $sql = "SELECT r.id_realestate, r.lat, r.lng, t.name_type, o.name_op, s.price, c.name_city, c.province, r.area, r.rooms, r.bathrooms, r.floor, r.description,
                    GROUP_CONCAT(DISTINCT i.img_realestate SEPARATOR ':') AS img_realestate, GROUP_CONCAT(DISTINCT l.id_user SEPARATOR ':') AS 'like'
                        FROM `real_estate` r
                        INNER JOIN `belong_to_type` bt ON  r.id_realestate = bt.id_realestate 
                        INNER JOIN `type` t ON t.id_type = bt.id_type
                        INNER JOIN `is_traded` s ON r.id_realestate = s.id_realestate 
                        INNER JOIN `operation` o ON o.id_op = s.id_op
                        INNER JOIN `img_realestate` i ON r.id_realestate = i.id_realestate
                        INNER JOIN `city` c ON r.id_city = c.id_city
                        LEFT JOIN `like` l ON r.id_realestate = l.id_realestate
                        WHERE t.name_type != 'Vivienda'	
                        GROUP BY r.id_realestate
                        LIMIT $limit OFFSET $offset";
                        //GROUP BY r.id_realestate, o.id_op";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar_array_img_like($stmt, $id_user);
        }

        public function insert_visited($db, $id_re){

            $sql = "UPDATE `real_estate` r 
                        SET r.visited = (r.visited + 1)
                        WHERE r.id_realestate = $id_re";

            $stmt = $db -> ejecutar($sql);
        }
        
        function select_one_realEstate($db, $id_re){

            $sql = "SELECT r.id_realestate, r.lat, r.lng, t.name_type, o.name_op, s.price,
                    c.name_city, c.province, r.area, r.rooms, r.bathrooms, r.floor, r.description
                        FROM `real_estate` r
                        INNER JOIN `belong_to_type` bt ON  r.id_realestate = bt.id_realestate
                        INNER JOIN `type` t ON t.id_type = bt.id_type
                        INNER JOIN `is_traded` s ON r.id_realestate = s.id_realestate
                        INNER JOIN `operation` o ON o.id_op = s.id_op
                        INNER JOIN `city` c ON r.id_city = c.id_city
                        WHERE r.id_realestate = $id_re AND t.name_type != 'Vivienda'
                        GROUP BY r.id_realestate";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar_object($stmt);
        }

        public function select_imgs_realEstate($db, $id_re){

            $sql = "SELECT i.id_img_re, i.img_realestate
                    FROM `img_realestate` i
                    WHERE i.id_realestate = $id_re";

            $stmt = $db -> ejecutar($sql);
            return $db -> listar_indexed_array($stmt);
        }

        public function select_extras_realEstate($db, $id_re){

            $sql = "SELECT e.id_extras, e.name_extras, e.img_extras
                    FROM `extras` e INNER JOIN `has_extras` he
                    ON e.id_extras = he.id_extras
                    WHERE he.id_realestate = $id_re";

            $stmt = $db->ejecutar($sql);
            return $db->listar_indexed_array($stmt);
        }

        public function select_likes_realEstate($db, $id_re, $id_user){

            $sql = "SELECT l.id_realestate, l.id_user AS user_like
                    FROM `real_estate` r LEFT JOIN `like` l ON r.id_realestate = l.id_realestate
                    WHERE r.id_realestate = $id_re";

            $stmt = $db->ejecutar($sql);
            $likeArray = $db->listar_array($stmt);

            $count_like = 0;
            foreach ($likeArray as $data) {
                if ($data['user_like'] == $id_user) {
                    $count_like = count($likeArray);
                }
		    }
		    $like['like'] = $count_like;

            return $like;
        }

        public function select_realestates_related($db, $id, $operation, $limit, $offset) {

            $sql_whereFilter = "";
            if ($operation != null) {
                $sql_whereFilter .= " AND o.name_op = '$operation'";
            }
    
            $sql = "SELECT r.id_realestate, r.lat, r.lng, t.name_type, o.name_op, s.price, c.name_city, c.province, r.area,
                    r.rooms, r.bathrooms, r.floor, r.description, GROUP_CONCAT(i.img_realestate SEPARATOR ':') AS img_realestate
                        FROM `real_estate` r
                        INNER JOIN `belong_to_type` bt ON  r.id_realestate = bt.id_realestate 
                        INNER JOIN `type` t ON t.id_type = bt.id_type
                        INNER JOIN `is_traded` s ON r.id_realestate = s.id_realestate 
                        INNER JOIN `operation` o ON o.id_op = s.id_op
                        INNER JOIN `img_realestate` i ON r.id_realestate = i.id_realestate
                        INNER JOIN `city` c ON r.id_city = c.id_city
                        WHERE t.name_type != 'Vivienda' AND r.id_realestate NOT IN($id)"
                        . $sql_whereFilter .
                        " GROUP BY r.id_realestate
                        LIMIT $limit OFFSET $offset";
                        //GROUP BY r.id_realestate, o.id_op";
            
            $stmt = $db -> ejecutar($sql);
            return $db -> listar_array_img($stmt);
        }

        public function select_filter_city($db){

            $sql = "SELECT c.id_city, c.name_city
                    FROM `city` c
                    ORDER BY c.name_city";

            $stmt = $db->ejecutar($sql);
            return $db->listar_indexed_array($stmt);
        }

        public function select_filter_cat($db){

            $sql = "SELECT c.id_cat, c.name_cat
                    FROM `category` c
                    ORDER BY c.id_cat";

            $stmt = $db->ejecutar($sql);
            return $db->listar_indexed_array($stmt);
        }

        public function select_filter_type($db){

            $sql = "SELECT t.id_type, t.name_type, t.subtype
                    FROM `type` t
                    WHERE t.subtype = 'Inmueble'
                    ORDER BY t.id_type";

            $stmt = $db->ejecutar($sql);
            return $db->listar_indexed_array($stmt);
        }

        public function select_filter_flat($db){

            $sql = "SELECT t.id_type, t.name_type, t.subtype
                    FROM `type` t
                    WHERE t.subtype = 'Piso'
                    ORDER BY t.id_type";

            $stmt = $db->ejecutar($sql);
            return $db->listar_indexed_array($stmt);
        }

        public function select_filter_house($db){

            $sql = "SELECT t.id_type, t.name_type, t.subtype
                    FROM `type` t
                    WHERE t.subtype = 'Casa'
                    ORDER BY t.id_type";

            $stmt = $db->ejecutar($sql);
            return $db->listar_indexed_array($stmt);
        }

        public function select_filter_op($db){

            $sql = "SELECT o.id_op, o.name_op
                    FROM `operation` o
                    ORDER BY o.id_op";

            $stmt = $db->ejecutar($sql);
            return $db->listar_indexed_array($stmt);
        }

        public function select_filter_extras($db){

            $sql = "SELECT e.id_extras, e.name_extras
                    FROM `extras` e
                    ORDER BY e.id_extras";

            $stmt = $db->ejecutar($sql);
            return $db->listar_indexed_array($stmt);
        }

        public function select_filter_touristcat($db){

            $sql = "SELECT c.id_touristcat, c.name_touristcat
                    FROM `tourist_cat` c
                    ORDER BY c.id_touristcat";

            $stmt = $db->ejecutar($sql);
            return $db->listar_indexed_array($stmt);
        }

        public function filters_shop($db, $filters, $limit, $offset, $id_user) {

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
                } else if ($filters[$i][1] == 'Más visitados') {
                    $sql_orderFilter .= " ORDER BY visited DESC";
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
    
            $sql = "SELECT r.id_realestate, r.lat, r.lng, t.name_type, t.subtype, o.name_op, s.price, c.name_city, c.province, r.area,
                     r.rooms, r.bathrooms, r.floor, r.description, GROUP_CONCAT(DISTINCT i.img_realestate SEPARATOR ':') AS img_realestate,
                     GROUP_CONCAT(DISTINCT l.id_user SEPARATOR ':') AS 'like'". $sql_selectFilter ."
                        FROM `real_estate` r
                        INNER JOIN `belong_to_type` bt ON  r.id_realestate = bt.id_realestate
                        INNER JOIN `type` t ON t.id_type = bt.id_type
                        INNER JOIN `is_traded` s ON r.id_realestate = s.id_realestate
                        INNER JOIN `operation` o ON o.id_op = s.id_op
                        INNER JOIN `img_realestate` i ON r.id_realestate = i.id_realestate
                        INNER JOIN `city` c ON r.id_city = c.id_city
                        LEFT JOIN `like` l ON r.id_realestate = l.id_realestate "
                        . $sql_innerFilter
                        . $sql_whereFilter .
                        " GROUP BY r.id_realestate"
                        . $sql_orderFilter .
                        " LIMIT $limit OFFSET $offset";
                        
            // $sql .= $sql_whereFilter . " GROUP BY r.id_realestate, o.id_op";
            // return $sql;
            
            $stmt = $db->ejecutar($sql);
            return $db->listar_array_img_like($stmt, $id_user);
        }

        function select_count_all($db){

            $sql = "SELECT COUNT(DISTINCT r.id_realestate) AS count
                        FROM `real_estate` r
                        INNER JOIN `belong_to_type` bt ON  r.id_realestate = bt.id_realestate 
                        INNER JOIN `type` t ON t.id_type = bt.id_type
                        INNER JOIN `is_traded` s ON r.id_realestate = s.id_realestate 
                        INNER JOIN `operation` o ON o.id_op = s.id_op
                        INNER JOIN `img_realestate` i ON r.id_realestate = i.id_realestate
                        INNER JOIN `city` c ON r.id_city = c.id_city
                        WHERE t.name_type != 'Vivienda'";

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }

        function select_count_filtersShop($db, $filters){

            $sql_innerFilter = "";
            $sql_whereFilter = "";
    
            for ($i=0; $i < count($filters); $i++) {
                if ($filters[$i][0] == 'name_cat') {
                    $sql_innerFilter .= " INNER JOIN `belong_to_cat` bcat ON r.id_realestate = bcat.id_realestate
                                        INNER JOIN `category` cat ON cat.id_cat = bcat.id_cat";
                } else if ($filters[$i][0] == 'name_extras') {
                    $sql_innerFilter .= " INNER JOIN `has_extras` hex ON r.id_realestate = hex.id_realestate
                                        INNER JOIN `extras` ex ON ex.id_extras = hex.id_extras";
                } else if ($filters[$i][0] == 'name_touristcat') {
                    $sql_innerFilter .= " INNER JOIN `tourist_cat` toc ON toc.id_touristcat = c.id_touristcat";
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
    
            $sql = "SELECT COUNT(DISTINCT r.id_realestate) AS count
                        FROM `real_estate` r
                        INNER JOIN `belong_to_type` bt ON  r.id_realestate = bt.id_realestate
                        INNER JOIN `type` t ON t.id_type = bt.id_type
                        INNER JOIN `is_traded` s ON r.id_realestate = s.id_realestate
                        INNER JOIN `operation` o ON o.id_op = s.id_op
                        INNER JOIN `img_realestate` i ON r.id_realestate = i.id_realestate
                        INNER JOIN `city` c ON r.id_city = c.id_city "
                        . $sql_innerFilter
                        . $sql_whereFilter;

            $stmt = $db->ejecutar($sql);
            return $db->listar_object($stmt);
        }

        public function select_count_related($db, $id, $operation){

            $sql = "SELECT r.id_realestate
                        FROM `real_estate` r
                        INNER JOIN `belong_to_type` bt ON  r.id_realestate = bt.id_realestate 
                        INNER JOIN `type` t ON t.id_type = bt.id_type
                        INNER JOIN `is_traded` s ON r.id_realestate = s.id_realestate 
                        INNER JOIN `operation` o ON o.id_op = s.id_op
                        INNER JOIN `img_realestate` i ON r.id_realestate = i.id_realestate
                        INNER JOIN `city` c ON r.id_city = c.id_city
                        WHERE t.name_type != 'Vivienda' AND r.id_realestate != $id AND o.name_op = '$operation'
                        GROUP BY r.id_realestate";

            $stmt = $db->ejecutar($sql);
            return $db->listar_indexed_array($stmt);
        }

    }

?>

