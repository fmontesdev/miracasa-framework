<?php
class profile_model {
    private $bll;
    static $_instance;
    
    function __construct() {
        $this -> bll = profile_bll::getInstance();
    }

    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function get_loadProfile_user($token) {
        return $this -> bll -> get_loadProfile_user_BLL($token);
    }

    public function get_load_bills($token) {
        return $this -> bll -> get_load_bills_BLL($token);
    }

    public function get_load_likes($token) {
        return $this -> bll -> get_load_likes_BLL($token);
    }

    public function get_update_user($args) {
        return $this -> bll -> get_update_user_BLL($args);
    }
}