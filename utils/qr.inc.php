<?php
    class qr {
        public static function generate($url, $id_bill){
            require_once ('phpqrcode/qrlib.php');

            // Contenido del código QR
            $content = $url;

            // Nombre del archivo donde se guardará el código QR
            $filename = BILL_QR . $id_bill . '.png';

            // Nivel de corrección de errores (L, M, Q, H)
            $errorCorrectionLevel = 'M';

            // Tamaño de cada "píxel" en el código QR
            $matrixPointSize = 8;

            // Margen (espacio en blanco alrededor del código QR)
            $margin = 2;

            // Generar el código QR y guardarlo en un archivo
            QRcode::png($content, $filename, $errorCorrectionLevel, $matrixPointSize, $margin);

            // Mostrar el código QR directamente en el navegador
            // header('Content-Type: image/png');
            // QRcode::png($content, null, $errorCorrectionLevel, $matrixPointSize, $margin);

            return "done";
        }
    }