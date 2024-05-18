<?php
    class mail {
        public static function send_email($email) {
            switch ($email['type']) {
                case 'validate';
                    $email['fromEmail'] = 'onboarding@resend.dev';
                    $email['inputEmail'] = 'onboarding@resend.dev';
                    $email['inputMatter'] = 'Miracasa: validación de registro';
                    $email['inputMessage'] = "<h2>Verificación de email.</h2><a href='http://miracasa.fw/login/verify/$email[token]'>Haz clic aquí para verificar tu email.</a>";
                    break;
                case 'recover';
                    $email['fromEmail'] = 'onboarding@resend.dev';
                    $email['inputEmail'] = 'onboarding@resend.dev';
                    $email['inputMatter'] = 'Miracasa: recuperación de contraseña';
                    $email['inputMessage'] = "<a href='http://miracasa.fw/login/recover/$email[token]'>Haz clic aquí para recuperar tu contraseña.</a>";
                    break;
            }
            return self::send_resend($email);
        }

        public static function send_resend($values){
            require __DIR__ . '/vendor/autoload.php'; // __DIR__ magic constant: si se usa dentro de un include, se devuelve el directorio del archivo incluido

            // Assign a new Resend Client instance to $resend variable, which is automatically autoloaded...
            $resend = Resend::client('re_fWCbxP1n_9HayMvpR4idRzsCpzGZsZUmK');

            try {
                $result = $resend->emails->send([
                    'from' => $values['fromEmail'],
                    'to' => ['f.montesdoria@gmail.com'],
                    'subject' => $values['inputMatter'],
                    'html' => $values['inputMessage'],
                ]);
            } catch (\Exception $e) {
                exit('Error: ' . $e->getMessage());
            }

            // Show the response of the sent email to be saved in a log...
            return $result->toJson();
        }
    }