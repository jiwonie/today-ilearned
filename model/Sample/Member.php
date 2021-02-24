<?php

namespace Model\Sample;

use Model\Model;

class Member extends Model
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * * 사용자 추가
     * 
     * @param array $input
     * @return string|integer|boolean
     */
    function insertMember(array $input = array())
    {
        if ($input['pw'] !== $input['pw_confirm']) {
            return false;
        }
    
        return $this->query("
            INSERT INTO 
                localhost.member 
            SET 
                id   = :id,
                pw   = :pw,
                name = :name
            ", array(
                ':id'   => $input['id'],
                ':pw'   => $this->password($input['pw']),
                ':name' => $input['name']
            ));
    }

    /**
     * * 사용자 검색
     * 
     * @param array $input
     * @return array
     */
    function selectMember(array $input = array())
    {
        return $this->query("
            SELECT 
                `id`, `name` 
            FROM 
            localhost.member 
            WHERE 
                id = :id 
                AND pw = :pw
            ", array(
                ':id' => $input['id'], 
                ':pw' => $this->password($input['pw'])
            ));
    }

    /**
     * * 모든 사용자 가져오기
     * 
     * @return array
     */
    function getMembers()
    {
        return $this->query("SELECT * FROM localhost.member");
    }
}