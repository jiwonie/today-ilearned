<?php

namespace Controller\Sample;

use Controller\Controller;
use Model\Sample\Member;

class Index extends Controller 
{
    public function __construct($__exec_func = '/', $__variables = [])
    {
        parent::__construct($__exec_func, $__variables);
        $this->{$this->__exec_func}();
    }

    public function index()
    {

        $member = new Member();
        $get_members = $member->getMembers();

        $this->view('sample/index/index', $get_members);
    }
}