<?php

namespace Controller\Sample;

use Controller\Controller;
use Model\Sample\Member as ModelSampleMember;

class Member extends Controller
{
    public function __construct($__exec_func = '/', $__variables = [])
    {
        parent::__construct($__exec_func, $__variables);
        $this->{$this->__exec_func}();
    }

    public function join()
    {
        $input  = $this->injection($_POST);

        switch ($input['_method']) {
            case 'post':
                $this->joinProcess($input);
                break;

            default:
                $this->view('sample/sign/up');
                break;
        }
    }

    public function joinProcess($input)
    {
        $member   = new ModelSampleMember();
        $result = $member->insertMember($input);

        if (is_numeric($result)) {
            $this->relocation('/', 'You are now a member.');
        } else {
            $this->relocation('/', 'Register failed');
        }
    }

    public function login()
    {
        $input = $this->injection($_POST);
        
        switch ($input['_method']) {
            case 'put':
                $this->loginProcess($input);
                break;
            default:
                $this->view('sample/sign/in');
                break;
        }
    }
    
    public function loginProcess($input)
    {
        $member   = new ModelSampleMember();
        $result = $member->selectMember($input);

        if (isset($result[0]['id']) && !empty($result[0]['id'])) {
            $_SESSION['IS_LOGIN']   = true;
            $_SESSION['LOGIN_ID']   = $result[0]['id'];
            $_SESSION['LOGIN_NAME'] = $result[0]['name'];

            $this->relocation('/', 'You are logged in');
        } else {
            $this->relocation('/', 'Login failed');
        }
    }

    public function logout()
    {
        session_destroy();
        $this->relocation('/', 'You are logged out');
    }
}