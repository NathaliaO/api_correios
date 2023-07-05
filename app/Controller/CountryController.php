<?php

namespace Controller;

require_once 'DatabaseController.php';

class CountryController {
    public function index(){
        $dataJson = file_get_contents('php://input');
        $data = json_decode($dataJson, true);

        if(empty($data['country'])){
            echo 'Enter the country';
            exit();
        }


        $urlCountry = 'https://apps.correios.com.br/localidades/v1/paises/'. $data['country']. '/cidades';
        $conn = new DatabaseController();

        if(!isset($_SESSION['user'])){
            echo 'You are not authenticated. Please authenticate first.';
            exit();
        }

        $tokenExpired = $conn->verifyTokenExpirated($_SESSION['user']);

        if(!$tokenExpired){
            echo 'You are not authenticated. Please authenticate first.';
            exit();
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $urlCountry);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic ' . base64_encode($_SESSION['user'] . ':'. $_SESSION['password'])
        ));

        $response = curl_exec($curl);

        if(curl_errno($curl)){
            echo 'ERROR: ' . curl_error($curl);
        }

        curl_close($curl);

        $response = json_decode($response);

        $verifyExists = $conn->verifyCountryExists($data['country']);

        if(!$verifyExists){
            foreach($response->cidades as $city){
                $conn->insert('cities', 'code, name, country', [
                    'code' => $city->coCidade,
                    'name' => $city->noCidade,
                    'country' => $city->sgPais
                ]);
            }
            echo 'Cities saved successfully in the database';
            exit();
        }

        echo 'There is already a record of the cities for this country in the database.';
        
    }
}