<?php

class QueueTask extends \Phalcon\CLI\Task{

    public function listenAction()
    {
        //监视指定tube
        echo "[".date("Y-m-d H:i:s")."] "."正在监听 ...".PHP_EOL;
        $this->queue->watch('phalconapi');
        while(true){
            //Todo: 获取任务
            /** @var \Phalcon\Queue\Beanstalk\Job $job */
            $job = $this->queue->reserve();
            if($job){
                $jobId = $job->getId();
                echo "[".date("Y-m-d H:i:s")."] 任务ID(".$jobId.")加入队列".PHP_EOL;
                $jobInfo = $job->getBody();
                //Todo: 首字母大写，下划线转驼峰
                $listenerName = ucwords($jobInfo['listener']).'Listener';
                /** @var ListenerInterface $listener */
                $listener = new $listenerName();
                $listener->run($jobInfo['data']);
//                $job->delete();  //删除
//                $job->release(); //释放
                $job->bury();    //保留
            }
        }
    }

}