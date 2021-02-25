<?php

namespace Model;

use PDO;
use PDOException;

class Model 
{
    protected $pdo;

    /**
    * * 외부 폴더에서 데이터베이스 설정값 가져옴
    * ! 해당 폴더는 외부 접속이 불가하도록 설정 필요
    *
    * (database.ini example)
    * [database]
    * db_host      = ''
    * db_port      = ''
    * db_name      = ''
    * db_user      = ''
    * db_password  = ''
    */
    function __construct()
    {
        $database = parse_ini_file('./../config/database.ini');

        try {
            $this->pdo = new PDO(
                "mysql:host={$database['db_host']};dbname={$database['db_name']}", 
                $database['db_user'], 
                $database['db_password']
            );

            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            http_response_code(500);
            exit('Please set up the DB');
        }
    }

    /**
    * * 입력 된 쿼리 타입에 따라 반환되는 값을 별도 지정
    * 
    * @param string $query
    * @param array $parameter
    * 
    * @return mixed
    */
    function query(string $query, array $parameter = array())
    {
        $query_type = strtolower(substr(trim($query), 0, 6));

        try {
            $statement = $this->pdo->prepare($query);
            $statement->execute($parameter);
        } catch (PDOException $e) {
            http_response_code(500);
            exit("Query error [{$e->getCode()}]");
        }

        switch ($query_type) {
            case 'insert':
                return $this->pdo->lastInsertId();
            break;

            case 'select': 
                return $statement->fetchAll(PDO::FETCH_ASSOC);
            break;
            
            case 'update':
            case 'delete':
                return $statement->rowCount();
            break;
        }
    }

    /**
    * * 비밀번호 입력 시, 단방향 암호화 처리
    */
    function password(string $password):string
    {
        return base64_encode(hash('sha512', $password, true));
    }
}