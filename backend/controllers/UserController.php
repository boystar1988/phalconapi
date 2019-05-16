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
        $res = $this->user->getUsers($condition);
        return $this->success($res);
    }

    /**
     * 创建/修改会员
     * @return \Phalcon\Http\ResponseInterface
     */
    public function updateAction()
    {
        $data = $this->request->getPost();
        $res = $this->user->saveUser($data);
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
        $res = $this->user->getOne($uid);
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
        $res = $this->user->deleteUser($uid);
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
        $res = $this->user->search();
        return $this->success($res);
    }

}