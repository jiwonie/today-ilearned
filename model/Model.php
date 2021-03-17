<?php

namespace Model;

use PDO;
use PDOException;

class Model
{
    protected $conn;

    public function __construct($database = 'mysql')
    {
        $_ini = parse_ini_file('./../config/database.ini', true, INI_SCANNER_RAW)[$database];

        try {
            $dsn = "mysql:host={$_ini["host"]};dbname={$_ini["dbname"]}";
            $options = [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$_ini['charset']}"
            ];
            
            $this->conn = new PDO($dsn, $_ini["username"], $_ini["password"], $options);
        } catch (PDOException $e) {
            http_response_code(500);
            require 'view/errors/500.php';
            exit('Please set up the DB');
        }
    }

    function select(string $table = "board", string $where = "true", string $target = "*", array $execute = []):array
    {
        $result = [];

        try {
            $prepared = $this->conn->prepare("SELECT $target FROM $table WHERE $where");
            $prepared->execute($execute);

            $result = $prepared->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            http_response_code(500);
            require 'view/errors/500.php';
            exit('Query error ' . $e->getCode());
        }

        return $result;
    }

    function insert(string $table = "", string $parameters = "", array $execute = [])
    {
        $result = false;

        try {
            $prepared = $this->conn->prepare("INSERT INTO $table SET $parameters");
            $prepared->execute($execute);

            $result = $this->conn->lastInsertId();
        } catch (PDOException $e) {
            http_response_code(500);
            require 'view/errors/500.php';
            exit('Query error ' . $e->getCode());
        }

        return $result;
    }

    function update(string $table = "", string $parameters = "", string $where = "true", array $execute = [])
    {
        $result = false;

        try {
            $prepared = $this->conn->prepare("UPDATE $table SET $parameters WHERE $where");
            $prepared->execute($execute);

            $result = $prepared->rowCount();
        } catch (PDOException $e) {
            http_response_code(500);
            require 'view/errors/500.php';
            exit('Query error ' . $e->getCode());
        }

        return $result;
    }

    function delete(string $table = "", string $where = "true", array $execute = [])
    {
        $result = false;
        
        try {
            $prepared = $this->conn->prepare("DELETE FROM $table WHERE $where");
            $prepared->execute($execute);

            $result = $prepared->rowCount();
        } catch (PDOException $e) {
            http_response_code(500);
            require 'view/errors/500.php';
            exit('Query error ' . $e->getCode());
        }

        return $result;
    }

    function query(string $query, array $parameter = array())
    {
        $result = false;
        $query_type = strtolower(substr(trim($query), 0, 6));

        try {
            $statement = $this->conn->prepare($query);
            $statement->execute($parameter);
        } catch (PDOException $e) {
            http_response_code(500);
            require 'view/errors/500.php';
            exit('Query error ' . $e->getCode());
        }

        switch ($query_type) {
            case 'insert':
                $result = $this->conn->lastInsertId();
            break;

            case 'select': 
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            break;
            
            case 'update':
            case 'delete':
                $result = $statement->rowCount();
            break;
        }

        return $result;
    }

    function password(string $password):string
    {
        return base64_encode(hash('sha512', $password, true));
    }
}