<?php

namespace Controller;

use Model\Member;

class Index extends Controller 
{
    public function __construct($__variables = [])
    {
        parent::__construct($__variables);
        $this->{$this->__variables['method']}();
    }

    public function index()
    {
        $member = new Member();
        $get_members = $member->getMembers();

        $this->view('index/index', $get_members);
    }
}