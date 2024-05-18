<?php
    class middleware_auth {
        public static function decode_token($type, $token) {
            $jwt = parse_ini_file(MODEL_PATH . "jwt.ini");

            if ($type == 'access') {
                $secret = $jwt['JWT_SECRET_ACCESS'];
            } else if ($type == 'refresh'){
                $secret = $jwt['JWT_SECRET_REFRESH'];
            } else if ($type == 'verify'){
                $secret = $jwt['JWT_SECRET_VERIFY'];
            }

            $JWT = new jwt;
            $token_dec = $JWT -> decode($token, $secret);
            $rt_token = json_decode($token_dec, TRUE);
            return $rt_token;
        }

        public static function create_token($type, $uid, $username) {
            $jwt = parse_ini_file(MODEL_PATH . "jwt.ini");

            $header = '{
                "typ": "'. $jwt['JWT_TYPE'] .'",
                "alg": "'. $jwt['JWT_ALG'] .'"
            }';
            //iat: Tiempo que inició el token
            //exp: Tiempo que expirará el token
            //name: info user
            if ($type == 'access') {
                $payload = '{
                    "iat": "'. time() .'", 
                    "exp": "'. time() + $jwt['JWT_EXP_ACCESS'] .'",
                    "uid": "'. $uid .'",
                    "username": "'. $username .'"
                }';
                $secret = $jwt['JWT_SECRET_ACCESS'];
            } else if ($type == 'refresh'){
                $payload = '{
                    "iat": "'. time() .'", 
                    "exp": "'. time() + $jwt['JWT_EXP_REFRESH'] .'",
                    "uid": "'. $uid .'",
                    "username": "'. $username .'"
                }';
                $secret = $jwt['JWT_SECRET_REFRESH'];
            } else if ($type == 'verify'){
                $payload = '{
                    "iat": "'. time() .'", 
                    "exp": "'. time() + $jwt['JWT_EXP_VERIFY'] .'",
                    "uid": "'. $uid .'",
                    "username": "'. $username .'"
                }';
                $secret = $jwt['JWT_SECRET_VERIFY'];
            }
        
            $JWT = new jwt;
            $token = $JWT -> encode($header, $payload, $secret);
            return $token;
        }
    }
?>