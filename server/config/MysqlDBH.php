<?php

namespace app\config;

class MysqlDBH implements DatabaseHandler
{


    // DB Details
    private $host = 'localhost';
    private $password = "";
    private $dbname = "archiveiot";
    private $username = 'root';
    private $charset = 'utf8mb4';
    private $connectionString;

    // Remote

    // private $host = "127.0.0.1";
    // private $username = "root_server_hms";
    // private $password = "1hvqjFNv3#3S";
    // private $dbname = "archiveiot_db";
    // private $charset = 'utf8mb4';






    function __construct()
    {
        try {
            $dsn = "mysql:host=$this->host;charset=$this->charset;dbname=$this->dbname";
            $this->connectionString = new \PDO($dsn, $this->username, $this->password);
        } catch (\Exception $ex) {
            echo ('Oooooops; we are unable to connect to server at the moment...');
        }
    }

    function connection()
    {
        return $this->connectionString;
    }
}
