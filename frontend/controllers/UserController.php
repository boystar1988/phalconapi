<?php

class UserController extends ControllerBase
{

    /**
     * 会员列表
     * @return \Phalcon\Http\ResponseInterface
     */
    public function indexAction()
    {
        $condition['page_no'] = $this->request->get('page_no','int',1);
        $condition['page_size'] = $this->request->get('page_size','int',15);
        /** @var UserService $userService */
        $userService = $this->user;
        $res = $userService->getUsers($condition);
        return $this->success($res);
    }

    /**
     * 创建/修改会员
     * @return \Phalcon\Http\ResponseInterface
     */
    public function updateAction()
    {
        $data = $this->request->getPost();
        /** @var UserService $userService */
        $userService = $this->user;
        if(!$data){
            return $this->error(API_FAIL_CODE,"提交的会员信息不能为空");
        }
        $res = $userService->saveUser($data);
        if($res['code'] == SUCCESS_CODE){
            return $this->success();
        }else{
            return $this->error(API_FAIL_CODE,$res['msg']??"保存失败");
        }
    }

    /**
     * 会员详情
     * @return \Phalcon\Http\ResponseInterface
     */
    public function detailAction()
    {
        $uid = $this->request->get('uid','int',0);
        /** @var UserService $userService */
        $userService = $this->user;
        $res = $userService->getOne($uid);
        if($res === false){
            return $this->error(API_FAIL_CODE,"会员不存在");
        }
        return $this->success($res);
    }

    /**
     * 删除会员
     * @return \Phalcon\Http\ResponseInterface
     */
    public function deleteAction()
    {
        $uid = $this->request->getPost('uid');
        /** @var UserService $userService */
        $userService = $this->user;
        $res = $userService->deleteUser($uid);
        if($res['code'] == SUCCESS_CODE){
            return $this->success();
        }else{
            return $this->error(API_FAIL_CODE,$res['msg']??"删除失败");
        }
    }

    /**
     * 搜索会员
     * @return \Phalcon\Http\ResponseInterface
     */
    public function searchAction()
    {
        /** @var UserService $userService */
        $userService = $this->user;
        $res = $userService->search();
        return $this->success($res);
    }

}