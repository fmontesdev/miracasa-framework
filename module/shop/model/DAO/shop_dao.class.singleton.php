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

    }

?>

