<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        return $this->success();
    }

    /**
     * 消息队列测试
     * @return \Phalcon\Http\ResponseInterface
     */
    public function jobAction()
    {
        $queue = new Phalcon\Queue\Beanstalk($this->config->beanstalk->toArray());
        //choose方法指定tube
        $queue->choose("user_job");
        //创建任务
        for($i=0;$i<1;$i++){
            $queueId = $queue->put(['msg' => 'hello phalcon('.$i.')'],[]);
            echo '任务Id:'.$queueId."\n";
        }
        exit;
    }

}
