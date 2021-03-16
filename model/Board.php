<?php

namespace Model;

class Board extends Model
{
    function insertBoard(array $input = array())
    {
        if (empty($input['subject']) || !$_SESSION['IS_LOGIN']) {
            return false;
        }
    
        return $this->insert(
            "board", 
            "subject = :subject, content = :content, create_at = NOW(),create_by = '{$_SESSION['LOGIN_ID']}'",
            [":subject" => $input['subject'], ":content" => $input['content']]
        );
    }

    function updateBoard(array $input = array())
    {
        if (empty($input['subject']) || !$_SESSION['IS_LOGIN']) {
            return false;
        }
    
        return $this->update(
            "board", 
            "subject = :subject, content = :content", "idx = :idx AND create_by = :create_by",
            [":subject" => $input['subject'], ":content" => $input['content'], ":idx" => $input['idx'], ":create_by" => $_SESSION['LOGIN_ID']]
        );
    }

    function deleteBoard(array $input = array())
    {
        if (empty($input['idx']) || !$_SESSION['IS_LOGIN']) {
            return false;
        }
    
        return $this->delete("board", "idx = :idx AND create_by = :create_by", [":idx" => $input["idx"], ":create_by" => $_SESSION["LOGIN_ID"]]);
    }

    function getBoard(string $idx = '')
    {
        return $this->select("board", "idx = :idx", "*", [":idx" => $idx]);
    }

    function getBoardsWithPaging(string $page = '1')
    {
        $first_index    = 10 * ($page - 1);
        $list_per_page  = 10;

        $count = $this->select("board", "true", "COUNT(1) AS `count`")[0]['count'];
        $total_page = (int) ceil($count / 10);
        $first_page = floor($page / 10 - 0.1) * 10 + 1;
        $last_page  = ceil($page / 10) * 10;

        if ($last_page >= $total_page) {
            $last_page = $total_page;
        }
        
        $prev_page  = ($first_page <= 1) ? 1 : $first_page - 1;
        $next_page  = ($last_page < $total_page) ? $last_page + 1 : $total_page;

        $boards['boards'] = $this->select("board", "true ORDER BY idx DESC LIMIT $first_index, $list_per_page");

        $boards['paging'] = array(
                'first' => $first_page, 
                'last' => $last_page, 
                'total' => $total_page, 
                'prev' => $prev_page, 
                'next' => $next_page,
                'now' => $page
        );
        return $boards;
    }
}