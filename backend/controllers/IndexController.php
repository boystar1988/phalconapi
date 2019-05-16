<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        return $this->success(['info'=>'后台应用']);
    }

    public function errorAction()
    {
        return $this->success(['info'=>'404']);
    }

}
