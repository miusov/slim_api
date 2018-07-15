<?php

class DB{
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '123456';
    private $dbname = 'customers_test';

    public function connect()
    {
        $mysql_connect_str = "mysql:host=$this->host;dbname=$this->dbname";
        $dbConnection = new PDO($mysql_connect_str, $this->user,$this->pass);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
    }
}