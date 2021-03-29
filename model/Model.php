<?php

namespace Model;

use PDO;
use PDOException;

class Model
{
    protected $conn;
    
    /**
     * ROOT 외부에서 데이터베이스 값 설정
     * 설정 파일은 외부 접속이 불가하도록 보안 필요
     * 
     *
     * - database.ini -
     * 
     * [mysql]
     * host     = localhost
     * port     = 3306
     * dbname   = localhost
     * username = localhost
     * password = localhost
     * charset  = utf8
     * 
     * [oracle]
     * host     = localhost
     * port     = 1521
     * user     = localhost
     * dbname   = localhost
     * password = localhost
     * charset  = utf8
     */
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

    /**
     * SELECT 쿼리 함수
     * 
     * $where 변수는 execute 실행 시, 바인딩 될 값을 파라미터로 포함해야 함
     * $where 변수 뒤로 GROUP BY, HAVING, ORDER BY, LIMIT 등의 추가 쿼리 작성 가능
     * 
     * 
     * @param string $table  테이블명
     * @param string $where  WHERE절 쿼리
     * @param string $target 출력할 칼럼명
     * @param array $execute WHERE절에서 사용한 파라미터 바인딩 배열
     * @return array
     */
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

    /**
     * INSERT 쿼리 함수
     * 
     * $parameters 변수는 execute 실행 시, 바인딩 될 값을 파라미터로 포함해야 함
     * INSERT INTO table_name VALUES (value, ...) 형태는 사용하지않음
     * 
     * 
     * @param string $table      테이블명
     * @param string $parameters 입력할 칼럼명
     * @param array $execute     입력 파라미터의 바인딩 배열
     * @return bool|int
     */
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

    /**
     * UPDATE 쿼리 함수
     * 
     * $parameters 변수는 execute 실행 시, 바인딩 될 값을 파라미터로 포함해야 함
     * 
     * 
     * @param string $table      테이블명
     * @param string $parameters 수정할 칼럼명
     * @param string $where      WHERE절 쿼리
     * @param array $execute     WHERE절에서 사용한 파라미터 바인딩 배열
     * @return bool|int
     */
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

    /**
     * DELETE 쿼리 함수
     * 
     * $where 변수는 execute 실행 시, 바인딩 될 값을 파라미터로 포함해야 함
     * $where 변수 뒤로 GROUP BY, HAVING, ORDER BY, LIMIT 등의 추가 쿼리 작성 가능
     * 
     * 
     * @param string $table  테이블명
     * @param string $where  WHERE절 쿼리
     * @param array $execute WHERE절에서 사용한 파라미터 바인딩 배열
     * @return bool|int
     */
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

    /**
     * 쿼리 직접 실행 함수
     * 
     * 
     * @param string $query     execute 실행 시, 바인딩 될 값을 파라미터로 포함해야 함
     * @param array $parameters 쿼리로 작성한 파라미터 바인딩 배열
     * @return bool|int|array
     */
    function query(string $query, array $parameters = array())
    {
        $result = false;
        $query_type = strtolower(substr(trim($query), 0, 6));

        try {
            $statement = $this->conn->prepare($query);
            $statement->execute($parameters);
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

    /**
     * 비밀번호 입력 및 대조 시, 인코딩 처리 함수
     * 
     * 
     * @param string $password
     * @return string
     */
    function password(string $password):string
    {
        return base64_encode(hash('sha512', $password, true));
    }
}