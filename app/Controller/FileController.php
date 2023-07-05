<?php

namespace Controller;

require_once 'DatabaseController.php';

class FileController {
    public function index(){
        $conn = new DatabaseController();

        $dataString = file_get_contents('php://input');
        // Criar um arquivo temporário para ler os dados como um arquivo
        $tmpFile = tmpfile();
        fwrite($tmpFile, $dataString);
        fseek($tmpFile, 0);

        // Ler os dados do arquivo como um CSV
        if (($handle = fopen(stream_get_meta_data($tmpFile)['uri'], "r")) !== false) {
            // Ler as linhas do CSV
            while (($data = fgetcsv($handle)) !== false) {
                $conn->insert('orders', 'code, document, delivery_date, sale_date, price, id_prod, quantity, observation', [
                    'code' => $data[0],
                    'document' => $data[1],
                    'delivery_date' => $data[2],
                    'sale_date' => $data[3],
                    'price' => $data[4],
                    'id_prod' => $data[5],
                    'quantity' => $data[6],
                    'observation' => $data[7],
                ]);

            }

            fclose($handle);
        }

        echo 'File Save! ';
    }
}