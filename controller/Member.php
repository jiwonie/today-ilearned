<?php

namespace Controller;

use Model\Member as ModelMember;

class Member extends Controller
{
    public function __construct($__variables = [])
    {
        parent::__construct($__variables);
        $this->{$this->__variables['method']}();
    }

    public function join()
    {
        $input  = $this->injection($_POST);

        switch ($input['_method']) {
            case 'post':
                $this->joinProcess($input);
                break;

            default:
                $this->view('sign/up');
                break;
        }
    }

    public function joinProcess($input)
    {
        $member   = new ModelMember();
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
                $this->view('sign/in');
                break;
        }
    }
    
    public function loginProcess($input)
    {
        $member   = new ModelMember();
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