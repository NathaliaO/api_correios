<?php

namespace Controller;

session_start();
require_once 'DatabaseController.php';

class AuthorizationController {
    public function index(){
        $dataJson = file_get_contents('php://input');
        $data = json_decode($dataJson, true);
        $conn = new DatabaseController();

        $urlAutenticacao = 'https://api.correios.com.br/token/v1/autentica';

        $user = $data['user'];
        $password = $data['password'];

        $tokenExpired = $conn->verifyTokenExpirated($user, $password);

        if($tokenExpired){
            echo 'You are already authenticated.';
            exit();
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $urlAutenticacao);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic ' . base64_encode($user . ':'. $password)
        ));

        $response = curl_exec($curl);

        if(curl_errno($curl)){
            echo 'ERROR: ' . curl_error($curl);
        }

        curl_close($curl);

        $response = json_decode($response);

        $explodedIp = explode(',', $response->ip);
        $infoAuth = [
            'cpf' => $response->id,
            'ip' => $explodedIp[0],
            'profileUser' => $response->perfil,
            'generate' => $response->emissao,
            'expiresIn' => $response->expiraEm,
            'token' => $response->token,
        ];

        $respStatus = $conn->insert('authorization', 'cpf, ip, profileUser, generate, expiresIn, token', $infoAuth);
        if($respStatus == 201){
            $_SESSION[ 'user' ] = $user;
            $_SESSION[ 'password' ] = $password;
            echo 'Authenticated successfully';
        }
    }
}