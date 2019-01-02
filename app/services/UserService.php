<?php

class UserService
{

    /**
     * @var \Phalcon\Di
     */
    public $di;

    /**
     * 获取会员列表
     * @param array $con
     * @return array
     */
    public function getUsers($con=[])
    {
        $qb = PhaUser::query();
        return PageHelper::pageOutput($qb, $con['page_no']??PAGE_NO_DEFAULT, $con['page_size']??PAGE_SIZE_DEFAULT);
    }

    /**
     * 获取一个会员
     * @param $uid
     * @return array|bool
     */
    public function getOne($uid)
    {
        if(empty($uid)) return false;
        $model = PhaUser::findFirst(["uid = ".$uid]);
        return $model ? $model->toArray() : false;
    }

    /**
     * 保存用户
     * @param $data
     * @return array
     */
    public function saveUser($data)
    {
        try{
            if(!empty($data['uid'])){
                $model = PhaUser::findFirst(['uid = '.$data['uid']]);
                if(empty($model)){
                    throw new \Exception(TIPS_NOT_FOUND_RECORD);
                }
            }else{
                $model = new PhaUser();
            }
            if($model->loadAndSave($data)){
                return ["code"=>SUCCESS_CODE,"msg"=>TIPS_SAVE_OK,"data"=>$model];
            }else{
                throw new \Exception(array_shift($model->getMessages()));
            }
        }catch (\Exception $e){
            return ["code"=>FAIL_CODE,"msg"=>$e->getMessage()];
        }
    }

    /**
     * 删除用户
     * @param $uid
     * @return array
     */
    public function deleteUser($uid)
    {
        try{
            if($uid){
                /** @var PhaUser $model */
                $model = PhaUser::findFirst(['uid = '.$uid]);
                if(empty($model)){
                    throw new \Exception(TIPS_NOT_FOUND_RECORD);
                }
            }else{
                throw new \Exception(TIPS_NOT_FOUND_RECORD);
            }
            $model->is_del = PhaUser::IS_DEL_TRUE;
            if($model->validation() && $model->save()){
                return ["code"=>SUCCESS_CODE,"msg"=>TIPS_DELETE_OK,"data"=>$model];
            }else{
                throw new \Exception(array_shift($model->getMessages()));
            }
        }catch (\Exception $e){
            return ["code"=>FAIL_CODE,"msg"=>$e->getMessage()];
        }
    }

    /**
     * 搜索用户
     * @param string $condition
     * @return array
     */
    public function search($condition='')
    {
        $qb = PhaUser::query();
        if($condition){
            $qb->andWhere($condition);
        }
        return PageHelper::pageOutput($qb);
    }

}
