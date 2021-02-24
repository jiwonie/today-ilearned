<?php

namespace Model;

use PDO;

/**
* * ORM 형태의 DB 호출 클래스
* todo: 1. mysql/oracle 구분 명확히
* todo: 2. 트랜잭션/커밋/롤백 다듬기
*/
class ORM
{
   public $oracle;
   public $mysql;

   public function __construct()
   {
       // 데이터베이스 설정값, 서버 외부에서 가져오기
       $db = parse_ini_file('storage/config/database.ini', true, INI_SCANNER_RAW);

       $this->oracle = $db["oracle"]["company_01"];
       $this->mysql = $db["mysql"]["company_02"];
   }

   /**
   * mysql, oracle where 구문 공통 함수
   *
   * @param
   * @return array
   */
   private function orm_where($where)
   {
       $i = 0;
       foreach ($where as $name => $value) {
           if ($value === "*") return array("execute" => array(":1" => "1"), "where" => ":1 = 1");
           if (is_int($name)) die("WHERE 조건은 키와 값이 있는 Map 배열 형식이어야 합니다.");

           if (is_array($value)) {
               $execute[":W_$name"] = $value[0];

               if (!isset($value[1]) || empty($value[1])) $value[1] = "=";
               if (!isset($value[2]) || empty($value[2])) $value[2] = "AND";
               if ($i === count($where) - 1) $value[2] = "";

               if ($value[1] === "LIKE") {
                   $wildcard = "%";
                   $where_case[] = "$name $value[1] :W_$name $value[2]";

                   $execute[":W_$name"] = $wildcard.$value[0].$wildcard;
               } else {
                   $where_case[] = "$name $value[1] :W_$name $value[2]";
               }
           } else {
               if ($i === count($where) - 1) $AND = "";
               else $AND = "AND";

               $execute[":W_$name"] = $value;
               $where_case[] = "$name = :W_$name $AND";
           }

           $i++;
       }

       $where_case = implode(" ", $where_case);

       return array(
           "execute" => $execute, 
           "where" => $where_case
           );
   }

   /**
   * mysql, oracle where 구문의 매개변수 처리 공통 함수
   *
   * @param
   * @return array
   */
   private function orm_param($parameter)
   {
       $set_params = array();
       foreach ($parameter as $name => $value) {
           if (is_int($name)) die("매개 변수의 이름은 키와 값이 있는 Map 배열 형식이어야 합니다.");
           if (is_array($value)) die("매개 변수의 값은 배열 형식이 아닙니다.");

           $set_params["sets"][] = "$name = :P_$name";
           $set_params["fields"][] = "$name";
           $set_params["colon_fields"][] = ":P_$name";
           $set_params["execute"][":P_$name"] = $value;
       }

       $sets = implode(", ",$set_params["sets"]);
       $fields = implode(", ",$set_params["fields"]);
       $colon_fields = implode(", ",$set_params["colon_fields"]);

       return array(
           "fields" => $fields, 
           "colon" => $colon_fields, 
           "sets" => $sets, 
           "execute" => $set_params["execute"]
           );
   }

   /**
   * mysql 커넥션을 반환합니다.
   *
   * @return PDO
   */
   function orm_mysql_conn()
   {
       $db = $this->mysql;

       $dsn = "mysql:host=" . $db["host"] . ";dbname=" . $db["dbname"];
       $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
       $conn = new PDO($dsn, $db["username"], $db["password"], $options);

       return $conn;
   }

