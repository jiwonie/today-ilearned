<?php

namespace Controller;

use Model\Board as ModelBoard;

class Board extends Controller
{
    public function __construct($__variables = [])
    {
        parent::__construct($__variables);
        $this->{$this->__variables['method']}();
    }

    public function board():void
    {
        $get  = $this->injection($_GET);
        $post = $this->injection($_POST);

        switch ($post['_method']) {
            case 'post':
                $this->writeProcess($post);
                break;
            case 'patch':
                $this->updateProcess($post);
                break;
            case 'delete':
                $this->deleteProcess($post);
                break;
            
            default:
                $board = new ModelBoard();

                if ($this->__variables['writable']) {

                    // 쓰기 페이지
                    // for RESTFUL_API
                    $_SESSION['IS_LOGIN'] ?? $this->relocation('/board', 'You need to log in');
                    $this->view('board/write');

                } else if (is_numeric($this->__variables['board'])) {

                    // 상세 페이지
                    // for RESTFUL_API
                    $this->view('board/read', $board->getBoard($this->__variables['board'])[0]);

                } else if (is_numeric($get['no'])) {

                    // 상세 페이지 ($_GET 메소드 활용, 예시로 작성함)
                    // for NORMAL_URL
                    $this->view('board/read', $board->getBoard($get['no'])[0]);

                } else {

                    // 글 목록 페이지
                    // for RESTFUL_API
                    $this->view('board/list', $board->getBoardsWithPaging($this->__variables['page'] ?? '1'));

                    // 글 목록 페이지 ($_GET 메소드 활용, 예시로 작성함)
                    // for NORMAL_URL
                    // $this->view('board/list', $board->getBoardsWithPaging($get['p'] ?? '1'));
                }
                break;
        }
    }

    public function boardWrite():void
    {
        // 쓰기 페이지 (예시로 작성함)
        // for NORMAL_URL
        if (!$_SESSION['IS_LOGIN']) {
            $this->relocation('/board', 'You need to log in');
        } else {
            $this->view('board/write');
        }
    }
    
    public function writeProcess(array $post):void
    {
        $_SESSION['IS_LOGIN'] ?? $this->relocation('/board', 'You need to log in');

        $board  = new ModelBoard();
        $result = $board->insertBoard($post);

        if (is_numeric($result)) {
            $this->relocation('/board', 'Post was created');
        } else {
            $this->relocation('/board', 'Failed to create post');
        }
    }

    public function updateProcess(array $post):void
    {
        $_SESSION['IS_LOGIN'] ?? $this->relocation('/board', 'You need to log in');

        $board  = new ModelBoard();
        $result = $board->updateBoard($post);

        if (is_numeric($result)) {
            $this->relocation('/board', "Post has been modified");
        } else {
            $this->relocation('/board', 'Failed to modify posts');
        }
    }

    public function deleteProcess(array $post):void
    {
        $_SESSION['IS_LOGIN'] ?? $this->relocation('/board', 'You need to log in');

        $board  = new ModelBoard();
        $result = $board->deleteBoard($post);

        if (is_numeric($result)) {
            $this->relocation('/board', "Posts have been deleted");
        } else {
            $this->relocation('/board', 'Failed to delete post');
        }
    }
}