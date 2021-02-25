<?php

namespace Model\Sample;

use Model\Model;

class Board extends Model
{
    function __construct()
    {
        parent::__construct();
    }

    /**
    * * 게시글 추가
    * 
    * @param array $input
    * @return string|integer|boolean
    */
    function insertBoard(array $input = array())
    {
        if (empty($input['subject']) || !$_SESSION['IS_LOGIN']) {
            return false;
        }
    
        return $this->query("
            INSERT INTO 
                localhost.board
            SET 
                subject   = :subject,
                content   = :content,
                create_at = NOW(),
                create_by = '{$_SESSION['LOGIN_ID']}'
            ", array(
                ':subject'   => $input['subject'],
                ':content'   => $input['content']
            ));
    }

    /**
    * * 게시글 수정
    * 
    * @param array $input
    * @return string|integer|boolean
    */
    function updateBoard(array $input = array())
    {
        if (empty($input['subject']) || !$_SESSION['IS_LOGIN']) {
            return false;
        }
    
        return $this->query("
            UPDATE
                localhost.board
            SET 
                subject = :subject,
                content = :content
            WHERE
                idx = :idx
                AND create_by = :create_by
            ", array(
                ':subject'   => $input['subject'],
                ':content'   => $input['content'],
                ':idx'       => $input['idx'],
                ':create_by' => $_SESSION['LOGIN_ID']
            ));
    }

    /**
    * * 게시글 삭제
    * 
    * @param array $input
    * @return string|integer|boolean
    */
    function deleteBoard(array $input = array())
    {
        if (empty($input['idx']) || !$_SESSION['IS_LOGIN']) {
            return false;
        }
    
        return $this->query("
            DELETE FROM
                localhost.board
            WHERE
                idx = :idx
                AND create_by = :create_by
            ", array(
                ':idx'       => $input['idx'],
                ':create_by' => $_SESSION['LOGIN_ID']
            ));
    }

    /**
    * * 게시글 검색
    * 
    * @param string $idx
    * @return array
    */
    function getBoard(string $idx = '')
    {
        return $this->query("
            SELECT 
                *
            FROM 
            localhost.board 
            WHERE 
                idx = :idx
            ", array(
                ':idx' => $idx
            ));
    }

    /**
    * * 전체 리스트 검색
    * 
    * @param string $page
    * @return null|array
    */
    function getBoardsWithPaging(string $page = '1')
    {
        $first_index    = 10 * ($page - 1);
        $list_per_page  = 10;

        $count = $this->query("SELECT COUNT(1) AS 'count' FROM localhost.board")[0]['count'];
        $total_page = (int) ceil($count / 10);
        $first_page = floor($page / 10 - 0.1) * 10 + 1;
        $last_page  = ceil($page / 10) * 10;

        if ($last_page >= $total_page) {
            $last_page = $total_page;
        }
        
        $prev_page  = ($first_page <= 1) ? 1 : $first_page - 1;
        $next_page  = ($last_page < $total_page) ? $last_page + 1 : $total_page;

        $boards['boards'] = $this->query("
                SELECT 
                    * 
                FROM 
                    localhost.board 
                ORDER BY
                    idx DESC
                LIMIT 
                    {$first_index}, 
                    {$list_per_page}
        ");

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