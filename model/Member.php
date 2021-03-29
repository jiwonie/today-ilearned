<?php

namespace Model;

class Member extends Model
{
    /**
     * @param array $input
     * @return bool|int
     */
    function insertMember(array $input = [])
    {
        if ($input['pw'] !== $input['pw_confirm']) {
            return false;
        }
    
        return $this->insert(
            "member", 
            "id = :id, pw = :pw, name = :name", 
            [":id" => $input['id'], ":pw" => $this->password($input["pw"]), ":name" => $input["name"]]
        );
    }

    /**
     * @param array $input
     * @return array
     */
    function selectMember(array $input = array())
    {
        return $this->select("member", "id = :id ANd pw = :pw", "*", [":id" => $input["id"], ":pw" => $this->password($input["pw"])]);
    }

    /**
     * @return array
     */
    function getMembers()
    {
        return $this->select("member");
    }
}