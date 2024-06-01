<?php
class cart_model {
    private $bll;
    static $_instance;
    
    function __construct() {
        $this -> bll = cart_bll::getInstance();
    }

    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function get_insert_cart($args) {
        return $this -> bll -> get_insert_cart_BLL($args);
    }

    public function get_select_cart($token) {
        return $this -> bll -> get_select_cart_BLL($token);
    }

    public function get_update_cart($args) {
        return $this -> bll -> get_update_cart_BLL($args);
    }

    public function get_billing($token) {
        $data = $this -> bll -> get_insert_bill_BLL($token);
        $this -> bll -> get_insert_bill_detail_BLL($data);
        $this -> bll -> get_update_stock_BLL($data['uid']);
        $this -> bll -> get_insert_purchase_log_BLL($data['uid']);
        return $this -> bll -> get_delete_cart_BLL($data['uid']);
    }

    public function get_quantity_cart($token) {
        return $this -> bll -> get_quantity_cart_BLL($token);
    }
}