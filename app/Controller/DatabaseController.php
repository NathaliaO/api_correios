<?php

namespace Controller;

use DateTime;
use mysqli;

class DatabaseController {
    private $conn;

    public function __construct()
    {
        $username = 'root';
        $password = 'root';
        $database = 'api_correios';
        $servername = 'mariadb';

        $this->conn = new mysqli($servername, $username, $password, $database);

        if($this->conn->connect_error) {
            die('Error connection with database'. $this->conn->connect_error);
        }
    }

    public function insert($table, $column, $values)
    {
        $valuesInsert = "'" . implode("', '", $values) . "'";

        $sql = "INSERT INTO $table ($column) VALUES ($valuesInsert)";

        if ($this->conn->query($sql) === TRUE) {
            return 201;
        } else {
            return 400;
        }
    }

    public function verifyTokenExpirated($user){
        date_default_timezone_set('America/Sao_Paulo');
        $sql = "SELECT * FROM authorization WHERE cpf = '$user' ORDER BY id DESC LIMIT 1";

        $result = $this->conn->query($sql);

        if($result){
            if($result->num_rows > 0){
                $authenticate = $result->fetch_assoc();

                $expires = new DateTime($authenticate['expiresIn']);
                $now = new DateTime(date('Y-m-d H:i:s'));

                if($expires > $now){
                    return true;
                }

                return false;
            } 
            return false;
        }
    }

    public function verifyCountryExists($country){
        $sql = "SELECT * FROM cities WHERE country = '$country'";

        $result = $this->conn->query($sql);

        if($result){
            if($result->num_rows > 0){
                return true;
            }
            return false;
        }
    }

}