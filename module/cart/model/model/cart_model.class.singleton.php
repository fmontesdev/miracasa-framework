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
}