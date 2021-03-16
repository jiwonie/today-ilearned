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
                    $_SESSION['IS_LOGIN'] ?? $this->relocation('/board', 'You need to log in');
                    
                    $this->view('board/write');
                } else if (is_numeric($this->__variables['board'])) {
                    $this->view('board/read', $board->getBoard($this->__variables['board'])[0]);
                } else {
                    $this->view('board/list', $board->getBoardsWithPaging($this->__variables['page'] ?? '1'));
                }
                break;
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