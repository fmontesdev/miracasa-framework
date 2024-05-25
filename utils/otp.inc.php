<?php
    class otp {
        public static function send_msg($otp, $phone){
            require_once ('vendor/autoload.php'); // if you use Composer

            $cnfg = parse_ini_file(MODEL_PATH . "credentials.ini");
            $client = new UltraMsg\WhatsAppApi($cnfg['ULTRAMSG_TOKEN'], $cnfg['ULTRAMSG_INSTANCE_ID']);

            // Mensaje a enviar
            $to = $cnfg['ULTRAMSG_TO_PHONE']; 
            $body = "Su código de autenticación para MiraCasa es: " . $otp; 

            $result = $client -> sendChatMessage($to, $body);
            return $result;
        }
    }