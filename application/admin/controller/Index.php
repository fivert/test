<?php
namespace app\admin\controller;

class Index
{
    public function index()
    {
        return view();
    }
    
    public function test(){
        return 'test';
    }

    public function fuck(){
        echo 'fuck 123';
        return 'fuck';
    }

}
