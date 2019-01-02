<?php

class UserTask extends \Phalcon\CLI\Task{

    public function mainAction()    {
        echo "comme in...".PHP_EOL;
        //连接Beanstalk
        $queue = new Phalcon\Queue\Beanstalk($this->config->beanstalk->toArray());
        echo "begin ...".PHP_EOL;
        //监视指定tube
        $queue->watch("user_job");
        echo "ok..";
        while(true){
            echo "监控任务中...".PHP_EOL;
            //获取任务
            $job = $queue->reserve();
            if(!$job){
                echo "任务不可用，已忽略...".PHP_EOL;
            }else{
                $job_id = $job->getId();
                echo '执行任务： '.$job_id.PHP_EOL;
                //获取任务详情
                $jobInfo = $job->getBody();
                echo '任务详情：'.$jobInfo['msg'].PHP_EOL;
                $job->delete();
                echo '成功执行任务， '.$job_id.'. 删除中...'.PHP_EOL;
            }
        }
    }

}