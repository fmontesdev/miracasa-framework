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

    }

?>