   /**
    * mysql select example
    * 나이가 30세 이하 이거나, 키가 177 인 필드 검색
    * $mySelect("USER", array("AGE" => array("30", "<=", "OR"), "HEIGHT" => array("177")));
    * ["queryString"] => "SELECT * FROM `USER` WHERE `AGE` <= 30 OR `HEIGHT` = 177
    *
    * @param string $table
    * @param array $where array("FIELD" => array("VALUE", "Functional symbol", "Logical operator"), ...)
    * @param string $target
    * @param string $query
    * @return array
    */
   function orm_mysql_select($table, $where, $target = "*", $query = "")
   {
       $conn = $this->orm_mysql_conn();

       if (!is_array($where)) die("WHERE 조건은 배열 형식이어야 합니다.");
       if (!empty($query)) $query = "AND ($query)";

       $w = $this->orm_where($where);

       $prepared = $conn->prepare("SELECT $target FROM $table WHERE $w[where] $query");

       if ($prepared->execute($w["execute"])) $fetch = $prepared->fetchAll(\PDO::FETCH_ASSOC);
       else $fetch = array();

       return $fetch;
   }

   /**
    * mysql insert example
    * 나이가 32 이고, 키가 180 인 튜플(레코드) 생성
    * $myInsert("USER", array("AGE" => 32, "HEIGHT" => 180));
    * ["queryString"] => "INSERT INTO `USER` (`AGE`, `HEIGHT`) VALUES (:P_AGE, :P_HEIGHT)
    *
    * @param string $table
    * @param array $parameters
    * @return bool
    */
   function orm_mysql_insert($table, $parameters)
   {
       $conn = $this->orm_mysql_conn();

       if (!is_array($parameters)) die("매개 변수는 배열 형식이어야 합니다.");

       $p = $this->orm_param($parameters);

       $prepared = $conn->prepare("INSERT INTO {$table} ($p[fields]) VALUES ($p[colon])");

       return $prepared->execute($p["execute"]);
   }

   /**
    * mysql update example
    * 나이가 32 이상이거나, 키가 180 인 필드의 값을 28, 177 으로 수정
    * $myUpdate("USER", array("AGE" => 28, "HEIGHT" => 177), array("AGE" => array(32, ">=", "OR"), "HEIGHT" >= 180));
    * ["queryString"] => "UPDATE `USER` SET `AGE` = :P_AGE AND `HEIGHT` = :P_HEIGHT WHERE `AGE` >= :W_AGE" OR `HEIGHT` >= 180
    *
    * @param string $table
    * @param array $parameters
    * @param array $where
    * @param string $query
    * @return bool|int
    */
   function orm_mysql_update($table, $parameters, $where, $query = "")
   {
       $conn = $this->orm_mysql_conn();

       if (!is_array($parameters)) die("매개 변수는 배열 형식이어야 합니다.");
       if (!is_array($where)) die("WHERE 조건은 배열 형식이어야 합니다.");
       if (!empty($query)) $query = "AND ($query)";

       $p = $this->orm_param($parameters);
       $w = $this->orm_where($where);

       $prepared = $conn->prepare("UPDATE $table SET $p[sets] WHERE $w[where] $query");

       if ($prepared->execute(array_merge($p["execute"], $w["execute"]))) return $prepared->rowCount();
       else return false;
   }

   /**
    * mysql delete example
    * 나이가 32 이상이면 모두 삭제
    * $myDelete("USER", array("*"), "`AGE` >= 32");
    * ["queryString"] => "SELECT * FROM `USER` WHERE 1 `AGE` >= :W_AGE"
    *
    * @param string $table
    * @param array $where
    * @param string $query
    * @return bool|int
    */
   function orm_mysql_delete($table, $where, $query = "")
   {
       $conn = $this->orm_mysql_conn();

       if (!is_array($where)) die("WHERE 조건은 배열 형식이어야 합니다.");
       if (!empty($query)) $query = "AND ($query)";

       $w = $this->orm_where($where);

       $prepared = $conn->prepare("DELETE FROM $table WHERE $w[where] $query");

       if ($prepared->execute($w["execute"])) return $prepared->rowCount();
       else return false;
   }

