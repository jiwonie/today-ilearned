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

    public function board()
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

                    // for restful url (write page)
                    $_SESSION['IS_LOGIN'] ?? $this->relocation('/board', 'You need to log in');
                    $this->view('board/write');

                } else if (is_numeric($this->__variables['board'])) {

                    // for restful url (detail page)
                    $this->view('board/read', $board->getBoard($this->__variables['board'])[0]);

                } else if (is_numeric($get['no'])) {

                    // for basic url (get method : detail page)
                    $this->view('board/read', $board->getBoard($get['no'])[0]);

                } else {

                    // for restful url (paging)
                    $this->view('board/list', $board->getBoardsWithPaging($this->__variables['page'] ?? '1'));

                    // for basic url (get method : paging)
                    // $this->view('board/list', $board->getBoardsWithPaging($get['p'] ?? '1'));

                }
                break;
        }
    }

    public function boardWrite()
    {
        // for basic url (write page)
        if (!$_SESSION['IS_LOGIN']) {
            $this->relocation('/board', 'You need to log in');
        } else {
            $this->view('board/write');
        }
    }
    
    public function writeProcess($post)
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

    public function updateProcess($post)
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

    public function deleteProcess($post)
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