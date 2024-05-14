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

    }

?>