   /**
   * oracle 커넥션을 반환합니다.
   *
   * @return resource
   */
   function orm_oracle_conn()
   {
       $db = $this->oracle;

       if (!function_exists("oci_connect")) die ("oci_connect() 를 사용할 수 없습니다.");

       $conn = oci_connect(
           "$db[user]", 
           "$db[password]", 
           "$db[ip]/$db[database_name]", 
           "$db[charset]"
           );

       if (!$conn) {
           $e = oci_error();
           trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
       } else {
           return $conn;
       }

       die ("OCI 연결이 되지 않았습니다.");
   }

   function orm_oci_exe_proc($conn, $execute)
   {
       if ($execute) {
           oci_commit($conn);
           oci_close($conn);
           return true;
       } else {
           oci_rollback($conn);
           oci_close($conn);
           return false;
       }
   }

   function orm_oci_select($table, $where, $target = "*", $query = "")
   {
       $conn = $this->orm_oracle_conn();

       if (!is_array($where)) die("WHERE 조건은 배열 형식이어야 합니다.");
       if (!empty($query)) $query = "AND ($query)";

       $w = $this->orm_where($where);

       $stmt = oci_parse($conn, "select $target from $table where $w[where] $query");

       foreach ($w["execute"] as $bv_name => $variable) {
           $result = oci_bind_by_name($stmt, $bv_name, $w["execute"][$bv_name]) ? "success" : "fail";
       }

       oci_execute($stmt);
       oci_fetch_all($stmt, $output, null, null, OCI_FETCHSTATEMENT_BY_ROW);

       return $output;
   }

   function orm_oci_ins_exe($table, $parameters)
   {
       $conn = $this->orm_oracle_conn();

       $p = $this->orm_param($parameters);

       // Index 필드는 테이블명에 ID를 붙히고, 앞 구분자를 F 로 표시
       $AUTO_INCREMENT["ID"] = str_replace("T", "F", $table) . "ID";
       // Index 필드의 시퀀스 이름은 테이블명 뒤에 _SEQ를 붙힘
       $AUTO_INCREMENT["SEQ"] = $table . "_SEQ.NEXTVAL";
       $stmt = oci_parse($conn, "INSERT INTO $table ($AUTO_INCREMENT[ID], $p[fields]) VALUES($AUTO_INCREMENT[SEQ], $p[colon])");

       foreach ($p["execute"] as $bv_name => $variable) {
           $result = oci_bind_by_name($stmt, $bv_name, $p["execute"][$bv_name]) ? "success" : "fail";
       }

       return array("conn" => $conn, "execute" => oci_execute($stmt, OCI_NO_AUTO_COMMIT));
   }

   function orm_oci_upd_exe($table, $parameters, $where, $query = "")
   {
       $conn = $this->orm_oracle_conn();

       if (!is_array($parameters)) die("매개 변수는 배열 형식이어야 합니다.");
       if (!is_array($where)) die("WHERE 조건은 배열 형식이어야 합니다.");
       if (!empty($query)) $query = "AND ($query)";

       $p = $this->orm_param($parameters);
       $w = $this->orm_where($where);

       $stmt = oci_parse($conn, "update $table set $p[sets] where $w[where] $query");

       foreach (array_merge($p["execute"], $w["execute"]) as $bv_name => $variable) {
           $result = oci_bind_by_name($stmt, $bv_name, $w["execute"][$bv_name]) ? "success" : "fail";
       }

       return array("conn" => $conn, "execute" => oci_execute($stmt, OCI_NO_AUTO_COMMIT));
   }

   function orm_oci_del_exe($table, $where, $query = "")
   {
       $conn = $this->orm_oracle_conn();

       if (!is_array($where)) die("WHERE 조건은 배열 형식이어야 합니다.");
       if (!empty($query)) $query = "AND ($query)";

       $w = $this->orm_where($where);

       $stmt = oci_parse($conn, "delete from $table where $w[where] $query");

       foreach ($w["execute"] as $bv_name => $variable) {
           $result = oci_bind_by_name($stmt, $bv_name, $w["execute"][$bv_name]) ? "success" : "fail";
       }

       return array("conn" => $conn, "execute" => oci_execute($stmt, OCI_NO_AUTO_COMMIT));
   }
}