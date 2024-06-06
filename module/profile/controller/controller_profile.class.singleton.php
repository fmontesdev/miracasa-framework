<?php
    class controller_profile {

        static $_instance;
        
        public static function getInstance() {
            if (!(self::$_instance instanceof self)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        function view() {
            common::load_view('top_page_profile.html', VIEW_PATH_PROFILE . 'profile.html');
        }

        function loadProfile_user() {
            echo json_encode(common::load_model('profile_model', 'get_loadProfile_user', $_POST['token']));
        }
 
        function load_bills() {
            echo json_encode(common::load_model('profile_model', 'get_load_bills', $_POST['token']));
        }
    }

?>