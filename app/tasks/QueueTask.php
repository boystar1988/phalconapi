<?php

class QueueTask extends \Phalcon\CLI\Task{

    public function listenAction()
    {
        //监视指定tube
//        $queue->watch(Beanstalk::DEFAULT_TUBE);
        echo "[".date("Y-m-d H:i:s")."] "."正在监听 ...".PHP_EOL;
        while(true){
            //Todo: 获取任务
            /** @var \Phalcon\Queue\Beanstalk\Job $job */
            $job = $this->queue->reserve();
            if(!$job){
                echo "[".date("Y-m-d H:i:s")."] "."任务不可用，已忽略...".PHP_EOL;
            }else{
                echo "[".date("Y-m-d H:i:s")."] "."任务可用，继续...".PHP_EOL;
                $job_id = $job->getId();
                $jobInfo = $job->getBody();
                //Todo: 首字母大写，下划线转驼峰
                $listenerName = ucwords($jobInfo['listener']).'Listener';
                echo "[".date("Y-m-d H:i:s")."] ".'监听器：'.$listenerName.PHP_EOL;
                echo "[".date("Y-m-d H:i:s")."] ".'传入任务参数：'.json_encode($jobInfo['data']).PHP_EOL;
                /** @var ListenerInterface $listener */
                $listener = new $listenerName();
                $listener->run($jobInfo['data']);
                echo "[".date("Y-m-d H:i:s")."] ".'删除完成的任务(ID:'.$job_id.')...'.PHP_EOL;
                $job->delete();
                echo "[".date("Y-m-d H:i:s")."] ".'成功...'.PHP_EOL;
            }
        }
    }

}