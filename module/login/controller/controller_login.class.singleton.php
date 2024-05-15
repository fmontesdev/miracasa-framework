<?php
    class controller_login {

        static $_instance;
        
        public static function getInstance() {
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        function view() {
            common::load_view('top_page_login.html', VIEW_PATH_LOGIN . 'login_register.html');
        }
 
        function register() {
            echo json_encode(common::load_model('login_model', 'get_register', [$_POST['username'], $_POST['password'], $_POST['email']]));
        }

        function login() {
            echo json_encode(common::load_model('login_model', 'get_login', [$_POST['username'], $_POST['password']]));
        }

    }
    
?>