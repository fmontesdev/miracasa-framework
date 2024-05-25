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

        function recover_view() {
            common::load_view('top_page_login.html', VIEW_PATH_LOGIN . 'recover_pass.html');
        }
 
        function register() {
            echo json_encode(common::load_model('login_model', 'get_register', [$_POST['username'], $_POST['password'], $_POST['email'], $_POST['phone']]));
        }

        function login() {
            echo json_encode(common::load_model('login_model', 'get_login', [$_POST['username'], $_POST['password']]));
        }

        function otp_login() {
            echo json_encode(common::load_model('login_model', 'get_otp_login', [$_POST['uid'], $_POST['otp']]));
        }

        function social_login() {
            echo json_encode(common::load_model('login_model', 'get_social_login', [$_POST['uid'], $_POST['username'], $_POST['email'], $_POST['phone'], $_POST['avatar'], $_POST['provider']]));
        }

        function verify_email() {
            echo json_encode(common::load_model('login_model', 'get_verify_email', $_POST['token_email']));
        }

        function send_recover_email() {
            echo json_encode(common::load_model('login_model', 'get_send_recover_email', $_POST['email_recover']));
        }

        function verify_token() {
            echo json_encode(common::load_model('login_model', 'get_verify_token', $_POST['token_email']));
        }

        function new_password() {
            echo json_encode(common::load_model('login_model', 'get_new_password', [$_POST['uid'], $_POST['new_pass']]));
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