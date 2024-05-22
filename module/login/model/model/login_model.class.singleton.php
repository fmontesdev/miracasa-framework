<?php
class login_model {
    private $bll;
    static $_instance;
    
    function __construct() {
        $this -> bll = login_bll::getInstance();
    }

    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function get_register($args) {
        return $this -> bll -> get_register_BLL($args);
    }

    public function get_login($args) {
        return $this -> bll -> get_login_BLL($args);
    }

    public function get_otp_login($args) {
        return $this -> bll -> get_otp_login_BLL($args);
    }

    public function get_social_login($args) {
        return $this -> bll -> get_social_login_BLL($args);
    }

    public function get_verify_email($token_email) {
        return $this -> bll -> get_verify_email_BLL($token_email);
    }

    public function get_send_recover_email($email_recover) {
        return $this -> bll -> get_send_recover_email_BBL($email_recover);
    }

    public function get_verify_token($token_email) {
        return $this -> bll -> get_verify_token_BLL($token_email);
    }

    public function get_new_password($args) {
        return $this -> bll -> get_new_password_BLL($args);
    }

    public function get_data_user($token) {
        return $this -> bll -> get_data_user_BLL($token);
    }

    public function get_logout() {
        return $this -> bll -> get_logout_BLL();
    }

    public function get_control_user($args) {
        return $this -> bll -> get_control_user_BBL($args);
    }

    public function get_control_activity() {
        return $this -> bll -> get_control_activity_BLL();
    }

    public function get_refresh_cookie() {
        return $this -> bll -> get_refresh_cookie_BLL();
    }
}