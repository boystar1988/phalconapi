<?php

class DefaultListener implements ListenerInterface
{

    public function run($data)
    {
        echo "[".date("Y-m-d H:i:s")."] "."执行成功".PHP_EOL;
    }

}
