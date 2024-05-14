<?php
    class search_dao{
        static $_instance;

        private function __construct() {
        }
    
        public static function getInstance() {
            if(!(self::$_instance instanceof self)){
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        
        function search_operation($db){

			$sql = "SELECT o.id_op, o.name_op
			        FROM `operation` o
				    ORDER BY o.id_op";

			$stmt = $db -> ejecutar($sql);
            return $db -> listar_indexed_array($stmt);
        }

        function search_touristCategory($db, $operation){

            $sql_innerFilter = "";
            $sql_whereFilter = "";
    
            if ($operation !== 'null') {
                $sql_innerFilter .= " INNER JOIN `city` c ON t.id_touristcat = c.id_touristcat
                                    INNER JOIN `real_estate` r ON r.id_city = c.id_city
                                    INNER JOIN `is_traded` s ON r.id_realestate = s.id_realestate 
                                    INNER JOIN `operation` o ON o.id_op = s.id_op";
                $sql_whereFilter .= " WHERE o.name_op LIKE '$operation'";
            }

            $sql = "SELECT t.id_touristcat, t.name_touristcat
                    FROM `tourist_cat`t"
                    . $sql_innerFilter
                    . $sql_whereFilter .
                    " ORDER BY t.id_touristcat";

			$stmt = $db -> ejecutar($sql);
            return $db -> listar_indexed_array($stmt);
        }

        function search_autocomplete($db, $complete, $operation, $touristcat){

            $sql_innerFilter = "";
            $sql_whereFilter = "";
    
            if (!empty($operation)) {
                $sql_innerFilter .= " INNER JOIN `is_traded` s ON r.id_realestate = s.id_realestate 
                                    INNER JOIN `operation` o ON o.id_op = s.id_op";
                $sql_whereFilter .= " AND o.name_op LIKE '$operation'";
            }
            if (!empty($touristcat)) {
                $sql_innerFilter .= " INNER JOIN `tourist_cat` t ON t.id_touristcat = c.id_touristcat";
                $sql_whereFilter .= " AND t.name_touristcat LIKE '$touristcat'";
            }
    
            $sql = "SELECT c.id_city, c.name_city
                    FROM `real_estate` r
                    INNER JOIN `city` c ON r.id_city = c.id_city"
                    . $sql_innerFilter .
                    " WHERE c.name_city LIKE '$complete%'"
                    . $sql_whereFilter .
                    " ORDER BY c.name_city";
			
            $stmt = $db -> ejecutar($sql);
            return $db -> listar_indexed_array($stmt);
        }
    }

?>