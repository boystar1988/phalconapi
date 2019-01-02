# PhalconApi整合版

作者：[赵卓彬](http://boystar.cn)

时间：[2019-01-01](#)

**打造专用API高性能框架**

>本项目基于phalcon7项目，并在原有项目上面进行一些工具的整合，非常适合做高性能的前后端分离的API开发

### 特性：

### Model数据校验
该项目整合了Model数据校验，在phalcon框架的model校验基础上，通过命令行脚本获取数据库结构并生成php配置文件，
在保存数据前，为model新增、更新前增加一层数据校验，从而保护数据库，避免出现数据溢出或非法数据等，当数据库结
构发生变化时，也可以通过命令行重新生成配置，详细的命令行用法如下：

##### 生成数据表校验配置
```
php app/cli.php db renew
```
执行完毕后，会生成文件：config/dbmap.php

##### 生成Model
```
php devtools/phalcon.php create-model pha_user --get-set --force --doc --mapcolumn --extends=BaseModel
```
执行完毕后，会生成model文件：models/*.php , 解放双手

> 会生成文件：config/dbmap.php

model保存时，只需通过`$mode->loadAndSave($data)`即可完成数据装载，数据校验和数据保存操作,例子：

```
$model = new PhaUser();
$data = [
    'username'=>'zhangsan',
    'password'=>md5('123456'),
    'is_del'=>0,
];
if($mode->loadAndSave($data)){
    return ['code'=>0,'msg'=>'success'];
}else{
    return ['code'=>1,'msg'=>$model->getFirstMessage()];
}
``` 
