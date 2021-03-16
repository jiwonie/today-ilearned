<?php

namespace Model;

class Member extends Model
{
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

    function selectMember(array $input = array())
    {
        return $this->select("member", "id = :id ANd pw = :pw", "*", [":id" => $input["id"], ":pw" => $this->password($input["pw"])]);
    }

    function getMembers()
    {
        return $this->select("member");
    }
}