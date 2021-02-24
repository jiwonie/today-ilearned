<?php

namespace Controller\Sample;

use Controller\Controller;
use Model\Sample\Board as ModelSampleBoard;

class Board extends Controller
{
    public function __construct($__exec_func = '/', $__variables = [])
    {
        parent::__construct($__exec_func, $__variables);
        $this->{$this->__exec_func}();
    }

    public function board()
    {
        switch ($this->__variables['board']) {
            case 'write':
                if (!$_SESSION['IS_LOGIN']) {
                    $this->relocation('/board', 'You need to log in');
                } else {
                    $this->view('sample/board/write');

                    $input  = $this->injection($_POST);

                    switch ($input['_method']) {
                        case 'post':
                            $this->writeProcess($input);
                            break;
                    }
                }
                break;

            case 'update':
                $input = $this->injection($_POST);
                
                switch ($input['_method']) {
                    case 'patch':
                        $this->updateProcess($input);
                        break;
                }
                break;
            
            case 'delete':
                $input = $this->injection($_POST);
                
                switch ($input['_method']) {
                    case 'delete':
                        $this->deleteProcess($input);
                        break;
                }
                break;
                
            default:
                $board = new ModelSampleBoard();
            
                if (is_numeric($this->__variables['board'])) {
                    $return = $board->getBoard($this->__variables['board'])[0];
                    $this->view('sample/board/read', $return);
                } else {
                    $page   = $this->__variables['page'] ?? '1';
                    $return = $board->getBoardsWithPaging($page);
                    
                    $this->view('sample/board/list', $return);
                }
                break;
        }
    }
    
    public function writeProcess($input)
    {
        $board  = new ModelSampleBoard();
        $result = $board->insertBoard($input);

        if (is_numeric($result)) {
            $this->relocation('/board', 'Post was created');
        } else {
            $this->relocation('/board', 'Failed to create post');
        }
    }

    public function updateProcess($input)
    {
        $board  = new ModelSampleBoard();
        $result = $board->updateBoard($input);

        if (is_numeric($result)) {
            $this->relocation('/board', "Post has been modified");
        } else {
            $this->relocation('/board', 'Failed to modify posts');
        }
    }

    public function deleteProcess($input)
    {
        $board  = new ModelSampleBoard();
        $result = $board->deleteBoard($input);

        if (is_numeric($result)) {
            $this->relocation('/board', "Posts have been deleted");
        } else {
            $this->relocation('/board', 'Failed to delete post');
        }
    }
}