<?php

class pdf {
    public static function generate($bill_data, $bill_items, $bill_total){
        require_once ('vendor/autoload.php'); // if you use Composer
        
        $billData = ['name' => $bill_data->username, 
                        'email' => $bill_data->email, 
                        'id_bill' => $bill_data->id_bill,
						'date' => $bill_data->date,
						'iva' => number_format($bill_total->total-($bill_total->total/1.21), 2, ',', '.'),
						'total' => number_format($bill_total->total, 2, '.', ',')];

        // Generar HTML para los items
        $item_rows = '';
        foreach ($bill_items as $item) {
            $item_rows .= "<tr class='item'>
                            <td>{$item['quantity']}</td>
                            <td>{$item['description']}</td>
                            <td>{$item['price']}</td>
                            <td>{$item['import']}</td>
                        </tr>";
        }

        // Cargar contenido HTML y reemplazar marcadores de posición
        $html_template = file_get_contents('bills/template/default_bill.html');

        foreach ($billData as $key => $value) {
            $html_template = str_replace("{{{$key}}}", $value, $html_template);
        }

        // Reemplazar los items
        $html_template = str_replace('{{items}}', $item_rows, $html_template);

        // Configurar Dompdf
        $options = new Dompdf\Options();
        $options->set('defaultFont', 'sans-serif');
        $dompdf = new Dompdf\Dompdf($options);

        // Cargar HTML con datos dinámicos
        $dompdf->loadHtml($html_template);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Obtener contenido PDF. Para guardar en el servidor
        $pdf_content = $dompdf->output();

        if ($pdf_content) {
            // Guardar el PDF en el servidor
            $path = 'bills/pdf/' . $bill_data->id_bill . '.pdf';
            file_put_contents($path, $pdf_content);
            return "done";
        } else {
            return "error_pdf";
        }

        // Generar y enviar el PDF al navegador
        // $dompdf->stream("factura.pdf", ["Attachment" => 0]);
    }
}