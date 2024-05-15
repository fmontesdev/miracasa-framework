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

        function verify_email() {
            echo json_encode(common::load_model('login_model', 'get_verify_email', $_POST['token_email']));
        }

        function send_recover_email() {
            echo json_encode(common::load_model('login_model', 'get_send_recover_email', $_POST['email_recover']));
        }

        function data_user() {
            echo json_encode(common::load_model('login_model', 'get_data_user', $_POST['token']));
        }

        function logout() {
            echo json_encode(common::load_model('login_model', 'get_logout'));
        }

        function control_user() {
            echo json_encode(common::load_model('login_model', 'get_control_user', [$_POST['accessToken'], $_POST['refreshToken']]));
        }

        function control_activity() {
            echo json_encode(common::load_model('login_model', 'get_control_activity'));
        }

        function refresh_cookie() {
            echo json_encode(common::load_model('login_model', 'get_refresh_cookie'));
        } 
    }
    
?>