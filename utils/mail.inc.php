<?php
    class mail {
        public static function send_email($message) {
            switch ($message['type']) {
                case 'validate':
                    $email['fromEmail'] = 'onboarding@resend.dev';
                    $email['inputEmail'] = 'onboarding@resend.dev';
                    $email['inputMatter'] = 'Miracasa: validación de registro';
                    $email['inputMessage'] = "<h2>Verificación de email.</h2><a href='http://miracasa.fw/login/verify/$message[token]'>Haz clic aquí para verificar tu email.</a>";
                    break;
                case 'recover':
                    $email['fromEmail'] = 'onboarding@resend.dev';
                    $email['inputEmail'] = 'onboarding@resend.dev';
                    $email['inputMatter'] = 'Miracasa: recuperación de contraseña';
                    $email['inputMessage'] = "<a href='http://miracasa.fw/login/recover/$message[token]'>Haz clic aquí para recuperar tu contraseña.</a>";
                    break;
            }
            return self::send_resend($email);
            // return $email;
            // print_r($email);
        }

        public static function send_resend($values){
            $cnfg = parse_ini_file(MODEL_PATH . "credentials.ini");

            // Mensaje a enviar
            $message = array();
            $message['from'] = $values['fromEmail'];
            $message['to'] = $cnfg['RESEND_TO_EMAIL'];
            $message['h:Reply-To'] = $values['inputEmail'];
            $message['subject'] = $values['inputMatter'];
            $message['html'] = $values['inputMessage'];

            // Convierte los datos a formato JSON
            $jsonMessage = json_encode($message);

            // Inicializa la sesión cURL
            $ch = curl_init();

            // Configura las opciones cURL
            curl_setopt($ch, CURLOPT_URL, $cnfg['RESEND_API_URL']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $cnfg['RESEND_API_KEY'],
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonMessage)
            ));
            curl_setopt($ch, CURLOPT_POST, true); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonMessage);

            // Establece un tiempo de espera de conexión de 10 segundos
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

            // Desactiva la verificación del certificado SSL/TLS
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            // Ejecuta la solicitud y captura la respuesta
            $result = curl_exec($ch);

            // Cierra la sesión cURL
            curl_close($ch);
            
            // Maneja los errores
            if (curl_errno($ch)) {
                $error_msg = 'Error en cURL: ' . curl_error($ch);
                curl_close($ch); // Cierra la sesión cURL
                return $error_msg;
            } else {
                curl_close($ch); // Cierra la sesión cURL
                return $result;
            }
        }
    }